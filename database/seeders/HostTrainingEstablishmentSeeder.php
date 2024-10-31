<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HostTrainingEstablishment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HostTrainingEstablishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HostTrainingEstablishment::factory()->count(10)->create();
    }
}
