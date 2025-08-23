package com.example.facerecognitionapp;

import android.Manifest;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.ImageFormat;
import android.graphics.Rect;
import android.graphics.YuvImage;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.camera.core.CameraSelector;
import androidx.camera.core.ImageCapture;
import androidx.camera.core.ImageCaptureException;
import androidx.camera.core.ImageProxy;
import androidx.camera.core.Preview;
import androidx.camera.lifecycle.ProcessCameraProvider;
import androidx.camera.view.PreviewView;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.google.common.util.concurrent.ListenableFuture;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;

import java.io.ByteArrayOutputStream;
import java.nio.ByteBuffer;
import java.util.ArrayList;
import java.util.List;
import java.util.concurrent.ExecutionException;

public class FaceRegisterActivity extends AppCompatActivity {
    private static final int CAMERA_REQUEST_CODE = 123;

    private PreviewView previewView;
    private Button btnCapture;
    private ImageCapture imageCapture;
    private FirebaseFirestore db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_face_register);

        previewView = findViewById(R.id.previewView);
        btnCapture = findViewById(R.id.btnCapture);

        db = FirebaseFirestore.getInstance();

        if (checkCameraPermission()) {
            startCamera();
        } else {
            requestCameraPermission();
        }

        btnCapture.setOnClickListener(v -> {
            if (imageCapture != null) {
                takePhoto();
            } else {
                Toast.makeText(this, "Camera chưa sẵn sàng", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private boolean checkCameraPermission() {
        return ContextCompat.checkSelfPermission(this, Manifest.permission.CAMERA) == PackageManager.PERMISSION_GRANTED;
    }

    private void requestCameraPermission() {
        ActivityCompat.requestPermissions(this, new String[] { Manifest.permission.CAMERA }, CAMERA_REQUEST_CODE);
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions,
                                           @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == CAMERA_REQUEST_CODE) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                startCamera();
            } else {
                Toast.makeText(this, "Bạn cần cấp quyền camera để đăng ký khuôn mặt", Toast.LENGTH_LONG).show();
            }
        }
    }

    private void startCamera() {
        ListenableFuture<ProcessCameraProvider> cameraProviderFuture =
                ProcessCameraProvider.getInstance(this);

        cameraProviderFuture.addListener(() -> {
            try {
                ProcessCameraProvider cameraProvider = cameraProviderFuture.get();

                // Preview
                androidx.camera.core.Preview preview = new androidx.camera.core.Preview.Builder()
                        .build();

                preview.setSurfaceProvider(previewView.getSurfaceProvider());

                // Capture
                imageCapture = new ImageCapture.Builder()
                        .setCaptureMode(ImageCapture.CAPTURE_MODE_MINIMIZE_LATENCY)
                        .build();

                CameraSelector cameraSelector = new CameraSelector.Builder()
                        .requireLensFacing(CameraSelector.LENS_FACING_FRONT)
                        .build();

                // Bind cả preview + capture
                cameraProvider.unbindAll();
                cameraProvider.bindToLifecycle(this, cameraSelector, preview, imageCapture);

            } catch (ExecutionException | InterruptedException e) {
                e.printStackTrace();
            }
        }, ContextCompat.getMainExecutor(this));
    }


    private void takePhoto() {
        if (imageCapture == null) return;

        imageCapture.takePicture(ContextCompat.getMainExecutor(this),
                new ImageCapture.OnImageCapturedCallback() {
                    @Override
                    public void onCaptureSuccess(@NonNull ImageProxy image) {
                        Bitmap bitmap = imageProxyToBitmap(image);
                        image.close();

                        if (bitmap == null) {
                            runOnUiThread(() -> Toast.makeText(FaceRegisterActivity.this, "Lỗi lấy ảnh", Toast.LENGTH_SHORT).show());
                            return;
                        }

                        // Detect face (ML Kit) and crop
                        Bitmap face = FaceAnalyzer.detectFace(bitmap, FaceRegisterActivity.this);
                        if (face == null) {
                            runOnUiThread(() -> Toast.makeText(FaceRegisterActivity.this, "Không tìm thấy khuôn mặt!", Toast.LENGTH_SHORT).show());
                            return;
                        }

                        // Get embedding (tflite)
                        float[] embedding = FaceEmbeddingHelper.getFaceEmbedding(face, FaceRegisterActivity.this);
                        if (embedding == null) {
                            runOnUiThread(() -> Toast.makeText(FaceRegisterActivity.this, "Lỗi sinh vector!", Toast.LENGTH_SHORT).show());
                            return;
                        }

                        // Save to Firestore
                        saveEmbeddingToFirestore(embedding);
                    }

                    @Override
                    public void onError(@NonNull ImageCaptureException exception) {
                        super.onError(exception);
                        exception.printStackTrace();
                        runOnUiThread(() -> Toast.makeText(FaceRegisterActivity.this, "Lỗi chụp ảnh: " + exception.getMessage(), Toast.LENGTH_SHORT).show());
                    }
                });
    }

    private void saveEmbeddingToFirestore(float[] embedding) {
        if (embedding == null) return;

        List<Float> list = new ArrayList<>();
        for (float f : embedding) list.add(f);

        // Get current uid
        if (FirebaseAuth.getInstance().getCurrentUser() == null) {
            runOnUiThread(() -> Toast.makeText(this, "Bạn chưa đăng nhập!", Toast.LENGTH_SHORT).show());
            return;
        }
        String uid = FirebaseAuth.getInstance().getCurrentUser().getUid();

        db.collection("users").document(uid).get()
                .addOnSuccessListener(doc -> {
                    if (doc.exists()) {
                        String studentId = doc.getString("student_id");
                        if (studentId != null && !studentId.isEmpty()) {
                            db.collection("students").document(studentId)
                                    .update("vector_embedding", list)
                                    .addOnSuccessListener(aVoid -> runOnUiThread(() -> Toast.makeText(FaceRegisterActivity.this, "Đăng ký khuôn mặt thành công!", Toast.LENGTH_SHORT).show()))
                                    .addOnFailureListener(e -> runOnUiThread(() -> Toast.makeText(FaceRegisterActivity.this, "Lỗi lưu vector: " + e.getMessage(), Toast.LENGTH_SHORT).show()));
                        } else {
                            runOnUiThread(() -> Toast.makeText(this, "Không tìm thấy student_id ở users!", Toast.LENGTH_SHORT).show());
                        }
                    } else {
                        runOnUiThread(() -> Toast.makeText(this, "Document user không tồn tại!", Toast.LENGTH_SHORT).show());
                    }
                })
                .addOnFailureListener(e -> runOnUiThread(() -> Toast.makeText(this, "Lỗi lấy users: " + e.getMessage(), Toast.LENGTH_SHORT).show()));
    }

    /**
     * Convert ImageProxy (YUV_420_888) to Bitmap using NV21 -> JPEG conversion.
     */
    private Bitmap imageProxyToBitmap(ImageProxy image) {
        try {
            ImageProxy.PlaneProxy[] planes = image.getPlanes();
            ByteBuffer yBuffer = planes[0].getBuffer();
            ByteBuffer uBuffer = planes[1].getBuffer();
            ByteBuffer vBuffer = planes[2].getBuffer();

            int ySize = yBuffer.remaining();
            int uSize = uBuffer.remaining();
            int vSize = vBuffer.remaining();

            byte[] nv21 = new byte[ySize + uSize + vSize];

            // copy Y
            yBuffer.get(nv21, 0, ySize);

            // V and U are swapped
            vBuffer.get(nv21, ySize, vSize);
            uBuffer.get(nv21, ySize + vSize, uSize);

            YuvImage yuvImage = new YuvImage(nv21, ImageFormat.NV21, image.getWidth(), image.getHeight(), null);
            ByteArrayOutputStream out = new ByteArrayOutputStream();
            yuvImage.compressToJpeg(new Rect(0, 0, image.getWidth(), image.getHeight()), 100, out);
            byte[] jpegBytes = out.toByteArray();
            return android.graphics.BitmapFactory.decodeByteArray(jpegBytes, 0, jpegBytes.length);
        } catch (Exception e) {
            e.printStackTrace();
            return null;
        }
    }
}
