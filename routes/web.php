<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentDocumentController;
use BotMan\BotMan\BotMan;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/home',[HomeController::class, 'index'])->name('home');

Route::get('/tables', function () {
    return Inertia::render('TablesView');
})->name('tables');



Route::get('/error', function () {
    return Inertia::render('ErrorView');
})->name('error');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

});

Route::resource('documents', DocumentController::class);
Route::post('/student-document/completion', [StudentDocumentController::class, 'updateCompletionStatus'])->name('student-document.update');
Route::get('/documents/download/{document}', [DocumentController::class, 'download'])->name('documents.download');

Route::resource('attendances', AttendanceController::class);
Route::get('/student-attendance/{id}', [AttendanceController::class, 'showStudentAttendance'])->name('student-attendance.show');


Route::resource('/journals', JournalController::class);
Route::post('/journals/{id}/mark-reviewed', [JournalController::class, 'markAsReviewed'])->name('journals.markReviewed');
Route::post('/journals/{id}/feedback', [JournalController::class, 'addFeedback'])->name('journals.addFeedback');
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);

Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendances.export');

require __DIR__ . '/auth.php';
