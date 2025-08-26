<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@htd.edu.vn',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        User::create([
            'name' => 'Giảng viên A',
            'email' => 'giangvien@htd.edu.vn',
            'password' => Hash::make('123456'),
            'role' => 'teacher',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        User::create([
            'name' => 'Sinh viên B',
            'email' => 'sinhvien@htd.edu.vn',
            'password' => Hash::make('123456'),
            'role' => 'student',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}
