package com.example.teacher_app.Models;

public class Feedback {
    private String title;
    private String message;
    private String timestamp;

    public Feedback() {
        // Constructor rỗng cần thiết cho Firebase
    }

    public Feedback(String title, String message, String timestamp) {
        this.title = title;
        this.message = message;
        this.timestamp = timestamp;
    }

    // Các hàm getter và setter giữ nguyên
    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getTimestamp() {
        return timestamp;
    }

    public void setTimestamp(String timestamp) {
        this.timestamp = timestamp;
    }
}