<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'student_document')
                    ->withPivot('is_completed')
                    ->withTimestamps();
    }

    public static function getDocumentsForUser($id)
    {
        return self::with(['users' => function ($query) use($id) {
            $query->where('user_id', $id);
        }])->get();
    }
}
