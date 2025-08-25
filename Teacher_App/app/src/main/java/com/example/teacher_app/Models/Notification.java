package com.example.teacher_app.Models;

public class Notification {
    private String message;
    private String title;

    public Notification(String message, String title) {
        this.message = message;
        this.title = title;
    }

    public Notification(){

    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

}
