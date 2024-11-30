<?php

use Inertia\Inertia;
use BotMan\BotMan\BotMan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BotManController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\StudentDocumentController;
use App\Http\Controllers\HostTrainingEstablishmentController;



Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/home',[HomeController::class, 'index'])->name('home');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

});

Route::resource('documents', DocumentController::class);
Route::post('/student-document/completion', [StudentDocumentController::class, 'updateCompletionStatus'])->name('student-document.update');
Route::get('/documents/download/{document}', [DocumentController::class, 'download'])->name('documents.download');
Route::post('/documents/{document}/upload', [DocumentController::class, 'uploadDocument'])->name('documents.upload');
Route::get('student-documents/download/{document}/{user}', [DocumentController::class, 'downloadDocument'])->name('student-documents.download');
Route::post('/documents/{id}/update-file', [DocumentController::class, 'updateFile'])->name('documents.updateFile');


Route::resource('attendances', AttendanceController::class);
Route::get('/student-attendance/{id}', [AttendanceController::class, 'showStudentAttendance'])->name('student-attendance.show');


Route::resource('/journals', JournalController::class);
Route::post('/journals/{id}/mark-reviewed', [JournalController::class, 'markAsReviewed'])->name('journals.markReviewed');
Route::post('/journals/{id}/feedback', [JournalController::class, 'addFeedback'])->name('journals.addFeedback');
Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);
Route::get('/student/journal/{id}', [JournalController::class, 'showStudentJournal'])->name('student.journal.show');

Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendances.export');


Route::prefix('users')->group(function () {
    Route::get('/student', [UserController::class, 'indexStudent'])->name('users-student.index');
    Route::get('/coordinator', [UserController::class, 'indexCoordinator'])->name('users-coordinator.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');

});

Route::prefix('htes')->name('htes.')->group(function () {
    Route::get('/', [HostTrainingEstablishmentController::class, 'index'])->name('index');
    Route::get('/create', [HostTrainingEstablishmentController::class, 'create'])->name('create');
    Route::post('/', [HostTrainingEstablishmentController::class, 'store'])->name('store');
    Route::get('/{id}', [HostTrainingEstablishmentController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [HostTrainingEstablishmentController::class, 'edit'])->name('edit');
    Route::put('/{id}', [HostTrainingEstablishmentController::class, 'update'])->name('update');
    Route::delete('/{id}', [HostTrainingEstablishmentController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/download-moa', [HostTrainingEstablishmentController::class, 'downloadMoa'])->name('downloadMoa');
    Route::post('/{id}/update-moa-file', [HostTrainingEstablishmentController::class, 'updateMoaFile'])
    ->name('updateMoaFile');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('schoolyears', SchoolYearController::class)->except(['show']);
});

Route::post('/users/{id}/update-hte', [UserController::class, 'updateHostTrainingEstablishment'])->name('users.update-hte');


require __DIR__ . '/auth.php';
