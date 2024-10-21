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
            }
        } catch (Exception $e) {
            Log::error('Error ' . $e->getMessage());
        }
    }

    public function studentView($user)
    {
        $documents = Document::getDocumentsForUser($user);
        $progress = $this->user->getProgressForStudent($user);
        $journals = Journal::getJournalsForUser($user->id);
        $attendance = $this->attendance->getStudentAttendancesForStudent($user);
        $latestJournal = Journal::getLatestJournalForUser($user->id);
        $latestAttendance = $this->attendance->getLatestStudentAttendance($user->id);

        return Inertia::render('Student/StudentView', [
            'documents' => $documents,
            'progress' => $progress,
            'journalsCount' => $journals->count(),
            'attendanceCount' => $attendance->count(),
            'latestJournal' => $latestJournal,
            'latestAttendance' => $latestAttendance,
        ]);
    }

    public function coordinatorView($user, $classBlock, $classBlocks)
    {

        $dateToCheck = Carbon::today();

        $studentUserWithProgress = $user->getStudentUserWithProgress($classBlock);
        $latestJournals = Journal::getLatestStudentJournalForCoordinator($user);
        $usersCount = $user->getStudentUserWithProgress()->count();
        $attendancesTodayCount = $this->attendance->getStudentAttendancesForCoordinator($user, $dateToCheck)->count();
        $unreviewedJournals = Journal::getUnreviewedJournalsForCoordinator($user)->count();



        return Inertia::render('Coordinator/CoordinatorView', [
            'users' => $studentUserWithProgress,
            'user' => $user,
            'latestJournals' => $latestJournals,
            'classBlocks' => $classBlocks,
            'classBlock' => $classBlock,
            'usersCount' =>   $usersCount,
            'attendancesTodayCount' => $attendancesTodayCount,
            'unreviewedJournals' =>   $unreviewedJournals
        ]);
    }
}
