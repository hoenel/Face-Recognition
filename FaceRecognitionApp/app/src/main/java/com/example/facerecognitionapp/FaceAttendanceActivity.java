package com.example.facerecognitionapp;

import android.Manifest;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.ImageFormat;
import android.graphics.Matrix;
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
import com.google.firebase.firestore.FirebaseFirestore;

import java.io.ByteArrayOutputStream;
import java.nio.ByteBuffer;
import java.util.List;
import java.util.concurrent.ExecutionException;


public class FaceAttendanceActivity extends AppCompatActivity {

    private static final double SIMILARITY_THRESHOLD = 0.85;

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
        // Tái sử dụng layout của màn hình đăng ký
        setContentView(R.layout.activity_face_register);

        previewView = findViewById(R.id.previewView);
        capturedImage = findViewById(R.id.capturedImage);
        btnCapture = findViewById(R.id.btnCapture);
        btnConfirm = findViewById(R.id.btnConfirm);
        btnRetake = findViewById(R.id.btnRetake);
        progressBar = findViewById(R.id.progressBar);

        db = FirebaseFirestore.getInstance();

        if (ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA) == PackageManager.PERMISSION_GRANTED) {
            startCamera();
        } else {
            ActivityCompat.requestPermissions(this, new String[]{Manifest.permission.CAMERA}, 123);
        }

        setupButtonClickListeners();
    }

    private void setupButtonClickListeners() {
        btnCapture.setOnClickListener(v -> takePhoto());
        btnRetake.setOnClickListener(v -> resetToPreviewState());
        btnConfirm.setOnClickListener(v -> {
            if (capturedBitmap != null) {
                showProcessingUI(true);
                new Thread(this::processAndCompareImage).start();
            }
        });
    }

    private void processAndCompareImage() {
        if (capturedBitmap == null) return;

        Bitmap face = FaceAnalyzer.detectFace(capturedBitmap, this);
        if (face == null) {
            runOnUiThread(() -> {
                Toast.makeText(this, "Không tìm thấy khuôn mặt! Vui lòng chụp lại.", Toast.LENGTH_LONG).show();
                resetToPreviewState();
                showProcessingUI(false);
            });
            return;
        }

        float[] currentEmbedding = FaceEmbeddingHelper.getFaceEmbedding(face, this);
        if (currentEmbedding == null) {
            runOnUiThread(() -> {
                Toast.makeText(this, "Lỗi sinh vector!", Toast.LENGTH_SHORT).show();
                showProcessingUI(false);
            });
            return;
        }

        fetchStoredEmbeddingAndCompare(currentEmbedding);
    }

    private void fetchStoredEmbeddingAndCompare(float[] currentEmbedding) {
        FirebaseUser currentUser = FirebaseAuth.getInstance().getCurrentUser();
        if (currentUser == null) {
            runOnUiThread(() -> Toast.makeText(this, "Bạn chưa đăng nhập!", Toast.LENGTH_SHORT).show());
            return;
        }

        String uid = currentUser.getUid();
        db.collection("users").document(uid).get()
                .addOnSuccessListener(userDoc -> {
                    if (userDoc.exists()) {
                        String studentId = userDoc.getString("student_id");
                        if (studentId != null && !studentId.isEmpty()) {
                            db.collection("students").document(studentId).get()
                                    .addOnSuccessListener(studentDoc -> {
                                        if (studentDoc.exists()) {
                                            // Firestore lưu danh sách số thực dưới dạng List<Double>
                                            List<Double> storedEmbeddingList = (List<Double>) studentDoc.get("vector_embedding");
                                            if (storedEmbeddingList != null && !storedEmbeddingList.isEmpty()) {
                                                compareEmbeddings(currentEmbedding, storedEmbeddingList);
                                            } else {
                                                runOnUiThread(() -> {
                                                    Toast.makeText(this, "Chưa đăng ký khuôn mặt!", Toast.LENGTH_SHORT).show();
                                                    finish();
                                                });
                                            }
                                        }
                                    })
                                    .addOnFailureListener(e -> runOnUiThread(() -> Toast.makeText(this, "Lỗi lấy dữ liệu sinh viên.", Toast.LENGTH_SHORT).show()));
                        }
                    }
                })
                .addOnFailureListener(e -> runOnUiThread(() -> Toast.makeText(this, "Lỗi lấy dữ liệu người dùng.", Toast.LENGTH_SHORT).show()));
    }

    private void compareEmbeddings(float[] currentEmbedding, List<Double> storedEmbeddingList) {
        float[] storedEmbedding = new float[storedEmbeddingList.size()];
        for (int i = 0; i < storedEmbeddingList.size(); i++) {
            storedEmbedding[i] = storedEmbeddingList.get(i).floatValue();
        }

        double similarity = calculateCosineSimilarity(currentEmbedding, storedEmbedding);

        runOnUiThread(() -> {
            if (similarity > SIMILARITY_THRESHOLD) {
                Toast.makeText(this, "Điểm danh thành công!", Toast.LENGTH_LONG).show();
            } else {
                Toast.makeText(this, "Ảnh không khớp! Vui lòng thử lại. Độ tương đồng: " + String.format("%.2f", similarity), Toast.LENGTH_LONG).show();
            }
            finish(); // Đóng activity sau khi có kết quả
        });
    }

    /**
     * Tính toán độ tương đồng Cosine giữa hai vector.
     * Kết quả trả về trong khoảng [-1, 1]. Càng gần 1 càng giống nhau.
     */
    private double calculateCosineSimilarity(float[] vectorA, float[] vectorB) {
        if (vectorA == null || vectorB == null || vectorA.length != vectorB.length) {
            return 0.0;
        }

        double dotProduct = 0.0;
        double normA = 0.0;
        double normB = 0.0;
        for (int i = 0; i < vectorA.length; i++) {
            dotProduct += vectorA[i] * vectorB[i];
            normA += Math.pow(vectorA[i], 2);
            normB += Math.pow(vectorB[i], 2);
        }

        if (normA == 0 || normB == 0) {
            return 0.0;
        }

        return dotProduct / (Math.sqrt(normA) * Math.sqrt(normB));
    }


    private void showProcessingUI(boolean isProcessing) {
        runOnUiThread(() -> {
            progressBar.setVisibility(isProcessing ? View.VISIBLE : View.GONE);
            btnConfirm.setVisibility(isProcessing ? View.GONE : View.VISIBLE);
            btnRetake.setVisibility(isProcessing ? View.GONE : View.VISIBLE);
        });
    }

    private void resetToPreviewState() {
        previewView.setVisibility(View.VISIBLE);
        capturedImage.setVisibility(View.GONE);
        btnCapture.setVisibility(View.VISIBLE);
        btnConfirm.setVisibility(View.GONE);
        btnRetake.setVisibility(View.GONE);
        capturedBitmap = null;
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
                    Toast.makeText(FaceAttendanceActivity.this, "Lỗi lấy ảnh", Toast.LENGTH_SHORT).show();
                    return;
                }
                Bitmap rotatedBitmap = rotateBitmap(unrotatedBitmap, rotationDegrees);
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

    private Bitmap flipBitmapHorizontal(Bitmap source) {
        Matrix matrix = new Matrix();
        matrix.preScale(-1.0f, 1.0f);
        return Bitmap.createBitmap(source, 0, 0, source.getWidth(), source.getHeight(), matrix, true);
    }

    private Bitmap imageProxyToBitmap(ImageProxy image) {
        if (image.getFormat() == ImageFormat.YUV_420_888) {
            ByteBuffer yBuffer = image.getPlanes()[0].getBuffer();
            ByteBuffer uBuffer = image.getPlanes()[1].getBuffer();
            ByteBuffer vBuffer = image.getPlanes()[2].getBuffer();
            int ySize = yBuffer.remaining();
            int uSize = uBuffer.remaining();
            int vSize = vBuffer.remaining();
            byte[] nv21 = new byte[ySize + uSize + vSize];
            yBuffer.get(nv21, 0, ySize);
            vBuffer.get(nv21, ySize, vSize);
            uBuffer.get(nv21, ySize + vSize, uSize);
            YuvImage yuvImage = new YuvImage(nv21, ImageFormat.NV21, image.getWidth(), image.getHeight(), null);
            ByteArrayOutputStream out = new ByteArrayOutputStream();
            yuvImage.compressToJpeg(new Rect(0, 0, yuvImage.getWidth(), yuvImage.getHeight()), 100, out);
            byte[] imageBytes = out.toByteArray();
            return android.graphics.BitmapFactory.decodeByteArray(imageBytes, 0, imageBytes.length);
        } else if (image.getFormat() == ImageFormat.JPEG) {
            ByteBuffer buffer = image.getPlanes()[0].getBuffer();
            byte[] bytes = new byte[buffer.remaining()];
            buffer.get(bytes);
            return android.graphics.BitmapFactory.decodeByteArray(bytes, 0, bytes.length);
        }
        return null;
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == 123) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                startCamera();
            } else {
                Toast.makeText(this, "Bạn cần cấp quyền camera để điểm danh", Toast.LENGTH_LONG).show();
                finish();
            }
        }
    }
}