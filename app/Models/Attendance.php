<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;

    protected $fillable = [
        'clock_in',
        'clock_out',
        'attendance_date',
        "status"
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
