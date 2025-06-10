<?php

namespace Database\Seeders;

use App\Models\Software;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SoftwareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $softwares = [
            'AutoCAD',
            'Revit',
            'ACC & BIM360',
            'FormIt',
            'Forma',
            'Inventor',
            'Fusion',
            '3ds Max',
            'Maya',
            'Robot Structural Analysis',
            'Autodesk Advance Steel',
            'AutoCAD Architecture',
            'Autodesk Civil 3D',
            'AutoCAD MEP',
            'AutoCAD Mechanical',
            'AutoCAD Map 3D',
            'AutoCAD Electrical',
            'AutoCAD Plant 3D',
            'Alias',
            'Navisworks',
            'Vault',
            'Simulation',
        ];

        foreach ($softwares as $software) {
            Software::create([
                'name' => $software,
                'slug' => Str::slug($software),
            ]);
        }
    }
}
