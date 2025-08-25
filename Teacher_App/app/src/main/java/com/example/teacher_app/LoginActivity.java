package com.example.teacher_app;

import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;

import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;

public class LoginActivity extends AppCompatActivity {
    private EditText edtEmail, edtPassword;

    private Button btnLogin;

    private TextView btnphone;

    private FirebaseAuth mAuth;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_login);

        mAuth = FirebaseAuth.getInstance();

        edtEmail = findViewById(R.id.edtEmail);
        edtPassword = findViewById(R.id.edtPassword);
        btnLogin = findViewById(R.id.btnLogin);
        btnphone = findViewById(R.id.btnphone);

        btnLogin.setOnClickListener(v -> loginUser());

        btnphone.setOnClickListener(v -> {
            Intent intent = new Intent(LoginActivity.this, PhoneLoginActivity.class);
            startActivity(intent);
        });
    }

    private void loginUser() {
        String email = edtEmail.getText().toString();
        String password = edtPassword.getText().toString();

        if (TextUtils.isEmpty(email)) {
            edtEmail.setError("Cần nhập Email!");
            edtEmail.requestFocus();
            return;
        }

        if (TextUtils.isEmpty(password)) {
            edtPassword.setError("Cần nhập Password!");
            edtPassword.requestFocus();
            return;
        }

        mAuth.signInWithEmailAndPassword(email, password)
                .addOnCompleteListener(task -> {
                    if (task.isSuccessful()) {
                        // Lấy uid của user đăng nhập
                        String uid = mAuth.getCurrentUser().getUid();

                        // Kiểm tra trong Firestore
                        FirebaseFirestore db = FirebaseFirestore.getInstance();
                        db.collection("users").document(uid).get()
                                .addOnSuccessListener(documentSnapshot -> {
                                    if (documentSnapshot.exists()) {
                                        String teacherId = documentSnapshot.getString("teacher_id");

                                        if (teacherId != null && !teacherId.isEmpty()) {
                                            // ✅ Có teacher_id → cho vào MainActivity
                                            Toast.makeText(LoginActivity.this, "Đăng nhập thành công", Toast.LENGTH_SHORT).show();
                                            Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                                            startActivity(intent);
                                            finish();
                                        } else {
                                            // ❌ Không có teacher_id → chặn
                                            Toast.makeText(LoginActivity.this, "Tài khoản không phải giảng viên!", Toast.LENGTH_LONG).show();
                                            FirebaseAuth.getInstance().signOut();
                                        }
                                    } else {
                                        Toast.makeText(LoginActivity.this, "Tài khoản không tồn tại trong hệ thống!", Toast.LENGTH_LONG).show();
                                        FirebaseAuth.getInstance().signOut();
                                    }
                                })
                                .addOnFailureListener(e -> {
                                    Toast.makeText(LoginActivity.this, "Lỗi kiểm tra quyền đăng nhập!", Toast.LENGTH_SHORT).show();
                                    FirebaseAuth.getInstance().signOut();
                                });
                    } else {
                        Toast.makeText(LoginActivity.this, "Đăng nhập thất bại: Email hoặc mật khẩu sai!", Toast.LENGTH_LONG).show();
                    }
                });
    }
}