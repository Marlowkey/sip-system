<?php

use Illuminate\Support\Facades\Log;use App\Models\User;
use Inertia\Inertia;
use App\Models\Document;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\StudentDocumentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    try {
        // Get the currently authenticated user
        $user = auth()->user();

        $studentUsers =User::where('role', 'student')
        ->orderBy('last_name')
        ->get();

        $studentUserWithProgress = $studentUsers->map(function ($student) {
            $totalDocuments = Document::count();
            $completedDocuments = $student->documents->filter(function ($document) {
                return $document->pivot->is_completed;
            })->count();
            $progress = $totalDocuments > 0 ? ($completedDocuments / $totalDocuments) * 100 : 0;

            return [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'year_level' => $student->year_level,
                'course' => $student->course,
                'host_training_establishment' => $student->host_training_establishment,
                'progress' => $progress,
            ];
        });




        $documents = Document::with(['users' => function ($query) {
            $query->where('user_id', auth()->id());
        }])->get();

        // Initialize data arrays
        $studentViewData = [
            'documents' => $documents
        ];

        $coordinatorViewData = [
            'users' => $studentUserWithProgress,
        ];

        // Prepare data based on user role
        switch ($user->role) {
            case 'admin':
                return Inertia::render('Admin/AdminView');
            case 'student':
                return Inertia::render('Student/StudentView', $studentViewData);
            case 'coordinator':
                return Inertia::render('Coordinator/CoordinatorView', $coordinatorViewData);
            default:
                abort(403, 'Unauthorized action.');
        }
    } catch (\Exception $e) {
        // Log the exception for debugging
        Log::error('Error rendering home page: ' . $e->getMessage());
        abort(500, 'Internal Server Error');
    }
})->name('home');

Route::get('/tables', function () {
    return Inertia::render('TablesView');
})->name('tables');

Route::get('/forms', function () {
    return Inertia::render('FormsView');
})->name('forms');


Route::get('/error', function () {
    return Inertia::render('ErrorView');
})->name('error');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('documents', DocumentController::class);
Route::post('/student-document/completion', [StudentDocumentController::class, 'updateCompletionStatus'])->name('student-document.update');


require __DIR__.'/auth.php';

