package com.example.teacher_app.Adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.teacher_app.Models.Absent;
import com.example.teacher_app.R;

import java.util.List;

public class AbsentAdapter extends RecyclerView.Adapter<AbsentAdapter.AbsentViewHolder> {

    private Context context;
    private List<Absent> absentItemList;

    public AbsentAdapter(Context context, List<Absent> absentItemList){
        this.context = context;
        this.absentItemList = absentItemList;
    }

    @NonNull
    @Override
    public AbsentViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_absent, parent, false);
        return new AbsentViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull AbsentViewHolder holder, int position) {
        Absent item = absentItemList.get(position);

        holder.tvName.setText(item.getName());
        holder.tvClass.setText(item.getClassName());
        holder.tvSubject.setText(item.getSubject());
        holder.tvDate.setText(item.getDate());
    }

    @Override
    public int getItemCount() {
        return absentItemList.size();
    }

    public static class AbsentViewHolder extends RecyclerView.ViewHolder{
        TextView tvName, tvClass, tvSubject, tvDate;
        public AbsentViewHolder(@NonNull View itemView){
            super(itemView);
            tvName = itemView.findViewById(R.id.tvStudentName);
            tvClass = itemView.findViewById(R.id.tvStudentId);
            tvSubject = itemView.findViewById(R.id.tvSubject);
            tvDate = itemView.findViewById(R.id.tvDate);
        }
    }
}
