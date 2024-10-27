<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $userId = 2;
        $year = now()->year;

        $months = [7, 8, 9, 10];

        foreach ($months as $month) {
            $startDate = Carbon::create($year, $month, 1);
            $endDate = $startDate->copy()->endOfMonth();

            for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
                Attendance::factory()->forUserOnDate($userId, $date->copy())->create();
            }
        }
    }
}

