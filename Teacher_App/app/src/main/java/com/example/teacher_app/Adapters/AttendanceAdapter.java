package com.example.teacher_app.Adapters;

import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.teacher_app.R;
import com.example.teacher_app.Models.AttendanceRecord;
import java.util.ArrayList;
import java.util.List;

public class AttendanceAdapter extends RecyclerView.Adapter<AttendanceAdapter.AttViewHolder> {

    private final List<AttendanceRecord> data = new ArrayList<>();

    public void setData(List<AttendanceRecord> items) {
        data.clear();
        if (items != null) data.addAll(items);
        notifyDataSetChanged();
    }

    @NonNull @Override
    public AttViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext())
                .inflate(R.layout.item_attendance_status, parent, false);
        return new AttViewHolder(v);
    }

    @Override
    public void onBindViewHolder(@NonNull AttViewHolder h, int position) {
        AttendanceRecord r = data.get(position);
        h.tvName.setText(r.getName());
        h.tvClassSubject.setText("Lớp: " + r.getClassName() + " • Môn: " + r.getSubjectName());
        h.tvStatus.setText("Trạng thái: " + r.getStatus());

        // Tô màu theo trạng thái
        String s = r.getStatus() == null ? "" : r.getStatus().trim().toLowerCase();
        if (s.contains("có mặt"))       h.tvStatus.setTextColor(Color.parseColor("#2E7D32")); // xanh
        else if (s.contains("muộn"))    h.tvStatus.setTextColor(Color.parseColor("#F57C00")); // cam
        else if (s.contains("vắng"))    h.tvStatus.setTextColor(Color.parseColor("#C62828")); // đỏ
        else                            h.tvStatus.setTextColor(Color.DKGRAY);
    }

    @Override public int getItemCount() { return data.size(); }

    static class AttViewHolder extends RecyclerView.ViewHolder {
        TextView tvName, tvClassSubject, tvStatus;
        AttViewHolder(@NonNull View itemView) {
            super(itemView);
            tvName = itemView.findViewById(R.id.tv_name);
//            tvClassSubject = itemView.findViewById(R.id.tv_class_subject);
            tvStatus = itemView.findViewById(R.id.tv_status);
        }
    }
}
