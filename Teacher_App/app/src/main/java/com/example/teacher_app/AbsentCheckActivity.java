package com.example.teacher_app;

import android.os.Bundle;

import androidx.activity.EdgeToEdge;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.graphics.Insets;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.teacher_app.Adapters.AbsentAdapter;
import com.example.teacher_app.Models.Absent;

import java.util.ArrayList;
import java.util.List;

public class AbsentCheckActivity extends AppCompatActivity {
    private RecyclerView recyclerView;
    private AbsentAdapter adapter;
    private List<Absent> absentList;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_absent_check);

        recyclerView = findViewById(R.id.recyclerViewAbsent);
        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        absentList = new ArrayList<>();
        absentList.add(new Absent("Nguyễn Văn A", "CNTT1", "Lập trình Java", "25/08/2025"));
        absentList.add(new Absent("Trần Thị B", "CNTT2", "CSDL", "25/08/2025"));

        adapter = new AbsentAdapter(this, absentList);
        recyclerView.setAdapter(adapter);
    }
}