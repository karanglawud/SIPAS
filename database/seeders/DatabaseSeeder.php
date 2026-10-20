<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Loan;
use App\Models\Message;
use App\Models\Tool;
use App\Models\ToolUnit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $siswaAbror = User::create([
            'name' => 'Abror Abiyyi',
            'email' => 'abror@smkn5solo.sch.id',
            'password' => $password,
            'role' => 'siswa',
            'identifier' => '0081234567',
            'class_or_dept' => 'XII RPL B / 01',
            'phone' => '081234567890',
        ]);

        $siswaRafi = User::create([
            'name' => 'Rafi Ramadhan',
            'email' => 'rafi@smkn5solo.sch.id',
            'password' => $password,
            'role' => 'siswa',
            'identifier' => '0081234588',
            'class_or_dept' => 'XI TP A / 14',
            'phone' => '081298765432',
        ]);

        $guruDanang = User::create([
            'name' => 'Bpk. Danang S., S.Kom.',
            'email' => 'danang@smkn5solo.sch.id',
            'password' => $password,
            'role' => 'guru',
            'identifier' => '198501102010011005',
            'class_or_dept' => 'Guru Produktif RPL',
            'phone' => '081322334455',
        ]);

        $guruSri = User::create([
            'name' => 'Ibu Sri Wahyuni, S.Pd.',
            'email' => 'sri@smkn5solo.sch.id',
            'password' => $password,
            'role' => 'guru',
            'identifier' => '198802152014022003',
            'class_or_dept' => 'Guru Pembina OSIS',
            'phone' => '081355667788',
        ]);

        $adminBengkel = User::create([
            'name' => 'Bpk. Bambang S., S.T.',
            'email' => 'pj.bengkel@smkn5solo.sch.id',
            'password' => $password,
            'role' => 'admin',
            'identifier' => '197805122006041002',
            'class_or_dept' => 'PJ Alat Bengkel & Mesin',
            'category_scope' => 'bengkel',
            'phone' => '081211112222',
        ]);

        $adminLab = User::create([
            'name' => 'Ibu Rina Wahyuni, M.Kom.',
            'email' => 'pj.lab@smkn5solo.sch.id',
            'password' => $password,
            'role' => 'admin',
            'identifier' => '198204192008012015',
            'class_or_dept' => 'PJ Alat Lab RPL & Elektronika',
            'category_scope' => 'lab-komputer',
            'phone' => '081233334444',
        ]);

        $adminMultimedia = User::create([
            'name' => 'Bpk. Aditya Pratama, S.Sn.',
            'email' => 'pj.multimedia@smkn5solo.sch.id',
            'password' => $password,
            'role' => 'admin',
            'identifier' => '198907232015031004',
            'class_or_dept' => 'PJ Alat Multimedia & DKV',
            'category_scope' => 'multimedia',
            'phone' => '081255556666',
        ]);

        $adminOlahraga = User::create([
            'name' => 'Bpk. Haryanto, S.Pd.',
            'email' => 'pj.olahraga@smkn5solo.sch.id',
            'password' => $password,
            'role' => 'admin',
            'identifier' => '197509142002121003',
            'class_or_dept' => 'PJ Alat & Perlengkapan Olahraga',
            'category_scope' => 'olahraga',
            'phone' => '081277778888',
        ]);

        $catBengkel = Category::create([
            'slug' => 'bengkel',
            'name' => 'Bengkel & Mesin',
            'badge' => 'Teknik Mesin & Otomotif',
            'pj_name' => 'Bpk. Bambang S., S.T.',
            'pj_role_title' => 'Penanggung Jawab Alat Bengkel Otomotif',
            'description' => 'Peralatan mekanik berat, perkakas tangan, mesin las, dan alat servis presisi untuk praktik kejuruan.',
            'icon' => 'wrench',
            'color' => 'amber',
        ]);

        $catLab = Category::create([
            'slug' => 'lab-komputer',
            'name' => 'Lab RPL & Elektronika',
            'badge' => 'Rekayasa Perangkat Lunak & IoT',
            'pj_name' => 'Ibu Rina Wahyuni, M.Kom.',
            'pj_role_title' => 'Penanggung Jawab Alat Lab Komputer & IoT Kejuruan',
            'description' => 'Kit mikrokontroler, oscilloscope, solder station, router jaringan, dan perlengkapan praktikum rekayasa software.',
            'icon' => 'cpu',
            'color' => 'blue',
        ]);

        $catMultimedia = Category::create([
            'slug' => 'multimedia',
            'name' => 'Multimedia & Broadcast',
            'badge' => 'DKV & Broadcasting SMKN 5',
            'pj_name' => 'Bpk. Aditya Pratama, S.Sn.',
            'pj_role_title' => 'Penanggung Jawab Alat Studio Multimedia & Dokumentasi',
            'description' => 'Kamera mirrorless, stabilizer gimbal, lighting studio, wireless mic, dan perlengkapan produksi konten.',
            'icon' => 'camera',
            'color' => 'emerald',
        ]);

        $catOlahraga = Category::create([
            'slug' => 'olahraga',
            'name' => 'Perlengkapan Olahraga',
            'badge' => 'Olahraga & Ekskul SMKN 5',
            'pj_name' => 'Bpk. Haryanto, S.Pd.',
            'pj_role_title' => 'Penanggung Jawab Perlengkapan Olahraga',
            'description' => 'Bola basket, raket bulutangkis, matras senam, stopwatch digital, dan perlengkapan olahraga sekolah.',
            'icon' => 'trophy',
            'color' => 'rose',
        ]);

        // Attach admins to their respective category
        $catBengkel->admins()->attach($adminBengkel->id);
        $catLab->admins()->attach($adminLab->id);
        $catMultimedia->admins()->attach($adminMultimedia->id);
        $catOlahraga->admins()->attach($adminOlahraga->id);

        $toolsData = [
            [
                'category_id' => $catBengkel->id,
                'code' => 'BKL-LAS-01',
                'name' => 'Mesin Las Inverter MMA 160A',
                'spec' => 'Duty Cycle 60%, Input 220V, Elektroda 1.6 - 3.2mm, Include Topeng & Kabel Las',
                'location' => 'Bengkel Pengelasan Lt. 1',
                'total_stock' => 5,
                'available_stock' => 4,
                'fine_per_day' => 5000,
                'max_days' => 2,
                'image_gradient' => 'from-amber-500 to-orange-600',
            ],
            [
                'category_id' => $catBengkel->id,
                'code' => 'BKL-TOR-04',
                'name' => 'Kunci Torsi Digital 1/2 Inch (20-200 Nm)',
                'spec' => 'Akurasi ±2%, Buzzer & LED alert, sertifikat kalibrasi presisi mesin',
                'location' => 'Lemari Khusus Toolroom B',
                'total_stock' => 8,
                'available_stock' => 8,
                'fine_per_day' => 3000,
                'max_days' => 3,
                'image_gradient' => 'from-amber-600 to-yellow-600',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'LAB-OSC-02',
                'name' => 'Digital Storage Oscilloscope 100MHz 2CH',
                'spec' => 'Real-time sample rate 1GSa/s, Layar 7 Inch TFT Color, USB Storage',
                'location' => 'Lab Elektronika & IoT Lt. 2',
                'total_stock' => 6,
                'available_stock' => 5,
                'fine_per_day' => 5000,
                'max_days' => 3,
                'image_gradient' => 'from-blue-600 to-indigo-700',
            ],
            [
                'category_id' => $catLab->id,
                'code' => 'LAB-IOT-07',
                'name' => 'ESP32 IoT Development Trainer Kit & Sensor Box',
                'spec' => 'ESP32 Wi-Fi + BLE, 16 Modul Sensor Terintegrasi, Kabel Jumper, Breadboard',
                'location' => 'Lab RPL 1 Lemari C',
                'total_stock' => 20,
                'available_stock' => 20,
                'fine_per_day' => 2000,
                'max_days' => 5,
                'image_gradient' => 'from-cyan-500 to-blue-600',
            ],
            [
                'category_id' => $catMultimedia->id,
                'code' => 'MM-CAM-01',
                'name' => 'Sony Alpha A6400 Mirrorless 4K + Lens 18-135mm',
                'spec' => '24.2 MP APS-C, Real-time Eye AF, Rekaman 4K HDR, 2 Baterai + Tas + SD 64GB',
                'location' => 'Studio Broadcasting Lt. 2',
                'total_stock' => 4,
                'available_stock' => 3,
                'fine_per_day' => 10000,
                'max_days' => 2,
                'image_gradient' => 'from-emerald-500 to-teal-700',
            ],
            [
                'category_id' => $catMultimedia->id,
                'code' => 'MM-MIC-03',
                'name' => 'Wireless Lavalier Microphone DJI Mic Dual TX+RX',
                'spec' => 'Jangkauan 250m, Memori internal perekaman 14 jam, Charging Case portable',
                'location' => 'Studio Podcasting & DKV',
                'total_stock' => 4,
                'available_stock' => 4,
                'fine_per_day' => 5000,
                'max_days' => 2,
                'image_gradient' => 'from-teal-600 to-cyan-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-BSK-01',
                'name' => 'Bola Basket Molten GG7X Official Leather Size 7',
                'spec' => 'FIBA Approved, Premium Composite Leather 12 panel, grip presisi',
                'location' => 'Ruang Alat Olahraga',
                'total_stock' => 12,
                'available_stock' => 12,
                'fine_per_day' => 2000,
                'max_days' => 1,
                'image_gradient' => 'from-rose-500 to-red-700',
            ],
            [
                'category_id' => $catOlahraga->id,
                'code' => 'OLR-BDM-02',
                'name' => 'Set Raket Bulutangkis Yonex Carbonex + Shuttlecock',
                'spec' => 'Full Carbon Graphite Frame, Tarikan 24 Lbs, Include Cover Bag 1 Tabung Cock',
                'location' => 'Ruang Alat Olahraga',
                'total_stock' => 10,
                'available_stock' => 10,
                'fine_per_day' => 2000,
                'max_days' => 1,
                'image_gradient' => 'from-pink-500 to-rose-600',
            ],
        ];

        foreach ($toolsData as $data) {
            $tool = Tool::create($data);

            for ($i = 1; $i <= $tool->total_stock; $i++) {
                $unitCode = sprintf('%s-U%02d', $tool->code, $i);
                ToolUnit::create([
                    'tool_id' => $tool->id,
                    'unit_code' => $unitCode,
                    'status' => 'tersedia',
                    'condition' => 'baik',
                ]);
            }
        }

        // Sample Loan 1: Abror Abiyyi (Approved, Oscilloscope)
        $oscTool = Tool::where('code', 'LAB-OSC-02')->first();
        if ($oscTool) {
            $assignedUnit = ToolUnit::where('tool_id', $oscTool->id)->first();
            if ($assignedUnit) {
                $assignedUnit->update(['status' => 'dipinjam']);
                $oscTool->decrement('available_stock');
            }

            $loan1 = Loan::create([
                'ticket_number' => 'SIPAS-2026-081',
                'user_id' => $siswaAbror->id,
                'tool_id' => $oscTool->id,
                'quantity' => 1,
                'start_date' => Carbon::now()->format('Y-m-d'),
                'duration_days' => 3,
                'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'purpose' => 'Praktikum Pengujian Frekuensi Sinyal Sensor IoT di Lab RPL.',
                'status' => 'disetujui',
                'assigned_units' => [$assignedUnit ? $assignedUnit->unit_code : 'LAB-OSC-02-U01'],
                'approved_by' => $adminLab->id,
                'approved_at' => Carbon::now()->subHours(3),
                'pj_notes' => 'Disetujui. Harap gunakan probe asli dan laporkan jika display bermasalah.',
            ]);

            // Official message from Admin PJ Lab to Abror
            Message::create([
                'sender_id' => $adminLab->id,
                'recipient_id' => $siswaAbror->id,
                'category_id' => $catLab->id,
                'loan_id' => $loan1->id,
                'type' => 'approval',
                'message' => 'Permohonan peminjaman Oscilloscope (No. Tiket ' . $loan1->ticket_number . ') telah disetujui. Unit fisik: LAB-OSC-02-U01. Silakan ambil di Lab RPL & Elektronika Lt. 2 pada jam layanan peminjaman alat.',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(3),
            ]);

            Message::create([
                'sender_id' => $adminLab->id,
                'recipient_id' => $siswaAbror->id,
                'category_id' => $catLab->id,
                'loan_id' => $loan1->id,
                'type' => 'reminder',
                'message' => 'Pengingat: Batas pengembalian alat adalah tanggal ' . $loan1->end_date->format('d/m/Y') . ' pukul 15.30 WIB. Pastikan kabel probe dan adaptor tersimpan rapi di tas box.',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(1),
            ]);
        }

        // Sample Loan 2: Rafi Ramadhan (Pending, Mesin Las)
        $lasTool = Tool::where('code', 'BKL-LAS-01')->first();
        if ($lasTool) {
            $loan2 = Loan::create([
                'ticket_number' => 'SIPAS-2026-095',
                'user_id' => $siswaRafi->id,
                'tool_id' => $lasTool->id,
                'quantity' => 1,
                'start_date' => Carbon::now()->addDay()->format('Y-m-d'),
                'duration_days' => 2,
                'end_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'purpose' => 'Praktik Sambungan Plat Tumpul Posisi 1G di Bengkel Pengelasan.',
                'status' => 'menunggu',
            ]);

            Message::create([
                'sender_id' => $adminBengkel->id,
                'recipient_id' => $siswaRafi->id,
                'category_id' => $catBengkel->id,
                'loan_id' => $loan2->id,
                'type' => 'info',
                'message' => 'Pengajuan No. Tiket ' . $loan2->ticket_number . ' telah diterima. Sedang dilakukan pengecekan ketersediaan elektroda dan topeng las di ruang toolroom bengkel.',
                'is_read' => false,
                'created_at' => Carbon::now()->subMinutes(45),
            ]);
        }

        // Sample Loan 3: Guru Danang (Completed)
        $camTool = Tool::where('code', 'MM-CAM-01')->first();
        if ($camTool) {
            $loan3 = Loan::create([
                'ticket_number' => 'SIPAS-2026-042',
                'user_id' => $guruDanang->id,
                'tool_id' => $camTool->id,
                'quantity' => 1,
                'start_date' => Carbon::now()->subDays(4)->format('Y-m-d'),
                'duration_days' => 2,
                'end_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'purpose' => 'Dokumentasi Kunjungan Industri Guru & Siswa ke PT Solo Technopark.',
                'status' => 'selesai',
                'assigned_units' => ['MM-CAM-01-U01'],
                'approved_by' => $adminMultimedia->id,
                'approved_at' => Carbon::now()->subDays(4),
                'actual_return_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'return_condition' => 'baik',
                'pj_notes' => 'Dikembalikan tepat waktu, lensa dan sensor dalam kondisi bersih sempurna.',
            ]);

            Message::create([
                'sender_id' => $adminMultimedia->id,
                'recipient_id' => $guruDanang->id,
                'category_id' => $catMultimedia->id,
                'loan_id' => $loan3->id,
                'type' => 'info',
                'message' => 'Terima kasih, Pak Danang. Alat Sony Alpha A6400 (Tiket ' . $loan3->ticket_number . ') telah diverifikasi kembali dalam kondisi BAIK.',
                'is_read' => true,
                'created_at' => Carbon::now()->subDays(2),
            ]);
        }
    }
}
