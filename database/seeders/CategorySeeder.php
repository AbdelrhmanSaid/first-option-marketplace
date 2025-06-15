<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Category names to be seeded.
     */
    public array $categories = [
        'Analysis & Simulation',
        'Blocks',
        'Building Design',
        'Building MEP',
        'Building Simulation & Analysis',
        'Civil',
        'Construction',
        'Counting',
        'Diagramming',
        'Electrical/Electronics',
        'Estimating',
        'Facility Management',
        'Fonts',
        'Hatches',
        'Interior Design',
        'Landscape',
        'Learning',
        'Linetypes',
        'Mapping',
        'Materials',
        'Mechanical Design',
        'Mechanical Simulation & Analysis',
        'Scheduling & Productivity',
        'Schematics',
        'Structural',
        'Survey',
        'Translator',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ($this->categories as $category) {
            Category::create(['name' => $category]);
        }
    }
}
