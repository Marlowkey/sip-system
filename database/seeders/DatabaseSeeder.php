<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Document;
use Illuminate\Database\Seeder;
use Database\Seeders\DocumentSeeder;
use Database\Seeders\AttendanceSeeder;
use Database\Seeders\UserSchoolYearSeeder;
use Database\Seeders\HostTrainingEstablishmentSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(30)->create();

        $this->call([
         AttendanceSeeder::class,
         DocumentSeeder::class,
         HostTrainingEstablishmentSeeder::class,
         UserSchoolYearSeeder::class]);
    }
}
