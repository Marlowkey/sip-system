<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\Attendance\StoreRequest;
use App\Http\Requests\Attendance\IndexRequest;
use Illuminate\Support\Facades\Auth;



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
        $attendance = $user->attendances;

        $date = $request->validated()['date'] ?? null;

        $studentAttendance = $this->attendance->getStudentAttendances($user, $date);

        return Inertia::render('Attendance/Index', [
            'user' => $user,
            'attendance' => $attendance,
            'studentAttendance' => $studentAttendance,
            'date' => $date,
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
    public function update(StoreRequest $request)
    {
        Auth::user()->attendances()->update($request->validated());
        return redirect()->route('attendances.index')->with('message', 'Attendance updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Attendance::findOrFail($id)->delete();

        return redirect()->route('attendances.index')->with('message', 'Attendance deleted successfully.');
    }

    public function showStudentAttendance($id)
    {
        $user = User::findOrFail($id);
        $attendance = $user->attendances;

        return Inertia::render('Attendance/Show', [
            'user' => $user,
            'attendance' => $attendance,
        ]);
    }
}
