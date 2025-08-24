package com.example.facerecognitionapp.Adapters;

import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.facerecognitionapp.Models.Schedule;
import com.example.facerecognitionapp.R;
import java.util.List;

public class ScheduleAdapter extends RecyclerView.Adapter<ScheduleAdapter.ScheduleViewHolder> {

    private List<Schedule> scheduleList;

    public ScheduleAdapter(List<Schedule> scheduleList) {
        this.scheduleList = scheduleList;
    }

    @NonNull
    @Override
    public ScheduleAdapter.ScheduleViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_schedule, parent, false);
        return new ScheduleViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ScheduleAdapter.ScheduleViewHolder holder, int position) {
        Schedule item = scheduleList.get(position);

        holder.tvDate.setText(item.getDate());
        holder.tvTime.setText(item.getTime());
        holder.tvSubject.setText(item.getSubject());
        holder.tvClassroom.setText(item.getClassroom());
    }

    @Override
    public int getItemCount() {
        return scheduleList.size();
    }

    public static class ScheduleViewHolder extends RecyclerView.ViewHolder {
        TextView tvTime, tvSubject, tvClassroom, tvDate;
        public ScheduleViewHolder(@NonNull View itemView) {
            super(itemView);
            tvDate = itemView.findViewById(R.id.tvDate);
            tvTime = itemView.findViewById(R.id.tvTime);
            tvSubject = itemView.findViewById(R.id.tvSubject);
            tvClassroom = itemView.findViewById(R.id.tvClassroom);
        }
    }
}