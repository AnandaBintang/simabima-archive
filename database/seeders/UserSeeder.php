<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\OrganizationUnit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $firstUnit = OrganizationUnit::first();

        // 1 Administrator
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@simabima.com',
            'password' => Hash::make('password'),
            'role' => UserRole::Administrator,
            'organization_unit_id' => $firstUnit?->id,
            'jabatan' => 'Administrator Sistem',
            'unit_kerja' => 'IT',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);

        // 3 Staff
        $units = OrganizationUnit::where('type', 'unit')->take(3)->get();

        $staffData = [
            [
                'name' => 'Staff Satu',
                'username' => 'staff1',
                'email' => 'staff1@simabima.com',
                'jabatan' => 'Staf Administrasi',
            ],
            [
                'name' => 'Staff Dua',
                'username' => 'staff2',
                'email' => 'staff2@simabima.com',
                'jabatan' => 'Staf Keuangan',
            ],
            [
                'name' => 'Staff Tiga',
                'username' => 'staff3',
                'email' => 'staff3@simabima.com',
                'jabatan' => 'Staf Kepegawaian',
            ],
        ];

        foreach ($staffData as $index => $data) {
            User::create(array_merge($data, [
                'password' => Hash::make('password'),
                'role' => UserRole::Staff,
                'organization_unit_id' => $units[$index]?->id ?? $firstUnit?->id,
                'unit_kerja' => $units[$index]?->name ?? 'Umum',
                'phone' => '08123456789' . ($index + 1),
                'email_verified_at' => now(),
            ]));
        }
    }
}
