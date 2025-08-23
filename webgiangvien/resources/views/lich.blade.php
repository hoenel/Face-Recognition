<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch giảng dạy</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
        }
        /* Sidebar */
        .sidebar {
            width: 220px;
            background-color: #1f2a44;
            color: #fff;
            padding: 20px 10px;
        }
        .sidebar h2 {
            font-size: 18px;
            margin-bottom: 20px;
            text-align: center;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .sidebar a:hover {
            background: #2e3d5c;
        }

        /* Main content */
        .main {
            flex: 1;
            background: #f4f6f9;
            padding: 20px;
        }
        .card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0,0,0,0.1);
        }
        .filters {
            margin-bottom: 20px;
        }
        .filters select, .filters input, .filters button {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: top;
        }
        th {
            background: #1f2a44;
            color: white;
        }
        .event {
            color: #fff;
            padding: 4px;
            border-radius: 4px;
            margin: 2px 0;
            display: block;
            font-size: 12px;
        }
        .blue { background: #007bff; }
        .green { background: #28a745; }
        .orange { background: #fd7e14; }
        .red { background: #dc3545; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>HTD - Điểm Danh</h2>
        <a href="{{ route('tongquan') }}">Tổng quan lớp học</a>
        <a href="{{ route('lich') }}" class="active">Lịch giảng dạy</a>
        <a href="{{ route('taobuoihoc') }}">Tạo buổi học / điểm danh</a>
        <a href="{{ route('trangthaidiemdanh') }}">Trạng thái điểm danh</a>
        <a href="{{ route('thongkechuyencan') }}">Thống kê chuyên cần</a>
        <a href="{{ route('xuatbaocao') }}">Xuất báo cáo</a>
        <a href="{{ route('dangnhap') }}">Đăng xuất</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <div class="card">
            <h2>Lịch giảng dạy</h2>

            <!-- Bộ lọc -->
            <div class="filters">
                <select id="filter-class">
                    <option value="">-- Chọn lớp --</option>
                    <option value="63CNTT.NB">63CNTT.NB</option>
                    <option value="63CNTT1">63CNTT1</option>
                </select>
                <select id="filter-course">
                    <option value="">-- Chọn môn học --</option>
                    <option value="MATH111">Giải tích</option>
                    <option value="CSE441">Phát triển ứng dụng di động</option>
                </select>
                <input type="date" id="filter-date">
                <select id="filter-term">
                    <option value="">-- Học kỳ --</option>
                    <option value="1">Học kỳ 1</option>
                    <option value="2">Học kỳ 2</option>
                </select>
                <button id="filter-btn">Lọc</button>
            </div>

            <!-- Bảng lịch -->
            <table>
                <thead>
                    <tr>
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

    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js"></script>

    <script>
    const firebaseConfig = {
        apiKey: "AIzaSyAygULXRSt9Nsqy2rb9Z4NNvh4Z4KvdK7c",
        authDomain: "facerecognitionapp-f034d.firebaseapp.com",
        projectId: "facerecognitionapp-f034d",
        storageBucket: "facerecognitionapp-f034d.firebasestorage.app",
        messagingSenderId: "1042946521446",
        appId: "1:1042946521446:web:02de5802629d422a5330a7"
    };

    firebase.initializeApp(firebaseConfig);
    const db = firebase.firestore();

    // Hàm chuyển ngày sang thứ
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

        let daysArr = ['','','','','','','']; // để trống, có thể thêm số ngày sau
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
                    // Áp dụng filter
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
</body>
</html>
<!DOCTYPE html>