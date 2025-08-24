package com.example.facerecognitionapp;

import android.content.Context;
import android.content.res.AssetFileDescriptor;
import android.graphics.Bitmap;

import org.tensorflow.lite.Interpreter;

import java.io.FileInputStream;
import java.io.IOException;
import java.nio.MappedByteBuffer;
import java.nio.channels.FileChannel;

public class FaceEmbeddingHelper {

    private static Interpreter interpreter;

    private static MappedByteBuffer loadModelFile(Context context, String modelPath) throws IOException {
        AssetFileDescriptor fileDescriptor = context.getAssets().openFd(modelPath);
        FileInputStream inputStream = new FileInputStream(fileDescriptor.getFileDescriptor());
        FileChannel fileChannel = inputStream.getChannel();
        long startOffset = fileDescriptor.getStartOffset();
        long declaredLength = fileDescriptor.getDeclaredLength();
        return fileChannel.map(FileChannel.MapMode.READ_ONLY, startOffset, declaredLength);
    }

    private static void init(Context context) {
        if (interpreter == null) {
            try {
                Interpreter.Options options = new Interpreter.Options();
                options.setNumThreads(4);
                interpreter = new Interpreter(loadModelFile(context, "facenet.tflite"), options);
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }

    /**
     * Assumes input face bitmap already cropped and reasonably front-facing.
     * Returns float[128] embedding (may vary depending on model).
     */
    public static float[] getFaceEmbedding(Bitmap face, Context context) {
        init(context);

        Bitmap resized = Bitmap.createScaledBitmap(face, 160, 160, true);
        // normalize to [0,1]
        float[][][][] input = new float[1][160][160][3];
        for (int y = 0; y < 160; y++) {
            for (int x = 0; x < 160; x++) {
                int pixel = resized.getPixel(x, y);
                input[0][y][x][0] = (((pixel >> 16) & 0xFF) / 255.0f);
                input[0][y][x][1] = (((pixel >> 8) & 0xFF) / 255.0f);
                input[0][y][x][2] = ((pixel & 0xFF) / 255.0f);
            }
        }

        float[][] embedding = new float[1][512];
        interpreter.run(input, embedding);

        return embedding[0];
    }
}
