<?php

namespace Database\Seeders;

use App\Models\ArchiveCategory;
use Illuminate\Database\Seeder;

class ArchiveCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Aktif', 'description' => 'Arsip yang masih digunakan secara aktif'],
            ['name' => 'Inaktif', 'description' => 'Arsip yang sudah tidak aktif digunakan'],
            ['name' => 'Vital', 'description' => 'Arsip penting yang harus dijaga keamanannya'],
            ['name' => 'Dimusnahkan', 'description' => 'Arsip yang telah dimusnahkan'],
            ['name' => 'Lainnya', 'description' => 'Kategori arsip lainnya'],
        ];

        foreach ($categories as $category) {
            ArchiveCategory::create($category);
        }
    }
}
