<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SchoolYear;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schoolYear = SchoolYear::where('year', '2024-2025')->first();

        if (!$schoolYear) {
            $schoolYear = SchoolYear::create([
                'year' => '2024-2025',
            ]);
        }

        $users = User::all();
        foreach ($users as $user) {
            $user->school_year_id = $schoolYear->id;
            $user->save();
        }

    }
}
