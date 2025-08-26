package com.example.teacher_app.Models;

public class AttendanceRecord {
    private String id;
    private String date;
    private String name;
    private String className;
    private String subjectName;
    private String status;

    public AttendanceRecord() {} // bắt buộc cho Firebase

    public AttendanceRecord(String id, String date, String name, String className, String subjectName, String status) {
        this.id = id; this.date = date; this.name = name;
        this.className = className; this.subjectName = subjectName; this.status = status;
    }

    public String getId() { return id; }
    public String getDate() { return date; }
    public String getName() { return name; }
    public String getClassName() { return className; }
    public String getSubjectName() { return subjectName; }
    public String getStatus() { return status; }

    public void setId(String id) { this.id = id; }
    public void setDate(String date) { this.date = date; }
    public void setName(String name) { this.name = name; }
    public void setClassName(String className) { this.className = className; }
    public void setSubjectName(String subjectName) { this.subjectName = subjectName; }
    public void setStatus(String status) { this.status = status; }
}
