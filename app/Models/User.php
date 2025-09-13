<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'employee_id',
        'department',
        'phone_number',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaveTypes()
    {
        return $this->hasMany(LeaveType::class);
    }

    public function leaveRequest()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    protected static function booted()
    {
        static::created(function ($user) {

            // Get the current month dynamical
            $currentMonth = now()->month;

            // Create default leave types for the user
            $user->leaveTypes()->createMany([
                [
                    'name' => 'Sick Leave',
                    'allocation_mode' => 'monthly',
                    'allocation' => 5,
                    'description' => 'Leave for sickness or medical emergencies.',
                    "month" => $currentMonth,

                ],
                [
                    'name' => 'Casual Leave',
                    'allocation_mode' => 'monthly',
                    'allocation' => 2,
                    'description' => 'Leave for personal or casual purposes.',
                    "month" => $currentMonth,
                ],
                [
                    'name' => 'Annual Leave',
                    'allocation_mode' => 'yearly',
                    'allocation' => 10,
                    'description' => 'Leave for vacations or extended breaks.',
                    "year" => now()->year
                ],
            ]);
        });
    }
}
