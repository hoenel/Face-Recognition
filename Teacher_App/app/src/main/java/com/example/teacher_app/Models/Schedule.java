package com.example.teacher_app.Models;

public class Schedule {
    private String time;
    private String subject;
    private String classroom;

    private String date;

    public Schedule(String time, String subject, String classroom, String date) {
        this.time = time;
        this.subject = subject;
        this.classroom = classroom;
        this.date = date;
    }

    public Schedule(){

    }

    public String getTime() {
        return time;
    }

    public void setTime(String time) {
        this.time = time;
    }

    public String getSubject() {
        return subject;
    }

    public void setSubject(String subject) {
        this.subject = subject;
    }

    public String getClassroom() {
        return classroom;
    }

    public void setClassroom(String classroom) {
        this.classroom = classroom;
    }

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }
}
