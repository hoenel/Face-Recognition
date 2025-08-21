<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'student_id',
        'date',
        'status',
        'face_recognition_data',
        'check_in_time',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'datetime',
        'face_recognition_data' => 'array',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
