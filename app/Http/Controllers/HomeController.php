<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Document;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
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
        $documents = Document::getDocumentsForUser($user->id);
        return Inertia::render('Student/StudentView', [
            'documents' => $documents
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
