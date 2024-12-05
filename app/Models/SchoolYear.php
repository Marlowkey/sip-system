<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SchoolYear extends Model
{
    use HasFactory;
    protected $guarded = [];


    public function users()
    {
        return $this->hasMany(User::class, 'school_year_id');
    }

    public function activate()
    {
        self::query()->update(['is_active' => false]);

        $this->update(['is_active' => true]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);

        $this->users()->update(['active' => false]);
    }
}
