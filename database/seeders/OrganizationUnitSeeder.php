<?php

namespace Database\Seeders;

use App\Models\OrganizationUnit;
use Illuminate\Database\Seeder;

class OrganizationUnitSeeder extends Seeder
{
    public function run(): void
    {
        $hierarchy = [
            // ── SEKRETARIAT ──────────────────────────────────────────────────
            [
                'name'     => 'Sekretariat',
                'type'     => 'group',
                'order'    => 1,
                'children' => [
                    ['name' => 'Unit Kearsipan',           'order' => 1],
                    ['name' => 'Unit Gudang',               'order' => 2],
                    ['name' => 'PPID dan Kehumasan',        'order' => 3],
                    ['name' => 'Kepegawaian',               'order' => 4],
                    ['name' => 'Unit Keuangan',             'order' => 5],
                    ['name' => 'Unit Penyusunan Program',   'order' => 6],
                    ['name' => 'Unit IT',                   'order' => 7],
                ],
            ],

            // ── BIDANG ───────────────────────────────────────────────────────
            [
                'name'     => 'Bidang',
                'type'     => 'group',
                'order'    => 2,
                'children' => [
                    ['name' => 'Bidang Pengaturan Pengendalian',     'order' => 1],
                    ['name' => 'Bidang Bina Teknik',                 'order' => 2],
                    ['name' => 'Bidang Bina Pemeliharaan',           'order' => 3],
                    ['name' => 'Bidang Pembangunan dan Peningkatan', 'order' => 4],
                ],
            ],

            // ── UPT ──────────────────────────────────────────────────────────
            [
                'name'     => 'UPT',
                'type'     => 'group',
                'order'    => 3,
                'children' => [
                    [
                        'name'         => 'UPT Surabaya',
                        'order'        => 1,
                        'sub_children' => [
                            ['name' => 'Sub Bagian TU',           'order' => 1],
                            ['name' => 'Sub Bagian Pemeliharaan', 'order' => 2],
                            ['name' => 'Sub Bagian Pembangunan',  'order' => 3],
                        ],
                    ],
                    [
                        'name'         => 'UPT Mojokerto',
                        'order'        => 2,
                        'sub_children' => [
                            ['name' => 'Sub Bagian TU',           'order' => 1],
                            ['name' => 'Sub Bagian Pemeliharaan', 'order' => 2],
                            ['name' => 'Sub Bagian Pembangunan',  'order' => 3],
                        ],
                    ],
                    [
                        'name'         => 'UPT Kota',
                        'order'        => 3,
                        'sub_children' => [
                            ['name' => 'Sub Bagian TU',           'order' => 1],
                            ['name' => 'Sub Bagian Pemeliharaan', 'order' => 2],
                            ['name' => 'Sub Bagian Pembangunan',  'order' => 3],
                        ],
                    ],
                    [
                        'name'         => 'UPT Laboratorium',
                        'order'        => 4,
                        'sub_children' => [
                            ['name' => 'Sub Bagian TU',  'order' => 1],
                            ['name' => 'Seksi Pengujian', 'order' => 2],
                            ['name' => 'Seksi Mutu',      'order' => 3],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($hierarchy as $groupData) {
            $group = OrganizationUnit::create([
                'name'      => $groupData['name'],
                'type'      => 'group',
                'parent_id' => null,
                'order'     => $groupData['order'],
            ]);

            foreach ($groupData['children'] as $unitData) {
                $unit = OrganizationUnit::create([
                    'name'      => $unitData['name'],
                    'type'      => 'unit',
                    'parent_id' => $group->id,
                    'order'     => $unitData['order'],
                ]);

                // UPT units have sub_children (sub-bagian)
                if (!empty($unitData['sub_children'])) {
                    foreach ($unitData['sub_children'] as $subData) {
                        OrganizationUnit::create([
                            'name'      => $subData['name'],
                            'type'      => 'sub_unit',
                            'parent_id' => $unit->id,
                            'order'     => $subData['order'],
                        ]);
                    }
                }
            }
        }
    }
}
