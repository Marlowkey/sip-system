<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Journal;
use App\Models\Document;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    protected User $user;
    protected Attendance $attendance;
    protected Journal $journal;



    public function __construct(User $user, Attendance $attendance, Journal $journal)
    {
        $this->user = $user;
        $this->attendance = $attendance;
        $this->journal = $journal;

    }
    public function index(Request $request)
    {
        $request->validate([
            'class_block' => 'nullable|string',
        ]);

        $classBlock = $request->class_block ?? null;
        $classBlocks = User::distinct()->pluck('block');

        try {
            $user = auth()->user();

            switch ($user->role) {
                case 'student':
                    return $this->studentView($user);
                case 'coordinator':
                    return $this->coordinatorView($user, $classBlock, $classBlocks);
                case 'admin':
                        return $this->adminView($user);
            }
        } catch (Exception $e) {
            Log::error('Error ' . $e->getMessage());
        }
    }

    public function studentView($user)
    {
        $documents = Document::getDocumentsForUser($user);
        $progress = $this->user->getProgressForStudent($user);
        $journals = Journal::getJournalsForUser($user);
        $attendanceCount = $this->attendance->getStudentAttendanceCountForStudent($user);
        $latestJournal = Journal::getLatestJournalForUser($user->id);
        $latestAttendance = $this->attendance->getLatestStudentAttendance($user->id);
        $placement = $user->hostTrainingEstablishment()->first();

        return Inertia::render('Student/StudentView', [
            'documents' => $documents,
            'progress' => $progress,
            'journalsCount' => $journals->count(),
            'attendanceCount' => $attendanceCount,
            'latestJournal' => $latestJournal,
            'latestAttendance' => $latestAttendance,
            'placement' => $placement->name ?? 'Not Assigned',

        ]);
    }

    public function coordinatorView($user, $classBlock, $classBlocks)
    {

        $dateToCheck = Carbon::today();
        $studentUserWithProgress = $user->getStudentUserWithProgress($classBlock);
        $usersCount = $user->getStudentUserWithProgress()->count();
        $studentAttendance = $this->attendance->getStudentAttendances($user, $dateToCheck);
        $unreviewedJournalsCount = Journal::getUnreviewedJournalsForCoordinator($user)->count();
        // $unreviewedJournals = Journal::getUnreviewedJournalsForCoordinator($user);

        return Inertia::render('Coordinator/CoordinatorView', [
            'users' => $studentUserWithProgress,
            'user' => $user,
            'classBlocks' => $classBlocks,
            'classBlock' => $classBlock,
            'usersCount' =>   $usersCount,
            'attendancesTodayCount' => $studentAttendance->count(),
            'unreviewedJournalsCount' =>   $unreviewedJournalsCount,
            // 'unreviewedJournals' => $unreviewedJournals,
        ]);
    }

    public function adminView($user)
    {
        return Inertia::render('Admin/AdminView');
    }

}
