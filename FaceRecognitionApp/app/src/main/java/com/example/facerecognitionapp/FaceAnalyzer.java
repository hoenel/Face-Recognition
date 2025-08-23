package com.example.facerecognitionapp;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.Rect;

import com.google.mlkit.vision.common.InputImage;
import com.google.mlkit.vision.face.Face;
import com.google.mlkit.vision.face.FaceDetection;
import com.google.mlkit.vision.face.FaceDetectorOptions;

import java.util.List;
import java.util.concurrent.CountDownLatch;

public class FaceAnalyzer {

    public static Bitmap detectFace(Bitmap bitmap, Context context) {
        final Bitmap[] croppedFace = {null};
        CountDownLatch latch = new CountDownLatch(1);

        FaceDetectorOptions options =
                new FaceDetectorOptions.Builder()
                        .setPerformanceMode(FaceDetectorOptions.PERFORMANCE_MODE_ACCURATE)
                        .build();

        com.google.mlkit.vision.face.FaceDetector detector =
                FaceDetection.getClient(options);

        InputImage image = InputImage.fromBitmap(bitmap, 0);

        detector.process(image)
                .addOnSuccessListener(faces -> {
                    if (!faces.isEmpty()) {
                        Face face = faces.get(0);
                        Rect box = face.getBoundingBox();

                        croppedFace[0] = Bitmap.createBitmap(bitmap,
                                box.left,
                                box.top,
                                box.width(),
                                box.height());
                    }
                    latch.countDown();
                })
                .addOnFailureListener(e -> latch.countDown());

        try {
            latch.await();
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        return croppedFace[0];
    }
}
