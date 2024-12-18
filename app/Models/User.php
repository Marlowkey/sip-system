<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Journal;
use App\Models\Document;
use App\Models\SchoolYear;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'student_document')
            ->withPivot('is_completed', 'file_path')
            ->withTimestamps();
    }

    public function hostTrainingEstablishment()
    {
        return $this->belongsTo(HostTrainingEstablishment::class, 'host_training_establishment_id');
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isCoordinator()
    {
        return $this->role === 'coordinator';
    }

    public function scopeStudent(Builder $query): Builder
    {
        return $query->where('role', 'student');
    }

    public function getStudentsForCoordinator()
    {
        return $this->student()
            ->orderBy('last_name')
            ->where('course', $this->course);
    }

    public function getProgress()
    {
        $totalDocuments = Document::count();
        $completedDocuments = $this->documents
            ->filter(function ($document) {
                return $document->pivot->is_completed;
            })
            ->count();

        return $totalDocuments > 0 ? ($completedDocuments / $totalDocuments) * 100 : 0;
    }

    public function getProgressForStudent($user)
    {
        return $user->getProgress();
    }

    public function getStudentUserWithProgress($classBlock = null)
    {
        $query = $this->getStudentsForCoordinator();

        if ($classBlock) {
            $query->where('block', $classBlock);
        }

        $students = $query->get();

        return $students->map(function ($student) {
            return [
                'id' => $student->id,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'block' => $student->block,
                'course' => $student->course,
                'host_training_establishment' => $student->host_training_establishment,
                'progress' => $student->getProgress(),
            ];
        });
    }
}
