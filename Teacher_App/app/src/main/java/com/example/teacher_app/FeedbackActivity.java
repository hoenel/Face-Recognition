package com.example.teacher_app;

import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;

import com.example.teacher_app.Models.Feedback;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.firestore.FirebaseFirestore;

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

    private FirebaseFirestore db;

    private DatabaseReference databaseRootRef;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_feedback);

        icBack = findViewById(R.id.icBack);
        edtFeedback = findViewById(R.id.edtFeedback);
        btnSendFeedback = findViewById(R.id.btnSendFeedback);

        databaseRootRef = FirebaseDatabase.getInstance("https://facerecognitionapp-f034d-default-rtdb.asia-southeast1.firebasedatabase.app").getReference();

        db = FirebaseFirestore.getInstance();

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
        String messageText = edtFeedback.getText().toString().trim();

        if (messageText.isEmpty()) {
            Toast.makeText(this, "Vui lòng nhập nội dung thông báo!", Toast.LENGTH_SHORT).show();
            return;
        }

        FirebaseUser currentUser = FirebaseAuth.getInstance().getCurrentUser();

        String uid = currentUser.getUid();

        db.collection("users").document(uid).get()
                .addOnSuccessListener(documentSnapshot -> {
                    if (documentSnapshot.exists()) {

                        String teacherName = documentSnapshot.getString("name");

                        if (teacherName == null || teacherName.isEmpty()) {
                            teacherName = "Thông báo";
                        }

                        proceedToSendNotification(teacherName, messageText);

                    } else {

                        Toast.makeText(this, "Không tìm thấy thông tin người dùng!", Toast.LENGTH_SHORT).show();
                    }
                })
                .addOnFailureListener(e -> {
                    Toast.makeText(this, "Lỗi khi lấy thông tin người dùng!", Toast.LENGTH_SHORT).show();
                    Log.e("FeedbackActivity", "Error getting user data from Firestore", e);
                });
    }


    private void proceedToSendNotification(String teacherName, String messageText) {
        String key = databaseRootRef.child("Notifications").push().getKey();
        if (key == null) {
            Toast.makeText(this, "Lỗi khi tạo key!", Toast.LENGTH_SHORT).show();
            return;
        }

        String timestamp = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.getDefault()).format(new Date());
        Feedback notification = new Feedback(teacherName, messageText, timestamp);

        Map<String, Object> childUpdates = new HashMap<>();
        childUpdates.put("/Feedbacks/" + key, notification);
        childUpdates.put("/Notifications/" + key, notification);

        databaseRootRef.updateChildren(childUpdates).addOnCompleteListener(task -> {
            if (task.isSuccessful()) {
                Toast.makeText(FeedbackActivity.this, "Gửi thông báo thành công!", Toast.LENGTH_SHORT).show();
                edtFeedback.setText("");
            } else {
                Toast.makeText(FeedbackActivity.this, "Lỗi khi gửi thông báo!", Toast.LENGTH_SHORT).show();
            }
        });
    }
}