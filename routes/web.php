<?php

use App\Models\User;
use Inertia\Inertia;
use App\Models\Document;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\StudentDocumentController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::middleware(['auth:sanctum', 'verified'])->get('/', function () {
    $user = auth()->user();
    $users = User::all();
    $documents = Document::with(['users' => function ($query) {
        $query->where('user_id', auth()->id());
    }])->get();

    return Inertia::render('HomeView', [
        'user' => $user,
        'users' => $users,
        'documents' => $documents,
    ]);})->name('home');

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

