<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Document::factory()->personalData()->create();
        Document::factory()->resume()->create();
        Document::factory()->medicalCertificate()->create();
        Document::factory()->schoolId()->create();
        Document::factory()->barangayClearance()->create();
        Document::factory()->internshipAgreement()->create();
        Document::factory()->liabilityWaiver()->create();
        Document::factory()->liabilityWaiverCovid()->create();
    }
}
