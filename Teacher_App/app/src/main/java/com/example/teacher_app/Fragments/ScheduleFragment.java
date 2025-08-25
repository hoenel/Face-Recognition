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

    // TAG dùng để lọc log trong Logcat
    private static final String TAG = "ScheduleFragment_DEBUG";
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

        scheduleList = new ArrayList<>();
        scheduleAdapter = new ScheduleAdapter(scheduleList);
        recyclerView.setAdapter(scheduleAdapter);

        Log.d(TAG, "Fragment view created. Starting data load process.");
        loadScheduleDataForCurrentUser();
    }

    private void loadScheduleDataForCurrentUser() {
        FirebaseUser currentUser = mAuth.getCurrentUser();
        if (currentUser == null) {
            Log.e(TAG, "Current user is null. Cannot proceed.");
            Toast.makeText(getContext(), "Vui lòng đăng nhập.", Toast.LENGTH_LONG).show();
            return;
        }

        String uid = currentUser.getUid();
        Log.d(TAG, "Step 1: Current User UID = " + uid);

        db.collection("users").document(uid).get()
                .addOnSuccessListener(userDocument -> {
                    if (userDocument.exists()) {
                        String teacherId = userDocument.getString("teacher_id");
                        Log.d(TAG, "Step 2: Found user document. teacher_id = '" + teacherId + "'");

                        if (teacherId != null && !teacherId.isEmpty()) {
                            fetchTeacherInfoAndSchedules(teacherId);
                        } else {
                            Log.e(TAG, "teacher_id is null or empty in users collection.");
                            Toast.makeText(getContext(), "Hồ sơ người dùng không có mã giáo viên.", Toast.LENGTH_LONG).show();
                        }
                    } else {
                        Log.e(TAG, "User document with UID '" + uid + "' does not exist.");
                        Toast.makeText(getContext(), "Không tìm thấy hồ sơ người dùng.", Toast.LENGTH_LONG).show();
                    }
                })
                .addOnFailureListener(e -> {
                    Log.e(TAG, "Error getting document from 'users' collection: ", e);
                    Toast.makeText(getContext(), "Lỗi khi tải hồ sơ người dùng.", Toast.LENGTH_SHORT).show();
                });
    }

    private void fetchTeacherInfoAndSchedules(String teacherId) {
        Log.d(TAG, "Step 3: Fetching teacher info with teacher_id = '" + teacherId + "'");

        db.collection("teachers").document(teacherId).get()
                .addOnSuccessListener(teacherDocument -> {
                    if (teacherDocument.exists()) {
                        String classId = teacherDocument.getString("class_id");
                        String teacherCourseCode = teacherDocument.getString("course_code");
                        Log.d(TAG, "Step 4: Found teacher document. class_id = '" + classId + "', course_code = '" + teacherCourseCode + "'");

                        if (classId != null && !classId.isEmpty() && teacherCourseCode != null && !teacherCourseCode.isEmpty()) {
                            fetchAndFilterSchedules(classId, teacherCourseCode);
                        } else {
                            Log.e(TAG, "class_id or course_code is null or empty in teachers collection.");
                            Toast.makeText(getContext(), "Hồ sơ giáo viên thiếu thông tin lớp hoặc môn học.", Toast.LENGTH_LONG).show();
                        }
                    } else {
                        Log.e(TAG, "Teacher document with ID '" + teacherId + "' does not exist.");
                        Toast.makeText(getContext(), "Không tìm thấy thông tin giáo viên.", Toast.LENGTH_LONG).show();
                    }
                })
                .addOnFailureListener(e -> {
                    Log.e(TAG, "Error getting document from 'teachers' collection: ", e);
                    Toast.makeText(getContext(), "Lỗi khi tải hồ sơ giáo viên.", Toast.LENGTH_SHORT).show();
                });
    }

    private void fetchAndFilterSchedules(String classId, String teacherCourseCode) {
        db.collection("schedules").document(classId).get()
                .addOnCompleteListener(task -> {
                    if (task.isSuccessful()) {
                        DocumentSnapshot document = task.getResult();
                        if (document != null && document.exists()) {
                            scheduleList.clear();

                            // Lấy ra MẢNG từ trường "schedule_sessions"
                            List<Map<String, Object>> sessionsList = (List<Map<String, Object>>) document.get("schedule_sessions");

                            if (sessionsList != null && !sessionsList.isEmpty()) {

                                for (Map<String, Object> sessionData : sessionsList) {
                                    String sessionCourseCode = (String) sessionData.get("course_code");


                                    if (sessionCourseCode != null && teacherCourseCode.trim().equalsIgnoreCase(sessionCourseCode.trim())) {
                                        
                                        String subject = (String) sessionData.get("course_name");
                                        String time = (String) sessionData.get("start_time");
                                        String date = (String) sessionData.get("date");
                                        String classroom = (String) sessionData.get("classroom");

                                        Schedule scheduleItem = new Schedule(time, subject, classroom, date);
                                        scheduleList.add(scheduleItem);
                                    }
                                }
                            }

                            if (scheduleList.isEmpty()) {
                                Toast.makeText(getContext(), "Không có lịch học cho môn của bạn.", Toast.LENGTH_SHORT).show();
                            }
                            scheduleAdapter.notifyDataSetChanged();

                        } else {
                            Log.d("ScheduleFragment", "Không có tài liệu lịch học cho lớp: " + classId);
                            Toast.makeText(getContext(), "Không tìm thấy lịch học cho lớp này.", Toast.LENGTH_SHORT).show();
                        }
                    } else {
                        Log.e("ScheduleFragment", "Lỗi khi lấy tài liệu lịch học: ", task.getException());
                        Toast.makeText(getContext(), "Lỗi khi tải dữ liệu lịch học.", Toast.LENGTH_SHORT).show();
                    }
                });
    }
}