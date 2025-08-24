package com.example.facerecognitionapp;

import android.Manifest;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.ImageFormat;
import android.graphics.Matrix; // <<< THÊM IMPORT NÀY
import android.graphics.Rect;
import android.graphics.YuvImage;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.camera.core.CameraSelector;
import androidx.camera.core.ImageCapture;
import androidx.camera.core.ImageCaptureException;
import androidx.camera.core.ImageProxy;
import androidx.camera.lifecycle.ProcessCameraProvider;
import androidx.camera.view.PreviewView;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.google.common.util.concurrent.ListenableFuture;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;

import java.io.ByteArrayOutputStream;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutionException;

public class FaceRegisterActivity extends AppCompatActivity {
    private static final int CAMERA_REQUEST_CODE = 123;

    private PreviewView previewView;
    private ImageView capturedImage;
    private Button btnCapture, btnConfirm, btnRetake;

    private ProgressBar progressBar;
    private ImageCapture imageCapture;
    private FirebaseFirestore db;
    private Bitmap capturedBitmap;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_face_register);

        previewView = findViewById(R.id.previewView);
        capturedImage = findViewById(R.id.capturedImage);
        btnCapture = findViewById(R.id.btnCapture);
        btnConfirm = findViewById(R.id.btnConfirm);
        btnRetake = findViewById(R.id.btnRetake);
        progressBar = findViewById(R.id.progressBar);

        db = FirebaseFirestore.getInstance();

        if (checkCameraPermission()) {
            startCamera();
        } else {
            requestCameraPermission();
        }

        setupButtonClickListeners();
    }

    private void setupButtonClickListeners() {
        btnCapture.setOnClickListener(v -> {
            if (imageCapture != null) {
                takePhoto();
            } else {
                Toast.makeText(this, "Camera chưa sẵn sàng", Toast.LENGTH_SHORT).show();
            }
        });

        btnRetake.setOnClickListener(v -> resetToPreviewState());

        btnConfirm.setOnClickListener(v -> {
            if (capturedBitmap != null) {
                // <<< SỬA LẠI: Hiển thị ProgressBar và ẩn các nút
                showProcessingUI(true);

                new Thread(() -> processAndSaveImage()).start();
            }
        });
    }

    // <<< HÀM MỚI: Dùng để quản lý trạng thái UI khi xử lý
    private void showProcessingUI(boolean isProcessing) {
        if (isProcessing) {
            progressBar.setVisibility(View.VISIBLE);
            btnConfirm.setVisibility(View.GONE);
            btnRetake.setVisibility(View.GONE);
        } else {
            progressBar.setVisibility(View.GONE);
            btnConfirm.setVisibility(View.VISIBLE);
            btnRetake.setVisibility(View.VISIBLE);
        }
    }

    // <<< HÀM MỚI: Đưa giao diện về trạng thái camera
    private void resetToPreviewState() {
        previewView.setVisibility(View.VISIBLE);
        capturedImage.setVisibility(View.GONE);
        btnCapture.setVisibility(View.VISIBLE);
        btnConfirm.setVisibility(View.GONE);
        btnRetake.setVisibility(View.GONE);
        capturedBitmap = null;
    }

    private boolean checkCameraPermission() {
        return ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA) == PackageManager.PERMISSION_GRANTED;
    }

    private void requestCameraPermission() {
        ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.CAMERA}, CAMERA_REQUEST_CODE);
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == CAMERA_REQUEST_CODE) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                startCamera();
            } else {
                Toast.makeText(this, "Bạn cần cấp quyền camera để đăng ký khuôn mặt", Toast.LENGTH_LONG).show();
                finish();
            }
        }
    }

    private void startCamera() {
        ListenableFuture<ProcessCameraProvider> cameraProviderFuture = ProcessCameraProvider.getInstance(this);
        cameraProviderFuture.addListener(() -> {
            try {
                ProcessCameraProvider cameraProvider = cameraProviderFuture.get();
                androidx.camera.core.Preview preview = new androidx.camera.core.Preview.Builder().build();
                preview.setSurfaceProvider(previewView.getSurfaceProvider());
                imageCapture = new ImageCapture.Builder().setCaptureMode(ImageCapture.CAPTURE_MODE_MINIMIZE_LATENCY).build();
                CameraSelector cameraSelector = new CameraSelector.Builder().requireLensFacing(CameraSelector.LENS_FACING_FRONT).build();
                cameraProvider.unbindAll();
                cameraProvider.bindToLifecycle(this, cameraSelector, preview, imageCapture);
            } catch (ExecutionException | InterruptedException e) {
                Log.e("DEBUG", "Lỗi khởi động camera: ", e);
            }
        }, ContextCompat.getMainExecutor(this));
    }

    private void takePhoto() {
        if (imageCapture == null) return;
        imageCapture.takePicture(ContextCompat.getMainExecutor(this), new ImageCapture.OnImageCapturedCallback() {
            @Override
            public void onCaptureSuccess(@NonNull ImageProxy image) {
                Bitmap unrotatedBitmap = imageProxyToBitmap(image);
                int rotationDegrees = image.getImageInfo().getRotationDegrees();
                image.close();

                if (unrotatedBitmap == null) {
                    Toast.makeText(FaceRegisterActivity.this, "Lỗi lấy ảnh", Toast.LENGTH_SHORT).show();
                    return;
                }

                Bitmap rotatedBitmap = rotateBitmap(unrotatedBitmap, rotationDegrees);
                // <<< SỬA LỖI MIRROR: Lật ảnh lại theo chiều ngang
                capturedBitmap = flipBitmapHorizontal(rotatedBitmap);

                previewView.setVisibility(View.GONE);
                capturedImage.setVisibility(View.VISIBLE);
                capturedImage.setImageBitmap(capturedBitmap);
                btnCapture.setVisibility(View.GONE);
                btnConfirm.setVisibility(View.VISIBLE);
                btnRetake.setVisibility(View.VISIBLE);
            }

            @Override
            public void onError(@NonNull ImageCaptureException exception) {
                Log.e("DEBUG", "Lỗi chụp ảnh: ", exception);
            }
        });
    }

    private Bitmap rotateBitmap(Bitmap source, float angle) {
        Matrix matrix = new Matrix();
        matrix.postRotate(angle);
        return Bitmap.createBitmap(source, 0, 0, source.getWidth(), source.getHeight(), matrix, true);
    }

    // <<< HÀM MỚI: Dùng để lật bitmap (sửa lỗi mirror)
    private Bitmap flipBitmapHorizontal(Bitmap source) {
        Matrix matrix = new Matrix();
        matrix.preScale(-1.0f, 1.0f);
        return Bitmap.createBitmap(source, 0, 0, source.getWidth(), source.getHeight(), matrix, true);
    }

    private void processAndSaveImage() {
        if (capturedBitmap == null) {
            runOnUiThread(() -> showProcessingUI(false));
            return;
        }

        Bitmap face = FaceAnalyzer.detectFace(capturedBitmap, this);
        if (face == null) {
            runOnUiThread(() -> {
                Toast.makeText(FaceRegisterActivity.this, "Không tìm thấy khuôn mặt! Vui lòng chụp lại.", Toast.LENGTH_LONG).show();
                showProcessingUI(false); // Trả lại UI
            });
            return;
        }

        float[] embedding = FaceEmbeddingHelper.getFaceEmbedding(face, this);
        if (embedding == null) {
            runOnUiThread(() -> {
                Toast.makeText(FaceRegisterActivity.this, "Lỗi sinh vector!", Toast.LENGTH_SHORT).show();
                showProcessingUI(false); // Trả lại UI
            });
            return;
        }

        saveEmbeddingToFirestore(embedding);
    }

    private void saveEmbeddingToFirestore(float[] embedding) {
        List<Float> list = new ArrayList<>();
        for (float f : embedding) list.add(f);

        FirebaseUser currentUser = FirebaseAuth.getInstance().getCurrentUser();
        if (currentUser == null) {
            runOnUiThread(() -> {
                Toast.makeText(this, "Bạn chưa đăng nhập!", Toast.LENGTH_SHORT).show();
                showProcessingUI(false);
            });
            return;
        }

        String uid = currentUser.getUid();
        db.collection("users").document(uid).get()
                .addOnSuccessListener(userDoc -> {
                    if (userDoc.exists()) {
                        String studentId = userDoc.getString("student_id");
                        if (studentId != null && !studentId.isEmpty()) {
                            findStudentAndUpdateEmbedding(studentId, list);
                        } else {
                            runOnUiThread(() -> {
                                Toast.makeText(this, "Tài khoản chưa liên kết với mã sinh viên!", Toast.LENGTH_SHORT).show();
                                showProcessingUI(false);
                            });
                        }
                    } else {
                        runOnUiThread(() -> {
                            Toast.makeText(this, "Không tìm thấy thông tin người dùng!", Toast.LENGTH_SHORT).show();
                            showProcessingUI(false);
                        });
                    }
                })
                .addOnFailureListener(e -> runOnUiThread(() -> {
                    Toast.makeText(this, "Lỗi lấy thông tin người dùng: " + e.getMessage(), Toast.LENGTH_SHORT).show();
                    showProcessingUI(false);
                }));
    }

    private void findStudentAndUpdateEmbedding(String studentId, List<Float> embeddingList) {
        db.collection("students").whereEqualTo("student_id", studentId).get()
                .addOnSuccessListener(querySnapshot -> {
                    if (!querySnapshot.isEmpty()) {
                        DocumentSnapshot studentDoc = querySnapshot.getDocuments().get(0);
                        studentDoc.getReference().update("vector_embedding", embeddingList)
                                .addOnSuccessListener(aVoid -> {
                                    runOnUiThread(() -> {
                                        Toast.makeText(FaceRegisterActivity.this, "Đăng ký khuôn mặt thành công!", Toast.LENGTH_SHORT).show();
                                        finish(); // Chỉ đóng activity khi chắc chắn thành công
                                    });
                                })
                                .addOnFailureListener(e -> runOnUiThread(() -> {
                                    Toast.makeText(this, "Lỗi lưu vector: " + e.getMessage(), Toast.LENGTH_SHORT).show();
                                    showProcessingUI(false);
                                }));
                    } else {
                        runOnUiThread(() -> {
                            Toast.makeText(this, "Không tìm thấy sinh viên với mã: " + studentId, Toast.LENGTH_SHORT).show();
                            showProcessingUI(false);
                        });
                    }
                })
                .addOnFailureListener(e -> runOnUiThread(() -> {
                    Toast.makeText(this, "Lỗi khi truy vấn students: " + e.getMessage(), Toast.LENGTH_SHORT).show();
                    showProcessingUI(false);
                }));
    }

    private Bitmap imageProxyToBitmap(ImageProxy image) {
        // ... (hàm này giữ nguyên không đổi)
        if (image.getFormat() == ImageFormat.JPEG) {
            ByteBuffer buffer = image.getPlanes()[0].getBuffer();
            byte[] bytes = new byte[buffer.remaining()];
            buffer.get(bytes);
            return android.graphics.BitmapFactory.decodeByteArray(bytes, 0, bytes.length);
        } else if (image.getFormat() == ImageFormat.YUV_420_888) {
            ByteBuffer yBuffer = image.getPlanes()[0].getBuffer();
            ByteBuffer uBuffer = image.getPlanes()[1].getBuffer();
            ByteBuffer vBuffer = image.getPlanes()[2].getBuffer();
            int ySize = yBuffer.remaining();
            int uSize = uBuffer.remaining();
            int vSize = vBuffer.remaining();
            byte[] nv21 = new byte[ySize + uSize + vSize];
            yBuffer.get(nv21, 0, ySize);
            vBuffer.get(nv21, ySize, vSize);
            byte[] uBytes = new byte[uSize];
            uBuffer.get(uBytes);
            int uPixelStride = image.getPlanes()[1].getPixelStride();
            if (uPixelStride == 2) {
                for (int i = 0; i < uSize; i += 2) {
                    nv21[ySize + i + 1] = uBytes[i];
                }
            }
            YuvImage yuvImage = new YuvImage(nv21, ImageFormat.NV21, image.getWidth(), image.getHeight(), null);
            ByteArrayOutputStream out = new ByteArrayOutputStream();
            yuvImage.compressToJpeg(new Rect(0, 0, yuvImage.getWidth(), yuvImage.getHeight()), 100, out);
            byte[] imageBytes = out.toByteArray();
            return android.graphics.BitmapFactory.decodeByteArray(imageBytes, 0, imageBytes.length);
        }
        return null;
    }
}