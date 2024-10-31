<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Exports\AttendanceExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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

        $month = $request->validated()['month'] ?? Carbon::now()->format('Y-m'); // Get the current month if $month is null
        $attendance = $this->attendance->getStudentAttendancesForStudent($user, $month);

        $date = $request->validated()['date'] ?? null;
        $studentAttendance = $this->attendance->getStudentAttendances($user, $date);


        // Dump the count of today's attendance for debugging
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

    public function update(StoreRequest $request, int $id)
    {
        $attendance = Attendance::findOrFail($id);

        if ($attendance->created_at->lt(Carbon::now()->subDay())) {
            return redirect()->back()->with('error', 'Attendance cannot be edited after a day.');
        }

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
        $month = $request->validated()['month'] ?? Carbon::now()->format('Y-m');

        $attendance = $this->attendance->getStudentAttendancesForStudent($user, $month);
        return Inertia::render('Attendance/Show', [
            'user' => $user,
            'attendance' => $attendance,
            'month' => $month,
        ]);
    }

    public function export(Request $request)
    {

        $request->validate([
            'month' => 'required',
            'user_id' => 'required',
        ]);


        $month = $request->input('month');
        $userId = $request->input('user_id');

        $user = User::findOrFail(   $userId);

        $userName = $user->first_name . ' ' . $user->last_name;

        return Excel::download(new AttendanceExport($month, $userId, $userName), 'attendance.xlsx');
    }

}

