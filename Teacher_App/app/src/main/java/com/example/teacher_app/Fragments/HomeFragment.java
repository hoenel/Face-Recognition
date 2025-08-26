package com.example.teacher_app.Fragments;

import android.app.Dialog;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentTransaction;

import com.example.teacher_app.AbsentCheckActivity;
import com.example.teacher_app.AttendanceStatusActivity;
import com.example.teacher_app.R;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.firestore.FirebaseFirestore;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Locale;


public class HomeFragment extends Fragment {
    TextView tvTime, tvDate, tvWelcome;

    FirebaseFirestore db;
    FirebaseAuth mAuth;


    public HomeFragment() {
        // Required empty public constructor
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_home, container, false);

        LinearLayout btn_schedule = view.findViewById(R.id.btn_schedule);
        LinearLayout btn_news = view.findViewById(R.id.btn_news);
        LinearLayout btn_absent = view.findViewById(R.id.btn_absent);
        LinearLayout btn_attendance = view.findViewById(R.id.btn_attendance);


        tvTime = view.findViewById(R.id.tvTime);
        tvDate = view.findViewById(R.id.tvDate);
        tvWelcome = view.findViewById(R.id.tvWelcome);

        setDateTime(tvTime, tvDate);

        db = FirebaseFirestore.getInstance();
        mAuth = FirebaseAuth.getInstance();

        String uid = mAuth.getCurrentUser().getUid();
        db.collection("users").document(uid).get().addOnSuccessListener(documentSnapshot -> {
            if (documentSnapshot.exists()) {
                String name = documentSnapshot.getString("name");
                String teacherId = documentSnapshot.getString("teacher_id");

                tvWelcome.setText("Xin chào, " + name + " !");

                Log.d("Firestore", "Teacher ID: " + teacherId);
            }
                });
        btn_schedule.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Fragment scheduleFragment = new ScheduleFragment();
                FragmentTransaction fragmentTransaction = getFragmentManager().beginTransaction();
                fragmentTransaction.replace(R.id.fragment_container, scheduleFragment);
                fragmentTransaction.addToBackStack(null);
                fragmentTransaction.commit();
            }
        });

        btn_news.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Fragment notificationFragment = new NotificationFragment();
                FragmentTransaction fragmentTransaction = getFragmentManager().beginTransaction();
                fragmentTransaction.replace(R.id.fragment_container, notificationFragment);
                fragmentTransaction.addToBackStack(null);
                fragmentTransaction.commit();
            }
        });

        btn_absent.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getActivity(), AbsentCheckActivity.class);
                startActivity(intent);
            }
        });

        btn_attendance.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Intent intent = new Intent(getActivity(), AttendanceStatusActivity.class);
                startActivity(intent);
            }
        });





        return view;
    }

    private void setDateTime(TextView tvTime, TextView tvDate) {
        Calendar calendar = Calendar.getInstance();

        // Giờ:Phút
        SimpleDateFormat timeFormat = new SimpleDateFormat("HH:mm", Locale.getDefault());
        String currentTime = timeFormat.format(calendar.getTime());
        tvTime.setText(currentTime);

        // Ngày + thứ
        SimpleDateFormat dateFormat = new SimpleDateFormat("EEEE, dd/MM/yyyy", new Locale("vi", "VN"));
        String currentDate = dateFormat.format(calendar.getTime());
        tvDate.setText(currentDate);
    }
}