<?php

namespace App\Http\Controllers;

use Exception;
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


    public function __construct(User $user, Attendance $attendance)
    {
        $this->user = $user;
        $this->attendance = $attendance;

    }
    public function index()
    {
        try {
            $user = auth()->user();

            switch ($user->role) {
                case 'student':
                    return $this->studentView($user);
                case 'coordinator':
                    return $this->coordinatorView($user);
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

        return Inertia::render('Student/StudentView', [
            'documents' => $documents,
            'progress' => $progress,
            'journalsCount' => $journals->count(),
            'attendanceCount' => $attendance->count(),
        ]);
    }

    public function coordinatorView($user)
    {
        $studentUserWithProgress = $user->getStudentUserWithProgress();

        return Inertia::render('Coordinator/CoordinatorView', [
            'users' => $studentUserWithProgress,
            'user' => $user,
        ]);
    }
}
