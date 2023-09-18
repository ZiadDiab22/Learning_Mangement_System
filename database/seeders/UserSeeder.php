<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\user;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::where('name', '!=', 50)->delete();
        // 4 Teachers , 8 Users , 1 Admis
        User::create([
            "name" => "Ziad",
            "email" => "zd@gmail.com",
            "password" => bcrypt(111000),
            "phone_no" => "0938",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 1,
            "blocked" => false
        ]);
        User::create([
            "name" => "Ahmad",
            "email" => "aa@gmail.com",
            "password" => bcrypt(100),
            "phone_no" => "0901",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 2,
            "blocked" => false
        ]);
        User::create([
            "name" => "Youssef",
            "email" => "ys@gmail.com",
            "password" => bcrypt(200),
            "phone_no" => "0902",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 2,
            "blocked" => false
        ]);
        User::create([
            "name" => "Omar",
            "email" => "om@gmail.com",
            "password" => bcrypt(300),
            "phone_no" => "0903",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 2,
            "blocked" => false
        ]);
        User::create([
            "name" => "Bahaa",
            "email" => "bh@gmail.com",
            "password" => bcrypt(400),
            "phone_no" => "0904",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 2,
            "blocked" => false
        ]);
        User::create([
            "name" => "Obada",
            "email" => "ob@gmail.com",
            "password" => bcrypt(500),
            "phone_no" => "0905",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 3,
            "blocked" => false
        ]);
        User::create([
            "name" => "Waheed",
            "email" => "ww@gmail.com",
            "password" => bcrypt(600),
            "phone_no" => "0906",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 3,
            "blocked" => false
        ]);
        User::create([
            "name" => "Rami",
            "email" => "rm@gmail.com",
            "password" => bcrypt(700),
            "phone_no" => "0907",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 3,
            "blocked" => false
        ]);
        User::create([
            "name" => "Abood",
            "email" => "aab@gmail.com",
            "password" => bcrypt(800),
            "phone_no" => "0908",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 3,
            "blocked" => false
        ]);
        User::create([
            "name" => "Wissam",
            "email" => "ws@gmail.com",
            "password" => bcrypt(900),
            "phone_no" => "0909",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 3,
            "blocked" => false
        ]);
        User::create([
            "name" => "Ali",
            "email" => "all@gmail.com",
            "password" => bcrypt(333),
            "phone_no" => "0910",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 3,
            "blocked" => false
        ]);
        User::create([
            "name" => "Mohamed",
            "email" => "mm@gmail.com",
            "password" => bcrypt(221),
            "phone_no" => "0911",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 3,
            "blocked" => false
        ]);
        User::create([
            "name" => "Jawad",
            "email" => "jw@gmail.com",
            "password" => bcrypt(556),
            "phone_no" => "0912",
            "img_url" => "img-113223",
            "badget" => 0,
            "role_id" => 3,
            "blocked" => false
        ]);
    }
}
