<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewEquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $catLab = Category::where('slug', 'lab-komputer')->first();
        $catOlahraga = Category::where('slug', 'olahraga')->first();

        if (! $catLab || ! $catOlahraga) {
            $this->command->error('Kategori lab-komputer atau olahraga tidak ditemukan!');

            return;
        }

        $items = [
            // --- Perlengkapan Olahraga (Image 1) ---
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-TNS-01',
                'name' => 'Bola Tenis (Set)',
                'spec' => '1 set isi 4 bola, felt kuning, tekanan standar',
                'location' => 'Ruang Olahraga',
                'total_stock' => 10,
                'fine_per_day' => 2000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-TNS-02',
                'name' => 'Raket Tenis',
                'spec' => 'Rangka aluminium, senar nilon terpasang, grip karet',
                'location' => 'Ruang Olahraga',
                'total_stock' => 6,
                'fine_per_day' => 3000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-TMJ-01',
                'name' => 'Bet Tenis Meja',
                'spec' => 'Karet dua sisi, gagang kayu',
                'location' => 'Ruang Olahraga',
                'total_stock' => 4,
                'fine_per_day' => 1000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-TMJ-02',
                'name' => 'Bola Tenis Meja',
                'spec' => 'Bahan seluloid, diameter 40 mm, warna putih',
                'location' => 'Ruang Olahraga',
                'total_stock' => 4,
                'fine_per_day' => 1000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-NET-01',
                'name' => 'Net Permainan',
                'spec' => 'Jaring nilon dengan tali pengikat dan tiang tali',
                'location' => 'Ruang Olahraga',
                'total_stock' => 6,
                'fine_per_day' => 3000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-ATL-01',
                'name' => 'Bola Tolak Peluru',
                'spec' => 'Bola besi padat untuk latihan tolak peluru',
                'location' => 'Ruang Olahraga',
                'total_stock' => 6,
                'fine_per_day' => 3000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-ATL-02',
                'name' => 'Tongkat Estafet',
                'spec' => 'Pipa PVC ringan, panjang sekitar 30 cm',
                'location' => 'Ruang Olahraga',
                'total_stock' => 10,
                'fine_per_day' => 1000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-KBG-01',
                'name' => 'Lompat Tali',
                'spec' => 'Tali PVC dengan gagang plastik, panjang dapat diatur',
                'location' => 'Ruang Olahraga',
                'total_stock' => 16,
                'fine_per_day' => 1000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-KBG-02',
                'name' => 'Dumbel 3 kg',
                'spec' => 'Besi berlapis karet, berat 3 kg per buah',
                'location' => 'Ruang Olahraga',
                'total_stock' => 3,
                'fine_per_day' => 3000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-KBG-03',
                'name' => 'Hula Hoop Bambu',
                'spec' => 'Lingkaran bambu, diameter sekitar 70 cm',
                'location' => 'Ruang Olahraga',
                'total_stock' => 6,
                'fine_per_day' => 2000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-MTR-01',
                'name' => 'Matras Senam',
                'spec' => 'Busa tipis anti slip, untuk senam lantai dan peregangan',
                'location' => 'Ruang Olahraga',
                'total_stock' => 35,
                'fine_per_day' => 1000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-MTR-02',
                'name' => 'Matras Tebal (Kasur)',
                'spec' => 'Busa tebal berlapis kulit sintetis, untuk guling dan lompat',
                'location' => 'Ruang Olahraga',
                'total_stock' => 6,
                'fine_per_day' => 3000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-TKD-01',
                'name' => 'Kick Shield Taekwondo',
                'spec' => 'Pelindung tendangan busa padat dengan pegangan tangan',
                'location' => 'Ruang Olahraga',
                'total_stock' => 3,
                'fine_per_day' => 3000,
                'max_days' => 3,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],

            // --- Lab RPL (Image 2) ---
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-FSD-01',
                'name' => 'Flashdisk',
                'spec' => 'USB Flash Drive untuk penyimpanan dan transfer data',
                'location' => 'Lab RPL',
                'total_stock' => 10,
                'fine_per_day' => 1000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-PRY-01',
                'name' => 'Proyektor',
                'spec' => 'Proyektor presentasi, termasuk kabel HDMI/VGA dan kabel daya',
                'location' => 'Lab RPL',
                'total_stock' => 3,
                'fine_per_day' => 5000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-MTR-01',
                'name' => 'Monitor',
                'spec' => 'Monitor komputer layar datar, termasuk kabel daya dan kabel display',
                'location' => 'Lab RPL',
                'total_stock' => 3,
                'fine_per_day' => 5000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-OBG-01',
                'name' => 'Obeng Mini (Set)',
                'spec' => '1 set obeng presisi berbagai ukuran mata obeng untuk perakitan perangkat',
                'location' => 'Lab RPL',
                'total_stock' => 1,
                'fine_per_day' => 1000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-ARD-01',
                'name' => 'Arduino Uno',
                'spec' => 'Board mikrokontroler ATmega328P beserta kabel USB',
                'location' => 'Lab RPL',
                'total_stock' => 30,
                'fine_per_day' => 2000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-SLD-01',
                'name' => 'Solder Listrik',
                'spec' => 'Solder tangan listrik untuk perakitan dan perbaikan elektronik',
                'location' => 'Lab RPL',
                'total_stock' => 1,
                'fine_per_day' => 2000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-BOR-01',
                'name' => 'Bor Tembok',
                'spec' => 'Mesin bor listrik dampak (impact drill) untuk melubangi tembok',
                'location' => 'Lab RPL',
                'total_stock' => 1,
                'fine_per_day' => 3000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-BOR-02',
                'name' => 'Bor Mini Listrik',
                'spec' => 'Bor listrik ukuran mini untuk pengeboran PCB dan komponen presisi',
                'location' => 'Lab RPL',
                'total_stock' => 1,
                'fine_per_day' => 2000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-SPK-01',
                'name' => 'Speaker Aktif',
                'spec' => 'Speaker pengeras suara untuk keperluan praktikum dan presentasi',
                'location' => 'Lab RPL',
                'total_stock' => 1,
                'fine_per_day' => 3000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'RPL-SND-01',
                'name' => 'Mikrofon & Sound System',
                'spec' => 'Paket mikrofon beserta perangkat pengeras suara (sound system) portabel',
                'location' => 'Lab RPL',
                'total_stock' => 1,
                'fine_per_day' => 5000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
        ];

        DB::transaction(function () use ($items) {
            $insertedCount = 0;
            $skippedCount = 0;

            foreach ($items as $itemData) {
                // Check if tool with same code already exists
                $existingByCode = Tool::where('code', $itemData['code'])->first();
                if ($existingByCode) {
                    $this->command->info("Lewati {$itemData['name']} (Kode {$itemData['code']} sudah ada).");
                    $skippedCount++;

                    continue;
                }

                // Check if tool with same name already exists in same category
                $existingByName = Tool::where('category_id', $itemData['category_id'])
                    ->whereRaw('LOWER(name) = ?', [strtolower($itemData['name'])])
                    ->first();
                if ($existingByName) {
                    $this->command->info("Lewati {$itemData['name']} (Nama alat sudah ada di kategori).");
                    $skippedCount++;

                    continue;
                }

                // Create tool
                $tool = Tool::create([
                    'category_id' => $itemData['category_id'],
                    'code' => strtoupper($itemData['code']),
                    'name' => $itemData['name'],
                    'spec' => $itemData['spec'],
                    'location' => $itemData['location'],
                    'total_stock' => $itemData['total_stock'],
                    'available_stock' => $itemData['total_stock'],
                    'fine_per_day' => $itemData['fine_per_day'],
                    'max_days' => $itemData['max_days'],
                    'image_gradient' => $itemData['image_gradient'],
                    'image' => null,
                ]);

                // Generate physical units
                for ($i = 1; $i <= $itemData['total_stock']; $i++) {
                    $unitCode = strtoupper($tool->code).'-U'.str_pad((string) $i, 2, '0', STR_PAD_LEFT);
                    ToolUnit::create([
                        'tool_id' => $tool->id,
                        'unit_code' => $unitCode,
                        'status' => 'tersedia',
                        'condition' => 'baik',
                        'notes' => 'Unit perdana terdata',
                    ]);
                }

                $insertedCount++;
                $this->command->info("Berhasil tambah {$tool->name} ({$tool->code}) dengan {$tool->total_stock} unit.");
            }

            $this->command->info("Selesai! Ditambahkan: {$insertedCount}, Dilewati: {$skippedCount}");
        });
    }
}
