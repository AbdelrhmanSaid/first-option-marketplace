<?php

namespace Database\Seeders;

use App\Models\Discipline;
use Illuminate\Database\Seeder;

class DisciplineSeeder extends Seeder
{
    /**
     * Discipline names to be seeded.
     */
    public array $disciplines = [
        'Structural',
        'Architectural',
        'MEP (Mechanical, Electrical, Plumbing)',
        'Infrastructure',
        'Civil Engineering',
        'Environmental Engineering',
        'Geotechnical Engineering',
        'Transportation Engineering',
        'Hydraulic Engineering',
        'Fire Protection Engineering',
        'Acoustic Engineering',
        'Lighting Design',
        'HVAC Design',
        'Electrical Systems',
        'Plumbing Systems',
        'Telecommunications',
        'Security Systems',
        'Vertical Transportation',
        'Landscape Architecture',
        'Urban Planning',
        'Site Development',
        'Sustainability Design',
        'Building Information Modeling (BIM)',
        'Construction Management',
        'Project Management',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->disciplines as $discipline) {
            Discipline::create(['name' => $discipline]);
        }
    }
}
