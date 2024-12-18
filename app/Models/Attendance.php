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

    public function getStudentAttendancesForCoordinator($user, $date = null)
    {
        $query = self::with([
            'user' => function ($query) use ($user) {
                $query->where('role', 'student');
                $query->where('course', $user->course);
                $query->orderBy('last_name');
            }
        ]);

        if ($date) {
            $query->where('date', $date);
        } else {
            $query->where('date', now()->toDateString());
        }
        return $query->get();
    }

    public function getStudentAttendancesForCoordinatorCount($user, $date = null)
{
    $query = self::with([
        'user' => function ($query) use ($user) {
            $query->where('role', 'student');
            $query->where('course', $user->course);
            $query->orderBy('last_name');
        }
    ]);

    if ($date) {
        $query->where('date', $date);
    }

    return $query->distinct('user_id')->count('user_id');
}

public function countTodaysAttendance($user)
{
    // Build the query to count today's attendance for students in the specified course
    $query = self::with([
        'user' => function ($query) use ($user) {
            $query->where('role', 'student')
                  ->where('course', $user->course);
        }
    ])
    ->where('date', now()->toDateString()); // Filter for today's date

    // Count distinct student IDs for today's attendance
    return $query->distinct('user_id')->count('user_id');
}

    public function getStudentAttendances($user, $date = null)
    {
        return $this->getStudentAttendancesForCoordinator($user, $date)->map(function ($attendance) {
            if ($attendance->user) {
                return [
                    'id' => $attendance->id,
                    'avatar' => $attendance->user->avatar,
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

    public function getStudentAttendancesForStudent($user, $month = null)
    {
        $query = $user->attendances();

        if ($month) {
            // Split the month into year and month components
            $dateComponents = explode('-', $month);
            if (count($dateComponents) == 2) {
                $year = $dateComponents[0];
                $month = $dateComponents[1];

                $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
            } else {
                // Handle invalid month format
                throw new \Exception('Invalid month format. Expected format: YYYY-MM');
            }
        } else {
            $query->whereDate('date', now()->toDateString());
        }

        return $query->orderBy('date', 'asc')->get();
    }

    public function getStudentAttendanceCountForStudent($user)
    {
        return $user->attendances()->count();
    }


    public function getLatestStudentAttendance($userId)
    {
        $user = User::findOrFail($userId);
        return $user->attendances()->orderBy('date', 'desc')->first();
    }
}
