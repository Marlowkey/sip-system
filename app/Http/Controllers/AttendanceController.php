<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Attendance\ShowRequest;
use App\Http\Requests\Attendance\IndexRequest;
use App\Http\Requests\Attendance\StoreRequest;

class AttendanceController extends Controller
{

    protected Attendance $attendance;
    protected User $user;

    public function __construct(Attendance $attendance, User $user)
    {
        $this->attendance = $attendance;
        $this->user = $user;
    }
    public function index(IndexRequest $request)
    {
        $user = auth()->user();

        $month = $request->validated()['month'] ?? null;
        $attendance = $this->attendance->getStudentAttendancesForStudent($user, $month);

        $date = $request->validated()['date'] ?? null;
        $studentAttendance = $this->attendance->getStudentAttendances($user, $date);

        return Inertia::render('Attendance/Index', [
            'user' => $user,
            'attendance' => $attendance,
            'studentAttendance' => $studentAttendance,
            'date' => $date,
            'month' => $month,
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
    public function store(StoreRequest $request)
    {
        Auth::user()->attendances()->create($request->validated());
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
    public function update(StoreRequest $request, int $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->validated());
        return redirect()->route('attendances.index')->with('message', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        Attendance::findOrFail($id)->delete();

        return redirect()->route('attendances.index')->with('message', 'Attendance deleted successfully.');
    }

    public function showStudentAttendance(ShowRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $month = $request->validated()['month'] ?? null;

        $attendance = $this->attendance->getStudentAttendancesForStudent($user, $month);
        return Inertia::render('Attendance/Show', [
            'user' => $user,
            'attendance' => $attendance,
            'month' => $month,
        ]);
    }
}

