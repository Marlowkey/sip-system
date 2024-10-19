<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Journal extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getJournalsForUser(int $userId, int $week = null, string $classBlock = null)
    {
        $user = User::findOrFail($userId);
        if ($user->role === 'student') {
            return $user->journals()->orderBy('created_at', 'desc')->get();
        } else {
            return self::getStudentJournalsForCoordinator($user, $week, $classBlock);
        }
    }

    public static function getStudentJournalsForCoordinator($user, $week = null, $classBlock = null)
    {
        $query = self::with([
            'user' => function ($query) use ($user) {
                $query->where('role', 'student');
                $query->where('course', $user->course);
                $query->orderBy('last_name');
            }
        ]);

        if ($week !== null) {
            $query->where('week', $week);
        }

        if ($classBlock) {
            $query->whereHas('user', function ($query) use ($classBlock) {
                $query->where('block', $classBlock);
            });
        }
        return $query->get();
    }

    public static function getLatestJournalForUser(int $userId)
    {
        $user = User::findOrFail($userId);
        if ($user->role === 'student') {
            return $user->journals()->orderBy('created_at', 'desc')->first();
        } else {
            return null;
        }
    }

    public static function getLatestStudentJournalForCoordinator($user)
    {
        $journals = self::getStudentJournalsForCoordinator($user);

        $latestJournals = $journals->sortByDesc('created_at')->take(5); // No need to call get() on a collection

        return $latestJournals;
    }
}
