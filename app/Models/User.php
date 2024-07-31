<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Document;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function documents()
    {
        return $this->belongsToMany(Document::class, 'student_document')
            ->withPivot('is_completed')
            ->withTimestamps();
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
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

    public function getStudentsForCoordinator(): Collection
    {
        $query = $this->student()
            ->orderBy('last_name')
            ->get();

        if ($this->isCoordinator()) {
            $query->where('course', $this->course);
        }

        return $query;
    }

    public function getProgress()
    {
        $totalDocuments = Document::count();
        $completedDocuments = $this->documents->filter(function ($document) {
            return $document->pivot->is_completed;
        })->count();

        return $totalDocuments > 0 ? ($completedDocuments / $totalDocuments) * 100 : 0;
    }

    public function getStudentUserWithProgress()
    {
        return $this->getStudentsForCoordinator()->map(function ($student) {
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
