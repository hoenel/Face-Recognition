package com.example.teacher_app.Models;

public class Student {
    private String id;
    private String name;
    private String classId;
    private String status; // "present" | "absent" | "late" (tuỳ bạn)

    public Student() { } // Firestore cần constructor rỗng

    public Student(String id, String name, String classId, String status) {
        this.id = id;
        this.name = name;
        this.classId = classId;
        this.status = status;
    }

    public String getId() { return id; }
    public String getName() { return name; }
    public String getClassId() { return classId; }
    public String getStatus() { return status; }

    public void setId(String id) { this.id = id; }
    public void setName(String name) { this.name = name; }
    public void setClassId(String classId) { this.classId = classId; }
    public void setStatus(String status) { this.status = status; }
}
