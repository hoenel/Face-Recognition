package com.example.teacher_app;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.teacher_app.Adapters.AbsentAdapter;
import com.example.teacher_app.Models.Absent;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.ValueEventListener;

import java.util.ArrayList;
import java.util.List;

public class AbsentCheckActivity extends AppCompatActivity {
    private RecyclerView recyclerView;
    private AbsentAdapter adapter;
    private List<Absent> absentList;

    private DatabaseReference ref;

    Button btn_refresh, btn_back;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_absent_check);

        recyclerView = findViewById(R.id.recyclerViewAbsent);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        absentList = new ArrayList<>();

        adapter = new AbsentAdapter(this, absentList);
        recyclerView.setAdapter(adapter);

        btn_refresh = findViewById(R.id.btnRefresh);
        btn_back = findViewById(R.id.btn_back);

        //Ghi dữ liệu ảo lên firebase
        ref = FirebaseDatabase.getInstance().getReference().child("Absents");
        loadDataFromFirebase();

        btn_refresh.setOnClickListener(view -> {
            loadDataFromFirebase();
        });

        btn_back.setOnClickListener(view -> {
            Intent intent = new Intent(AbsentCheckActivity.this, MainActivity.class);
            startActivity(intent);
            finish();
        });
    }

    private void loadDataFromFirebase() {
        ref.addValueEventListener(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot snapshot) {
                absentList.clear();
                for (DataSnapshot data : snapshot.getChildren()) {
                    Absent absent = data.getValue(Absent.class);
                    if (absent != null) {
                        absentList.add(absent);
                    }
                }
                adapter.notifyDataSetChanged();
            }

            @Override
            public void onCancelled(DatabaseError error) {
                Log.e("Firebase", "Error: " + error.getMessage());
            }
        });
    }
}