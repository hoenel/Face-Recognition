package com.example.teacher_app.Models;

public class Absent {
    private String name;
    private String className;
    private String subject;
    private String date;

    public Absent() {
    }

    public Absent(String name, String className, String subject, String date) {
        this.name = name;
        this.className = className;
        this.subject = subject;
        this.date = date;
    }

    public String getName(){
        return name;
    }

    public String getClassName() {
        return className;
    }

    public String getSubject() {
        return subject;
    }

    public String getDate() {
        return date;
    }

    public void setName(String name) {
        this.name = name;
    }

    public void setClassName(String className) {
        this.className = className;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public void setSubject(String subject) {
        this.subject = subject;
    }
}
