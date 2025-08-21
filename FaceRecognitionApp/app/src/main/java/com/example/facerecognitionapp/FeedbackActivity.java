package com.example.facerecognitionapp;

import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.example.facerecognitionapp.Models.Feedback;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Locale;
import java.util.Map;

public class FeedbackActivity extends AppCompatActivity {
    private ImageView icBack;
    private EditText edtFeedback;

    private Button btnSendFeedback;

    private DatabaseReference feedbackRef;

    private FirebaseAuth auth;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_feedback);

        icBack = findViewById(R.id.icBack);
        edtFeedback = findViewById(R.id.edtFeedback);
        btnSendFeedback = findViewById(R.id.btnSendFeedback);

        feedbackRef = FirebaseDatabase.getInstance("https://facerecognitionapp-f034d-default-rtdb.asia-southeast1.firebasedatabase.app").getReference("Feedbacks");

        icBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                onBackPressed();
            }
        });

        btnSendFeedback.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                sendFeedback();
            }
    });
}

    private void sendFeedback() {
        String feedbackText = edtFeedback.getText().toString().trim();

        if (feedbackText.isEmpty()) {
            Toast.makeText(this, "Vui lòng nhập góp ý!", Toast.LENGTH_SHORT).show();
            return;
        }

        FirebaseUser user = FirebaseAuth.getInstance().getCurrentUser();

        String studentId = user.getUid();
        String timestamp = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault()).format(new Date());

        Feedback feedback = new Feedback(feedbackText, timestamp);

        feedbackRef.child(studentId).push().setValue(feedback)
                .addOnCompleteListener(task -> {
                    if (task.isSuccessful()) {
                        Toast.makeText(FeedbackActivity.this, "Gửi góp ý thành công!", Toast.LENGTH_SHORT).show();
                        edtFeedback.setText("");
                    } else {
                        Toast.makeText(FeedbackActivity.this, "Lỗi khi gửi góp ý!", Toast.LENGTH_SHORT).show();
                    }
                });
    }
}