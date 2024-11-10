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

    public static function getJournalsForUser($user)
    {
        return $user->journals()->orderBy('created_at', 'desc')->get();
    }

    public static function getStudentUserJournalsForCoordinator($user, $classBlock = null)
    {
        $query = User::where('role', 'student')
            ->where('course', $user->course)
            ->orderBy('last_name');

        if ($classBlock) {
            $query->where('block', $classBlock);
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
        $journals = self::getStudentJournalsForCoordinator($user)->load('user');

        $latestJournals = $journals->sortByDesc('created_at')->take(5);

        $latestJournalsWithUsername = $latestJournals->map(function ($journal) {
            if ($journal->user) {
                $journal->username = $journal->user->first_name . ' ' . $journal->user->last_name;
            } else {
                $journal->username = 'Unknown';
            }

            return $journal;
        });

        return $latestJournalsWithUsername;
    }

    public static function getUnreviewedJournalsForCoordinator($user)
    {
        $journals = self::getStudentJournalsForCoordinator($user)->load('user');

        $unreviewedJournals = $journals
            ->where('reviewed', false);

        $journalsWithUsername = $unreviewedJournals->map(function ($journal) {
            if ($journal->user) {
                $journal->username = $journal->user->first_name . ' ' . $journal->user->last_name;
            } else {
                $journal->username = 'Unknown';
            }

            return $journal;
        });

        return $journalsWithUsername;
    }

    public static function getStudentJournalsForCoordinator($user, $week = null, $classBlock = null)
    {
        $query = self::with('user')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('role', 'student')
                    ->where('course', $user->course)
                    ->orderBy('last_name');
            });

        if ($classBlock) {
            $query->whereHas('user', function ($query) use ($classBlock) {
                $query->where('block', $classBlock);
            });
        }
        $journals = $query->with('user')->get();
        $journalsWithUsername = $journals->map(function ($journal) {
            if ($journal->user) {
                $journal->username = $journal->user->first_name . ' ' . $journal->user->last_name;
            } else {
                $journal->username = 'Unknown';
            }
            return $journal;
        });
        return $journalsWithUsername;
    }
}








