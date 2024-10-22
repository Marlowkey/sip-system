<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'due_date' => $this->faker->optional()->date,
            'file_path' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

        /**
     * Define specific documents.
     */
    public function personalData(): self
    {
        return $this->state([
            'title' => 'Personal Data (with Passport Size picture)',
            'description' => 'Includes Passport Size picture in formal attire, light blue background.',
        ]);
    }

    public function resume(): self
    {
        return $this->state([
            'title' => 'CICT FORMAT Resume (with Passport Size picture)',
            'description' => 'Includes attached certificates of training, seminars, and awards.',
        ]);
    }

    public function medicalCertificate(): self
    {
        return $this->state([
            'title' => 'Medical Certificate (requested in CatSU clinic)',
            'description' => 'Includes attached photocopy of vaccine card.',
        ]);
    }

    public function schoolId(): self
    {
        return $this->state([
            'title' => 'Photocopy of latest School ID',
            'description' => 'A scanned copy of the school ID.',
        ]);
    }

    public function barangayClearance(): self
    {
        return $this->state([
            'title' => 'Photocopy of Barangay Clearance',
            'description' => 'A scanned copy of the Barangay Clearance.',
        ]);
    }

    public function internshipAgreement(): self
    {
        return $this->state([
            'title' => 'Notarized Internship Agreement (Parental Consent)',
            'description' => 'A notarized document for parental consent.',
        ]);
    }

    public function liabilityWaiver(): self
    {
        return $this->state([
            'title' => 'Waiver of Liability Agreement',
            'description' => 'A document waiving liability for the internship program.',
        ]);
    }

    public function liabilityWaiverCovid(): self
    {
        return $this->state([
            'title' => 'Waiver of Liability Agreement COVID-19',
            'description' => 'A waiver specific to COVID-19 liability.',
        ]);
    }
}
