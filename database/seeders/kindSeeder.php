<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\kind;

class kindSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        kind::where('name', '!=', 50)->delete();
        kind::create([
            "name" => "Laravel",
            "subject_id" => 1,
        ]);
        kind::create([
            "name" => "Python",
            "subject_id" => 1,
        ]);
        kind::create([
            "name" => "Photoshop",
            "subject_id" => 6,
        ]);
    }
}
