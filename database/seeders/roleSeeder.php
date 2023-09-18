<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\role;

class roleSeeder extends Seeder
{
    public function run(): void
    {
        role::where('name', '!=', 50)->delete();
        role::create([
            "name" => "Admin",
        ]);
        role::create([
            "name" => "Teacher",
        ]);
        role::create([
            "name" => "Student",
        ]);
    }
}
