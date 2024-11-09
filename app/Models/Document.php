<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PHPUnit\Runner\DeprecationCollector\Collector;

class Document extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'student_document')
            ->withPivot('is_completed', 'file_path')
            ->withTimestamps();
    }

    public static function getDocumentsForUser($id)
    {
        return self::with([
            'users' => function ($query) use ($id) {
                $query->where('user_id', $id);
            }
        ])->get();
    }

    public function getDocumentsWithNumberOfCompleted($user)
    {
        return $this->getAllDocuments()->map(function ($document) use ($user) {
            return [
                'id' => $document->id,
                'title' => $document->title,
                'description' => $document->description,
                'file_path' => $document->file_path,
                'due_date' => $document->due_date,
                'completed' => $document->getCompletedDocuments($user),
                'number_of_users' => $user->getStudentsForCoordinator()->count(),
            ];
        });
    }

    public function getCompletedDocuments($user)
    {
        return
            $this->users->filter(function ($user) {
                return $user->pivot->is_completed;
            })
                ->where('course', $user->course)
                ->count();
    }


    public function getAllDocuments()
    {
        return self::all();
    }
}
