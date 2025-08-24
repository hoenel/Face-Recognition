package com.example.facerecognitionapp.Fragments;

import android.content.Intent;
import android.os.Bundle;

import androidx.fragment.app.Fragment;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.Toast;

import com.example.facerecognitionapp.FaceRegisterActivity;
import com.example.facerecognitionapp.FeedbackActivity;
import com.example.facerecognitionapp.LoginActivity;
import com.example.facerecognitionapp.R;
import com.example.facerecognitionapp.UserProfileActivity;


public class ProfileFragment extends Fragment {
    private LinearLayout item_username, item_feedback, item_face_register, item_logout;

    public ProfileFragment() {
        // Required empty public constructor
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        return inflater.inflate(R.layout.fragment_profile, container, false);
    }

    @Override
    public void onViewCreated(View view, Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        item_username = view.findViewById(R.id.item_username);
        item_feedback = view.findViewById(R.id.item_feedback);
        item_face_register = view.findViewById(R.id.item_face_register);
        item_logout = view.findViewById(R.id.item_logout);

        item_username.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(getActivity(), UserProfileActivity.class));
            }
        });

        item_feedback.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(getActivity(), FeedbackActivity.class));
            }
        });

        item_logout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Toast.makeText(getActivity(), "Đã đăng xuất tài khoản!", Toast.LENGTH_SHORT).show();
                startActivity(new Intent(getActivity(), LoginActivity.class).addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK));
                getActivity().finish();
            }
        });

        item_face_register.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(getActivity(), FaceRegisterActivity.class));
            }
        });

    }
}