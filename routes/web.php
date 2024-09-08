<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\StudentDocumentController;

Route::middleware(['auth:sanctum', 'verified'])->get('/',[HomeController::class, 'index'])->name('home');

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
});

Route::resource('documents', DocumentController::class);
Route::post('/student-document/completion', [StudentDocumentController::class, 'updateCompletionStatus'])->name('student-document.update');
Route::get('/documents/download/{document}', [DocumentController::class, 'download'])->name('documents.download');

Route::resource('attendances', AttendanceController::class);
Route::get('/student-attendance/{id}', [AttendanceController::class, 'showStudentAttendance'])->name('student-attendance.show');


Route::resource('/journals', JournalController::class);
require __DIR__ . '/auth.php';
