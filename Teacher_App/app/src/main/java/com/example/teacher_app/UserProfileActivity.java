package com.example.teacher_app;

import android.os.Bundle;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.firestore.FirebaseFirestore;

public class UserProfileActivity extends AppCompatActivity {
    private TextView tvFullName, tvBirth, tvAddress, tvClass, tvMajor;
    private FirebaseFirestore db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_profile);

        tvFullName = findViewById(R.id.tvFullName);
        tvBirth = findViewById(R.id.tvBirth);
        tvAddress = findViewById(R.id.tvAddress);
        tvClass = findViewById(R.id.tvClass);
        tvMajor = findViewById(R.id.tvMajor);

        db = FirebaseFirestore.getInstance();

        FirebaseUser currentUser = FirebaseAuth.getInstance().getCurrentUser();
        if (currentUser != null) {
            loadUserData(currentUser.getUid());
        }

        ImageView btnBack = findViewById(R.id.btnBack);
        btnBack.setOnClickListener(v -> onBackPressed());
    }

    private void loadUserData(String uid) {
        db.collection("users").document(uid).get()
                .addOnSuccessListener(documentSnapshot -> {
                    if (documentSnapshot.exists()) {
                        String studentId = documentSnapshot.getString("student_id");
                        if (studentId != null) {
                            loadStudentData(studentId);
                        }
                    }
                })
                .addOnFailureListener(e -> {
                    Toast.makeText(this, "Lỗi khi lấy dữ liệu user!", Toast.LENGTH_SHORT).show();
                });
    }

    private void loadStudentData(String studentId) {
        db.collection("students").document(studentId).get()
                .addOnSuccessListener(documentSnapshot -> {
                    if (documentSnapshot.exists()) {
                        String name = documentSnapshot.getString("name");
                        String dob = documentSnapshot.getString("date_of_birth");
                        String classId = documentSnapshot.getString("class_id");
                        String fieldOfStudy = documentSnapshot.getString("field_of_study");
                        String address = documentSnapshot.getString("hometown");

                        tvFullName.setText("Họ và tên: " + name);
                        tvBirth.setText("Ngày sinh: " + dob);
                        tvClass.setText("Lớp: " + classId);
                        tvMajor.setText("Ngành: " + fieldOfStudy);
                        tvAddress.setText("Quê quán: " + address);
                    }
                })
                .addOnFailureListener(e -> {
                    Toast.makeText(this, "Lỗi khi lấy dữ liệu sinh viên!", Toast.LENGTH_SHORT).show();
                });
    }
}
