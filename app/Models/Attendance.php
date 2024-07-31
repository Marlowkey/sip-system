<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attendance extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStudentAttendancesForCoordinator ($user, $date = null)
    {
        $query = self::with(['user' => function ($query) use($user) {
            $query->where('role', 'student');
            $query->where('course', $user->course);
            $query->orderBy('last_name');
        }]);

        if ($date) {
            $query->where('date', $date);
        } else {
            $query->where('date', now()->toDateString());
        }
        return $query->get();
    }

    public function getStudentAttendances ($user, $date = null)
    {
        return $this->getStudentAttendancesForCoordinator($user, $date)->map(function ($attendance) {
            if ($attendance->user) {
                return [
                    'id' => $attendance->id,
                    'user_id' => $attendance->user->id,
                    'last_name' => $attendance->user->last_name,
                    'first_name' => $attendance->user->first_name,
                    'course' => $attendance->user->course,
                    'block' => $attendance->user->block,
                    'host_training_establishment' => $attendance->user->host_training_establishment,
                    'date' => $attendance->date,
                    'time_in_am' => $attendance->time_in_am,
                    'time_out_am' => $attendance->time_out_am,
                    'time_in_pm' => $attendance->time_in_pm,
                    'time_out_pm' => $attendance->time_out_pm,
                ];
            }
            return null;
        })->filter();
    }

}
