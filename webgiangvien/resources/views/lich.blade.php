@extends('layouts.app')

@section('title', 'Lịch giảng dạy')

@section('active-lich', 'active')

@section('content')
<div class="card shadow-lg p-4" style="border-radius:18px; background: #fff;">
    <h2 class="mb-4" style="font-weight:700; color:#243B55; letter-spacing:1px;">Lịch giảng dạy</h2>
    <div class="filters d-flex flex-wrap align-items-center mb-4 gap-3" style="background:#f8fafc; border-radius:12px; padding:18px 16px; box-shadow:0 2px 8px rgba(36,59,85,0.05);">
        <select id="filter-class" class="form-select" style="max-width:180px;">
            <option value="">-- Chọn lớp --</option>
            <option value="63CNTT.NB">63CNTT.NB</option>
            <option value="63CNTT1">63CNTT1</option>
        </select>
        <select id="filter-course" class="form-select" style="max-width:220px;">
            <option value="">-- Chọn môn học --</option>
            <option value="MATH111">Giải tích</option>
            <option value="CSE441">Phát triển ứng dụng di động</option>
        </select>
        <input type="date" id="filter-date" class="form-control" style="max-width:160px;">
        <select id="filter-term" class="form-select" style="max-width:120px;">
            <option value="">-- Học kỳ --</option>
            <option value="1">Học kỳ 1</option>
            <option value="2">Học kỳ 2</option>
        </select>
        <button id="filter-btn" class="btn btn-primary px-4" style="border-radius:8px; font-weight:600;">Lọc</button>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" style="border-radius:12px; overflow:hidden;">
            <thead class="table-light">
                <tr style="background:#243B55; color:#fff;">
                    <th>T2</th>
                    <th>T3</th>
                    <th>T4</th>
                    <th>T5</th>
                    <th>T6</th>
                    <th>T7</th>
                    <th>CN</th>
                </tr>
            </thead>
            <tbody id="calendar-body">
                <!-- Dữ liệu sẽ được đổ từ Firestore -->
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
const db = firebase.firestore();

function getDayOfWeek(dateStr) {
    const days = ['T2','T3','T4','T5','T6','T7','CN'];
    const match = dateStr.match(/Thứ\s*(\d)/);
    if(match) {
        let idx = parseInt(match[1],10)-2;
        return idx >=0 && idx < 7 ? days[idx] : null;
    }
    return null;
}

async function loadSchedule(filters = {}) {
    const calendarBody = document.getElementById('calendar-body');
    calendarBody.innerHTML = '';

    let dayRow = document.createElement('tr');
    let eventRow = document.createElement('tr');

    let daysArr = ['','','','','','',''];
    daysArr.forEach(day => {
        let td = document.createElement('td');
        td.textContent = day;
        dayRow.appendChild(td);
    });

    let events = [[],[],[],[],[],[],[]];

    const snapshot = await db.collection("schedules").get();
    snapshot.forEach(doc => {
        const scheduleDoc = doc.data();
        if(scheduleDoc.schedule_sessions) {
            Object.values(scheduleDoc.schedule_sessions).forEach(item => {
                if(filters.classId && item.class_id !== filters.classId) return;
                if(filters.courseCode && item.course_code !== filters.courseCode) return;
                if(filters.date && !item.date.includes(filters.date)) return;

                const dayOfWeek = getDayOfWeek(item.date || '');
                const dayIdx = ['T2','T3','T4','T5','T6','T7','CN'].indexOf(dayOfWeek);
                if(dayIdx >=0) {
                    let colorClass = 'blue';
                    if(item.course_name && item.course_name.toLowerCase().includes('csdl')) colorClass = 'green';
                    else if(item.course_name && item.course_name.toLowerCase().includes('ai')) colorClass = 'orange';
                    else if(item.course_name && item.course_name.toLowerCase().includes('python')) colorClass = 'red';
                    events[dayIdx].push(
                        `<span class="event ${colorClass}">
                            ${item.course_name || ''}<br>
                            ${item.classroom || ''}<br>
                            ${item.start_time || ''}
                        </span>`
                    );
                }
            });
        }
    });

    events.forEach(evArr => {
        let td = document.createElement('td');
        td.innerHTML = evArr.join('');
        eventRow.appendChild(td);
    });

    calendarBody.appendChild(dayRow);
    calendarBody.appendChild(eventRow);
}

document.addEventListener('DOMContentLoaded', function() {
    loadSchedule();

    document.getElementById('filter-btn').addEventListener('click', function() {
        const filters = {
            classId: document.getElementById('filter-class').value,
            courseCode: document.getElementById('filter-course').value,
            date: document.getElementById('filter-date').value ? 
                  document.getElementById('filter-date').value.split('-').reverse().join('/') : ''
        };
        loadSchedule(filters);
    });
});
</script>
@endsection
<!DOCTYPE html>