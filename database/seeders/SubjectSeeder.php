<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\subject;

class SubjectSeeder extends Seeder
{

    public function run()
    {
        subject::where('name', '!=', 50)->delete();
        subject::create([
            "name" => "Programming",
        ]);
        subject::create([
            "name" => "Accounting",
        ]);
        subject::create([
            "name" => "Drawing",
        ]);
        subject::create([
            "name" => "Administration",
        ]);
        subject::create([
            "name" => "Copywriting",
        ]);
        subject::create([
            "name" => "Editing",
        ]);
        subject::create([
            "name" => "Photographing",
        ]);
        subject::create([
            "name" => "Graphic Designing",
        ]);
        subject::create([
            "name" => "Social Media Management",
        ]);
        subject::create([
            "name" => "Marketing",
        ]);
        subject::create([
            "name" => "Direction",
        ]);
        subject::create([
            "name" => "Acting",
        ]);
    }
}
