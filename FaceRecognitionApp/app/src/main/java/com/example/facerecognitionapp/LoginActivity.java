package com.example.facerecognitionapp;

import android.content.Intent;
import android.os.Bundle;
import android.text.TextUtils;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.firestore.DocumentReference;
import com.google.firebase.firestore.FirebaseFirestore;

public class LoginActivity extends AppCompatActivity {
    private EditText edtEmail, edtPassword;

    private Button btnLogin;

    private TextView btnphone;

    private FirebaseAuth mAuth;

    private FirebaseFirestore db;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        EdgeToEdge.enable(this);
        setContentView(R.layout.activity_login);

        mAuth = FirebaseAuth.getInstance();
        db = FirebaseFirestore.getInstance();

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
                        FirebaseUser user = mAuth.getCurrentUser();
                        if (user != null) {
                            checkIfStudent(user.getUid());
                        }
                    } else {
                        Toast.makeText(LoginActivity.this, "Đăng nhập thất bại: Email hoặc mật khẩu sai!", Toast.LENGTH_LONG).show();
                    }
                });
    }

    private void checkIfStudent(String uid) {
        DocumentReference userRef = db.collection("users").document(uid);
        userRef.get().addOnCompleteListener(task -> {
            if (task.isSuccessful()) {
                if (task.getResult() != null && task.getResult().exists()) {
                    if (task.getResult().contains("student_id")) {
                        Toast.makeText(LoginActivity.this, "Đăng nhập thành công", Toast.LENGTH_SHORT).show();
                        Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                        startActivity(intent);
                        finish();
                    } else {

                        mAuth.signOut();
                        Toast.makeText(LoginActivity.this, "Đăng nhập thất bại: Tài khoản không hợp lệ!", Toast.LENGTH_LONG).show();
                    }
                } else {
                    mAuth.signOut();
                    Toast.makeText(LoginActivity.this, "Đăng nhập thất bại: Tài khoản không tồn tại!", Toast.LENGTH_LONG).show();
                }
            } else {
                mAuth.signOut();
                Toast.makeText(LoginActivity.this, "Lỗi: " + task.getException().getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }
}