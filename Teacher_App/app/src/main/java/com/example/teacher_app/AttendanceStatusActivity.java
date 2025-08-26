package com.example.teacher_app;

import android.app.DatePickerDialog;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.View;
import android.widget.*;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.teacher_app.Adapters.AttendanceAdapter;
import com.example.teacher_app.Models.AttendanceRecord;
import com.google.firebase.database.*;

import java.text.SimpleDateFormat;
import java.util.*;

public class AttendanceStatusActivity extends AppCompatActivity {

    private EditText edtDate, edtName, edtSubject;
    private Spinner spnClass, spnStatus;
    private Button btnClear;
    private ProgressBar progress;
    private TextView tvEmpty;
    private AttendanceAdapter adapter;

    private final List<AttendanceRecord> allRecords = new ArrayList<>();
    private final Set<String> classSet = new HashSet<>();

    private final String STATUS_ALL = "Tất cả";
    private final String CLASS_ALL = "Tất cả";

    private final SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_attendance_status);

        // Ánh xạ view
        ImageButton btnBack = findViewById(R.id.btn_back);
        edtDate   = findViewById(R.id.edt_date);
        edtName   = findViewById(R.id.edt_name);
        edtSubject= findViewById(R.id.edt_subject);
        spnClass  = findViewById(R.id.spn_class);
        spnStatus = findViewById(R.id.spn_status);
        btnClear  = findViewById(R.id.btn_clear_filters);
        progress  = findViewById(R.id.progress);
        tvEmpty   = findViewById(R.id.tv_empty);
        RecyclerView rv = findViewById(R.id.recycler_attendance);

        btnBack.setOnClickListener(v -> onBackPressed());

        // RecyclerView
        adapter = new AttendanceAdapter();
        rv.setLayoutManager(new LinearLayoutManager(this));
        rv.setAdapter(adapter);

        // Spinner Trạng thái
        ArrayAdapter<String> statusAdapter = new ArrayAdapter<>(
                this, android.R.layout.simple_spinner_dropdown_item,
                Arrays.asList(STATUS_ALL, "Có mặt", "Muộn", "Vắng")
        );
        spnStatus.setAdapter(statusAdapter);

        // Spinner Lớp (sẽ cập nhật sau khi tải data)
        ArrayAdapter<String> classAdapter = new ArrayAdapter<>(
                this, android.R.layout.simple_spinner_dropdown_item,
                new ArrayList<>(Collections.singletonList(CLASS_ALL))
        );
        spnClass.setAdapter(classAdapter);

        // Ngày mặc định: hôm nay
        String today = df.format(new Date());
        edtDate.setText(today);

        // Chọn ngày
        edtDate.setOnClickListener(v -> showDatePicker());

        // Listeners lọc
        TextWatcher watcher = new TextWatcher() {
            @Override public void beforeTextChanged(CharSequence s, int start, int count, int after) {}
            @Override public void onTextChanged(CharSequence s, int start, int before, int count) { applyFilters(); }
            @Override public void afterTextChanged(Editable s) {}
        };
        edtName.addTextChangedListener(watcher);
        edtSubject.addTextChangedListener(watcher);

        spnStatus.setOnItemSelectedListener(new SimpleOnItemSelectedListener(this::applyFilters));
        spnClass.setOnItemSelectedListener(new SimpleOnItemSelectedListener(this::applyFilters));

        btnClear.setOnClickListener(v -> {
            edtName.setText("");
            edtSubject.setText("");
            spnStatus.setSelection(0);
            spnClass.setSelection(0);
            applyFilters();
        });

        // Tải data lần đầu
        loadDataForDate(today, classAdapter);
    }

    private void showDatePicker() {
        Calendar c = Calendar.getInstance();
        try {
            Date d = df.parse(edtDate.getText().toString());
            if (d != null) c.setTime(d);
        } catch (Exception ignored) {}

        new DatePickerDialog(this, (view, y, m, day) -> {
            Calendar picked = Calendar.getInstance();
            picked.set(y, m, day);
            String dateStr = df.format(picked.getTime());
            edtDate.setText(dateStr);
            // đổi ngày → tải lại từ DB
            ArrayAdapter<String> classAdapter = (ArrayAdapter<String>) spnClass.getAdapter();
            loadDataForDate(dateStr, classAdapter);
        }, c.get(Calendar.YEAR), c.get(Calendar.MONTH), c.get(Calendar.DAY_OF_MONTH)).show();
    }

    /** Đọc dữ liệu theo ngày từ Firebase */
    private void loadDataForDate(String date, ArrayAdapter<String> classAdapter) {
        progress.setVisibility(View.VISIBLE);
        tvEmpty.setVisibility(View.GONE);
        allRecords.clear();
        classSet.clear();
        classSet.add(CLASS_ALL);

        DatabaseReference ref = FirebaseDatabase.getInstance()
                .getReference("attendance")
                .child(date);

        // Lấy 1 lần (nếu muốn realtime thay bằng addValueEventListener)
        ref.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override public void onDataChange(@NonNull DataSnapshot snap) {
                if (!snap.exists()) {
                    adapter.setData(Collections.emptyList());
                    progress.setVisibility(View.GONE);
                    tvEmpty.setVisibility(View.VISIBLE);
                    // cập nhật spinner lớp
                    updateClassSpinner(classAdapter);
                    return;
                }
                for (DataSnapshot child : snap.getChildren()) {
                    AttendanceRecord r = child.getValue(AttendanceRecord.class);
                    if (r == null) continue;
                    r.setId(child.getKey());
                    r.setDate(date);
                    allRecords.add(r);
                    if (r.getClassName() != null && !r.getClassName().isEmpty())
                        classSet.add(r.getClassName());
                }
                updateClassSpinner(classAdapter);
                progress.setVisibility(View.GONE);
                applyFilters();
            }

            @Override public void onCancelled(@NonNull DatabaseError error) {
                progress.setVisibility(View.GONE);
                Toast.makeText(AttendanceStatusActivity.this,
                        "Lỗi tải dữ liệu: " + error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void updateClassSpinner(ArrayAdapter<String> classAdapter) {
        List<String> classes = new ArrayList<>(classSet);
        Collections.sort(classes);
        classAdapter.clear();
        classAdapter.addAll(classes);
        classAdapter.notifyDataSetChanged();
        spnClass.setSelection(0);
    }

    /** Áp dụng các bộ lọc đang chọn */
    private void applyFilters() {
        String kwName = edtName.getText().toString().trim().toLowerCase(Locale.getDefault());
        String kwSubject = edtSubject.getText().toString().trim().toLowerCase(Locale.getDefault());

        String classFilter = spnClass.getSelectedItem() == null ? CLASS_ALL
                : spnClass.getSelectedItem().toString();

        String statusFilter = spnStatus.getSelectedItem() == null ? STATUS_ALL
                : spnStatus.getSelectedItem().toString();

        List<AttendanceRecord> filtered = new ArrayList<>();
        for (AttendanceRecord r : allRecords) {
            boolean ok = true;

            if (!kwName.isEmpty())
                ok &= r.getName() != null && r.getName().toLowerCase(Locale.getDefault()).contains(kwName);

            if (!kwSubject.isEmpty())
                ok &= r.getSubjectName() != null && r.getSubjectName().toLowerCase(Locale.getDefault()).contains(kwSubject);

            if (!CLASS_ALL.equals(classFilter))
                ok &= r.getClassName() != null && r.getClassName().equals(classFilter);

            if (!STATUS_ALL.equals(statusFilter))
                ok &= r.getStatus() != null && r.getStatus().equalsIgnoreCase(statusFilter);

            if (ok) filtered.add(r);
        }

        adapter.setData(filtered);
        tvEmpty.setVisibility(filtered.isEmpty() ? View.VISIBLE : View.GONE);
    }

    /** Listener rút gọn cho Spinner */
    private static class SimpleOnItemSelectedListener implements AdapterView.OnItemSelectedListener {
        private final Runnable onSelected;
        SimpleOnItemSelectedListener(Runnable onSelected) { this.onSelected = onSelected; }
        @Override public void onItemSelected(AdapterView<?> parent, View view, int position, long id) { onSelected.run(); }
        @Override public void onNothingSelected(AdapterView<?> parent) {}
    }
}
