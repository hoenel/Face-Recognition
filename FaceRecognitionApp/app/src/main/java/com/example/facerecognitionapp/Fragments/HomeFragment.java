package com.example.facerecognitionapp.Fragments;

import android.app.Dialog;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;

import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentTransaction;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.example.facerecognitionapp.R;
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

        tvTime = view.findViewById(R.id.tvTime);
        tvDate = view.findViewById(R.id.tvDate);
        tvWelcome = view.findViewById(R.id.tvWelcome);

        setDateTime(tvTime, tvDate);

        db = FirebaseFirestore.getInstance();
        mAuth = FirebaseAuth.getInstance();

        String uid = mAuth.getCurrentUser().getUid();
        db.collection("users").document(uid).get().addOnSuccessListener(documentSnapshot -> {
                    String name = documentSnapshot.getString("name");
                    tvWelcome.setText("Xin chào, " + name + " !");
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
            public void onClick(View v) {
                Dialog dialog = new Dialog(requireContext());
                dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
                dialog.setContentView(R.layout.dialog_absent);

                if (dialog.getWindow() != null) {
                    dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
                    dialog.getWindow().clearFlags(WindowManager.LayoutParams.FLAG_DIM_BEHIND);
                    dialog.getWindow().addFlags(WindowManager.LayoutParams.FLAG_DIM_BEHIND);
                    dialog.getWindow().setDimAmount(0.8f);
                }

                Button btnHuy, btnBaoNghi, btnChapNhan;

                btnHuy = dialog.findViewById(R.id.btnHuy);
                btnBaoNghi = dialog.findViewById(R.id.btnBaoNghi);

                btnHuy.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        dialog.dismiss();
                    }
                });
                btnBaoNghi.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        dialog.dismiss();
                        Dialog dialogChecked = new Dialog(requireContext());
                        dialogChecked.requestWindowFeature(Window.FEATURE_NO_TITLE);
                        dialogChecked.setContentView(R.layout.dialog_absent_checked);

                        if (dialogChecked.getWindow() != null) {
                            dialogChecked.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
                            dialogChecked.getWindow().clearFlags(WindowManager.LayoutParams.FLAG_DIM_BEHIND);
                            dialogChecked.getWindow().addFlags(WindowManager.LayoutParams.FLAG_DIM_BEHIND);
                            dialogChecked.getWindow().setDimAmount(0.8f);
                        }

                        Button btnChapNhan = dialogChecked.findViewById(R.id.btnChapNhan);
                        btnChapNhan.setOnClickListener(v2 -> dialogChecked.dismiss());

                        dialogChecked.show();
                    }
                });

                dialog.show();

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