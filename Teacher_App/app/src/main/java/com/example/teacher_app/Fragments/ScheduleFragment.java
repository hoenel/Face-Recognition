package com.example.teacher_app.Fragments;

import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.teacher_app.Adapters.ScheduleAdapter;
import com.example.teacher_app.Models.Schedule;
import com.example.teacher_app.R;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.FirebaseUser;
import com.google.firebase.firestore.DocumentSnapshot;
import com.google.firebase.firestore.FirebaseFirestore;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

public class ScheduleFragment extends Fragment {

    private static final String TAG = "ScheduleFragment";
    private RecyclerView recyclerView;
    private ScheduleAdapter scheduleAdapter;
    private List<Schedule> scheduleList;
    private FirebaseFirestore db;

    private FirebaseAuth mAuth;

    public ScheduleFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_schedule, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);


        db = FirebaseFirestore.getInstance();
        mAuth = FirebaseAuth.getInstance();

        recyclerView = view.findViewById(R.id.rvSchedule);
        recyclerView.setLayoutManager(new LinearLayoutManager(getContext()));

        // Khởi tạo danh sách và adapter
        scheduleList = new ArrayList<>();
        scheduleAdapter = new ScheduleAdapter(scheduleList);
        recyclerView.setAdapter(scheduleAdapter);

        // Bắt đầu quá trình lấy dữ liệu
        loadUserAndScheduleData();
    }

    private void loadUserAndScheduleData() {
        FirebaseUser currentUser = mAuth.getCurrentUser();
        if (currentUser != null) {
            String uid = currentUser.getUid();

            // Bước 1: Lấy thông tin người dùng từ collection "users" bằng UID
            db.collection("users").document(uid).get()
                    .addOnSuccessListener(documentSnapshot -> {
                        if (documentSnapshot.exists()) {
                            // Lấy ra classId từ hồ sơ người dùng
                            String classId = documentSnapshot.getString("class_id");

                            if (classId != null && !classId.isEmpty()) {
                                // Bước 2: Dùng classId để lấy lịch học
                                fetchScheduleDataForClass(classId);
                            } else {
                                Toast.makeText(getContext(), "Hồ sơ của bạn thiếu thông tin lớp học.", Toast.LENGTH_LONG).show();
                            }
                        } else {
                            Toast.makeText(getContext(), "Không tìm thấy hồ sơ người dùng.", Toast.LENGTH_LONG).show();
                        }
                    })
                    .addOnFailureListener(e -> {
                        Log.e(TAG, "Lỗi khi lấy thông tin người dùng: ", e);
                        Toast.makeText(getContext(), "Lỗi khi tải hồ sơ.", Toast.LENGTH_SHORT).show();
                    });
        } else {
            // Xử lý trường hợp người dùng chưa đăng nhập
            Toast.makeText(getContext(), "Vui lòng đăng nhập để xem lịch học.", Toast.LENGTH_LONG).show();
        }
    }

    private void fetchScheduleDataForClass(String classId) {
        db.collection("schedules").document(classId)
                .get()
                .addOnCompleteListener(task -> {
                    if (task.isSuccessful()) {
                        DocumentSnapshot document = task.getResult();
                        if (document != null && document.exists()) {
                            List<Map<String, Object>> sessionsMap = (List<Map<String, Object>>) document.get("schedule_sessions");

                            if (sessionsMap != null && !sessionsMap.isEmpty()) {
                                scheduleList.clear();


                                for (Map<String, Object> map : sessionsMap) {
                                    // Ánh xạ dữ liệu từ Map sang đối tượng Schedule
                                    String subject = (String) map.get("course_name");
                                    String time = (String) map.get("start_time");
                                    String date = (String) map.get("date");

                                    String classroom = (String) map.get("classroom");

                                    Schedule scheduleItem = new Schedule(time, subject, classroom, date);
                                    scheduleList.add(scheduleItem);
                                }
                                scheduleAdapter.notifyDataSetChanged();
                            } else {
                                Log.d(TAG, "schedule_sessions array is null or empty");
                                Toast.makeText(getContext(), "Không có lịch học.", Toast.LENGTH_SHORT).show();
                            }
                        } else {
                            Log.d(TAG, "No such document for class: " + classId);
                            Toast.makeText(getContext(), "Không tìm thấy lịch học cho lớp này.", Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        Log.e(TAG, "Error getting documents: ", task.getException());
                        Toast.makeText(getContext(), "Lỗi khi tải dữ liệu.", Toast.LENGTH_SHORT).show();
                    }
                });
    }
}