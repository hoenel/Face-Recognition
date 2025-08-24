package com.example.facerecognitionapp;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Rect;

import com.google.mlkit.vision.common.InputImage;
import com.google.mlkit.vision.face.Face;
import com.google.mlkit.vision.face.FaceDetection;
import com.google.mlkit.vision.face.FaceDetector;
import com.google.mlkit.vision.face.FaceDetectorOptions;

import java.util.concurrent.CountDownLatch;

public class FaceAnalyzer {

    /**
     * Detect face and crop. Returns cropped face Bitmap or null if not found.
     */
    public static Bitmap detectFace(Bitmap bitmap, Context context) {
        final Bitmap[] croppedFace = {null};
        CountDownLatch latch = new CountDownLatch(1);

        FaceDetectorOptions options =
                new FaceDetectorOptions.Builder()
                        .setPerformanceMode(FaceDetectorOptions.PERFORMANCE_MODE_ACCURATE)
                        .setLandmarkMode(FaceDetectorOptions.LANDMARK_MODE_NONE)
                        .setClassificationMode(FaceDetectorOptions.CLASSIFICATION_MODE_NONE)
                        .build();

        FaceDetector detector = FaceDetection.getClient(options);

        InputImage image = InputImage.fromBitmap(bitmap, 0);

        detector.process(image)
                .addOnSuccessListener(faces -> {
                    if (!faces.isEmpty()) {
                        Face face = faces.get(0);
                        Rect box = face.getBoundingBox();

                        // ensure inside bounds
                        int left = Math.max(0, box.left);
                        int top = Math.max(0, box.top);
                        int right = Math.min(bitmap.getWidth(), box.right);
                        int bottom = Math.min(bitmap.getHeight(), box.bottom);

                        if (right > left && bottom > top) {
                            try {
                                croppedFace[0] = Bitmap.createBitmap(bitmap,
                                        left,
                                        top,
                                        right - left,
                                        bottom - top);
                            } catch (Exception e) {
                                e.printStackTrace();
                            }
                        }
                    }
                    latch.countDown();
                })
                .addOnFailureListener(e -> {
                    e.printStackTrace();
                    latch.countDown();
                })
                .addOnCompleteListener(task -> {
                    // close detector to free resources
                    detector.close();
                });

        try {
            latch.await(); // wait for ML Kit
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        return croppedFace[0];
    }
}
