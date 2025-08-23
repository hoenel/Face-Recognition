package com.example.facerecognitionapp;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.os.Bundle;
import android.widget.Button;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.camera.core.CameraSelector;
import androidx.camera.core.ImageCapture;
import androidx.camera.core.ImageCaptureException;
import androidx.camera.core.ImageProxy;
import androidx.camera.lifecycle.ProcessCameraProvider;
import androidx.camera.view.PreviewView;
import androidx.core.content.ContextCompat;

import com.google.common.util.concurrent.ListenableFuture;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;

import java.nio.ByteBuffer;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.ExecutionException;

public class FaceRegisterActivity extends AppCompatActivity {

    private PreviewView previewView;
    private Button btnCapture;
    private ImageCapture imageCapture;
    private FirebaseFirestore db;
    private String studentId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_face_register);

        previewView = findViewById(R.id.previewView);
        btnCapture = findViewById(R.id.btnCapture);

        db = FirebaseFirestore.getInstance();
        studentId = FirebaseAuth.getInstance().getCurrentUser().getUid();

        startCamera();

        btnCapture.setOnClickListener(v -> takePhoto());
    }

    private void startCamera() {
        ListenableFuture<ProcessCameraProvider> cameraProviderFuture =
                ProcessCameraProvider.getInstance(this);

        cameraProviderFuture.addListener(() -> {
            try {
                ProcessCameraProvider cameraProvider = cameraProviderFuture.get();

                imageCapture = new ImageCapture.Builder().build();

                CameraSelector cameraSelector = new CameraSelector.Builder()
                        .requireLensFacing(CameraSelector.LENS_FACING_FRONT)
                        .build();

                cameraProvider.unbindAll();
                cameraProvider.bindToLifecycle(this, cameraSelector, imageCapture);

            } catch (ExecutionException | InterruptedException e) {
                e.printStackTrace();
            }
        }, ContextCompat.getMainExecutor(this));
    }

    private void takePhoto() {
        imageCapture.takePicture(ContextCompat.getMainExecutor(this),
                new ImageCapture.OnImageCapturedCallback() {
                    @Override
                    public void onCaptureSuccess(@NonNull ImageProxy image) {
                        Bitmap bitmap = toBitmap(image);
                        image.close();

                        // Detect face & crop
                        Bitmap face = FaceAnalyzer.detectFace(bitmap, FaceRegisterActivity.this);
                        if (face == null) {
                            runOnUiThread(() ->
                                    Toast.makeText(FaceRegisterActivity.this, "Không tìm thấy khuôn mặt!", Toast.LENGTH_SHORT).show());
                            return;
                        }

                        // Generate embedding
                        float[] embedding = FaceEmbeddingHelper.getFaceEmbedding(face, FaceRegisterActivity.this);

                        // Save to Firestore
                        saveEmbeddingToFirestore(embedding);

                    }

                    @Override
                    public void onError(@NonNull ImageCaptureException exception) {
                        super.onError(exception);
                    }
                });
    }

    private void saveEmbeddingToFirestore(float[] embedding) {
        String uid = FirebaseAuth.getInstance().getCurrentUser().getUid();

        // Bước 1: Lấy student_id từ collection users
        db.collection("users").document(uid).get()
                .addOnSuccessListener(documentSnapshot -> {
                    if (documentSnapshot.exists()) {
                        String studentId = documentSnapshot.getString("student_id");

                        if (studentId != null && !studentId.isEmpty()) {
                            // Bước 2: Update vector vào students collection
                            db.collection("students").document(studentId)
                                    .update("vector_embedding", embedding)
                                    .addOnSuccessListener(unused -> runOnUiThread(() ->
                                            Toast.makeText(FaceRegisterActivity.this, "Đăng ký khuôn mặt thành công!", Toast.LENGTH_SHORT).show()))
                                    .addOnFailureListener(e -> runOnUiThread(() ->
                                            Toast.makeText(FaceRegisterActivity.this, "Lỗi khi lưu vector!", Toast.LENGTH_SHORT).show()));
                        } else {
                            Toast.makeText(this, "Không tìm thấy student_id!", Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        Toast.makeText(this, "Không tìm thấy user!", Toast.LENGTH_SHORT).show();
                    }
                })
                .addOnFailureListener(e -> Toast.makeText(this, "Lỗi khi lấy user!", Toast.LENGTH_SHORT).show());
    }


    private Bitmap toBitmap(ImageProxy image) {
        ByteBuffer buffer = image.getPlanes()[0].getBuffer();
        byte[] bytes = new byte[buffer.remaining()];
        buffer.get(bytes);
        return BitmapFactory.decodeByteArray(bytes, 0, bytes.length, null);
    }
}
