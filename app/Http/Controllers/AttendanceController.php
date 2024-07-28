<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $date = $request->query('date');
        $attendance = $user->attendances;

        $request->validate([
            'date' => 'nullable|date',
        ]);

        $attendanceQuery = Attendance::with(['user' => function ($query) {
            $user = auth()->user();
            $query->where('course', $user->course);
        }]);


        if($date) {
            $attendanceQuery->where('date', $date);
        }

        $attendances = $attendanceQuery->get();
        $studentAttendance = $attendances->map(function ($attendance){
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

        return Inertia::render('Attendance/Index', [
            'user' => $user,
            'attendance' => $attendance,
            'studentAttendance' => $studentAttendance,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        return Inertia::render('Attendance/Create', [
            'user' => $user,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'time_in_am' => 'nullable',
            'time_out_am' => 'nullable',
            'time_in_pm' => 'nullable',
            'time_out_pm' => 'nullable',
        ]);

     Attendance::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'date' => $request->date,
            ],
            [
                'time_in_am' => $request->time_in_am,
                'time_out_am' => $request->time_out_am,
                'time_in_pm' => $request->time_in_pm,
                'time_out_pm' => $request->time_out_pm,           ]
        );

        return redirect()->route('attendances.index')->with('message', 'Attendance recorded successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);

        return Inertia::render('Attendance/Create', [
            'attendance' => $attendance,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return redirect()->route('attendances.index')->with('message', 'Attendance deleted successfully.');
    }

    public function showStudentAttendance ( $id)
    {
        $user = User::findOrFail($id);
        $attendance = $user->attendances;

        return Inertia::render('Attendance/Show', [
            'user' => $user,
            'attendance' => $attendance,
        ]);
    }
}
