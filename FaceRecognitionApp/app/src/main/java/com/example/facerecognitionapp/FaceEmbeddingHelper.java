package com.example.facerecognitionapp;

import android.content.Context;
import android.graphics.Bitmap;

import org.tensorflow.lite.Interpreter;

import java.io.FileInputStream;
import java.io.IOException;
import java.nio.MappedByteBuffer;
import java.nio.channels.FileChannel;

public class FaceEmbeddingHelper {

    private static Interpreter interpreter;

    private static MappedByteBuffer loadModelFile(Context context, String modelPath) throws IOException {
        FileInputStream inputStream = new FileInputStream(context.getAssets().openFd(modelPath).getFileDescriptor());
        FileChannel fileChannel = inputStream.getChannel();
        long startOffset = context.getAssets().openFd(modelPath).getStartOffset();
        long declaredLength = context.getAssets().openFd(modelPath).getDeclaredLength();
        return fileChannel.map(FileChannel.MapMode.READ_ONLY, startOffset, declaredLength);
    }

    private static void init(Context context) {
        if (interpreter == null) {
            try {
                interpreter = new Interpreter(loadModelFile(context, "facenet.tflite"));
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

    public static float[] getFaceEmbedding(Bitmap face, Context context) {
        init(context);

        Bitmap resized = Bitmap.createScaledBitmap(face, 160, 160, true);
        float[][][][] input = new float[1][160][160][3];
        for (int y = 0; y < 160; y++) {
            for (int x = 0; x < 160; x++) {
                int pixel = resized.getPixel(x, y);
                input[0][y][x][0] = ((pixel >> 16) & 0xFF) / 255.0f;
                input[0][y][x][1] = ((pixel >> 8) & 0xFF) / 255.0f;
                input[0][y][x][2] = (pixel & 0xFF) / 255.0f;
            }
        }

        float[][] embedding = new float[1][128];
        interpreter.run(input, embedding);

        return embedding[0];
    }
}
