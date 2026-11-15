# SIPAS

**Sistem Informasi Peminjaman Alat Sekolah**: aplikasi web untuk mengelola peminjaman alat dan perlengkapan sekolah, mulai dari katalog, pengajuan, persetujuan penanggung jawab (PJ), serah terima, sampai pengembalian dan perhitungan denda.

Dibangun dengan [Laravel](https://laravel.com), Tailwind CSS 4, dan Vite. Data contoh bawaan bernuansa SMKN 5 Solo (kategori Bengkel, Lab RPL, Multimedia, dan Olahraga).

## Fitur

**Peminjam (siswa dan guru)**
- Pendaftaran akun: siswa dengan NISN dan kelas, guru dengan NIP dan mata pelajaran/unit kerja
- Katalog alat per kategori beserta stok, spesifikasi, lokasi, batas hari pinjam, dan denda per hari
- Pengajuan peminjaman dengan nomor tiket dan unggah proposal
- Riwayat peminjaman, pembatalan pengajuan, serta cetak bukti pinjam dan riwayat
- Kotak pesan (inbox) untuk notifikasi dari PJ
- Halaman profil PJ per kategori

**Admin (PJ per kategori)**
- Verifikasi peminjaman: setujui, tolak, serah terima, dan selesaikan pengembalian
- Kirim pesan ke peminjam terkait sebuah peminjaman
- Manajemen alat dan unit fisik per alat (kode unit, status, kondisi)
- Cetak bukti pinjam dan rekap peminjaman
- Pusat pesan untuk mengirim pesan ke pengguna

**Otomatis**
- Perhitungan denda keterlambatan dan pengingat ke peminjam lewat perintah terjadwal `loans:process-overdue` (setiap hari pukul 07:00)

Alur status peminjaman: `menunggu` → `disetujui` → `dipinjam` → `selesai`, dengan cabang `ditolak` dan `dibatalkan`.

## Teknologi

| Bagian | Teknologi |
| --- | --- |
| Backend | PHP 8.3+, Laravel 13 |
| Frontend | Blade, Tailwind CSS 4, Vite |
| Database | SQLite (default di `.env.example`), MySQL didukung di image Docker |
| Antrean, cache, sesi | Database driver |
| Testing | PHPUnit |
| Code style | Laravel Pint |

## Prasyarat

- PHP 8.3 atau lebih baru dengan ekstensi umum Laravel (`pdo`, `mbstring`, `gd`, `zip`, `intl`, dll.). Proyek ini dikembangkan dengan PHP 8.5, sedangkan image Docker untuk Railway memakai PHP 8.4
- Composer 2
- Node.js dan npm

## Instalasi

```bash
git clone https://github.com/karanglawud/SIPAS.git
cd SIPAS
composer run setup
```

Perintah `composer run setup` menjalankan `composer install`, menyalin `.env.example` ke `.env`, membuat `APP_KEY`, menjalankan migrasi, lalu `npm install` dan `npm run build`.

Jika memakai SQLite dan file database belum ada, buat dulu sebelum migrasi:

```bash
touch database/database.sqlite
php artisan migrate
```

Isi data contoh (kategori, alat, unit, akun, dan beberapa peminjaman):

```bash
php artisan db:seed
```

Agar gambar dan proposal yang diunggah bisa diakses:

```bash
php artisan storage:link
```

## Menjalankan di Lokal

```bash
composer run dev
```

Aplikasi berjalan di `http://localhost:8000`. Alternatifnya, jalankan `php artisan serve` dan `npm run dev` di dua terminal.

Untuk denda dan pengingat otomatis, jalankan scheduler Laravel:

```bash
php artisan schedule:work
```

Atau jalankan manual satu kali:

```bash
php artisan loans:process-overdue
```

## Akun Contoh

Setelah `php artisan db:seed`, semua akun berikut memakai password `password`.

| Peran | Email |
| --- | --- |
| Siswa | `abror@smkn5solo.sch.id` |
| Guru | `danang@smkn5solo.sch.id` |
| Admin (Bengkel) | `pj.bengkel@smkn5solo.sch.id` |
| Admin (Lab RPL) | `pj.lab@smkn5solo.sch.id` |
| Admin (Multimedia) | `pj.multimedia@smkn5solo.sch.id` |
| Admin (Olahraga) | `pj.olahraga@smkn5solo.sch.id` |

> Jangan jalankan seeder di produksi, atau ganti semua password bawaan terlebih dahulu.

## Testing dan Code Style

```bash
composer test
vendor/bin/pint
```

## Struktur Proyek

```
app/Console/Commands/   Perintah artisan (loans:process-overdue)
app/Http/Controllers/   Controller peminjam dan admin
app/Models/             User, Category, Tool, ToolUnit, Loan, Message
database/migrations/    Skema database
database/seeders/       Data contoh
resources/views/        Tampilan Blade (auth, catalog, loans, admin, messages)
routes/web.php          Definisi route
routes/console.php      Jadwal perintah artisan
tests/Feature/          Pengujian fitur
```

## Deployment

Repositori menyertakan `Dockerfile` dan `start.sh` untuk deployment berbasis container (skrip dipersiapkan untuk Railway). Saat start, skrip menyiapkan folder storage, membangun cache produksi, menjalankan `php artisan migrate --force`, lalu menyalakan server di `PORT` (default 8080).

Hal yang perlu diperhatikan di produksi:
- Set `APP_ENV=production`, `APP_DEBUG=false`, dan `APP_KEY` melalui environment variable
- Pasang volume persisten untuk `storage/` agar unggahan tidak hilang
- Pastikan scheduler (`schedule:run` lewat cron, atau `schedule:work`) berjalan agar denda dan pengingat terproses

## Kontribusi

1. Fork repositori ini
2. Buat branch: `git checkout -b fitur/nama-fitur`
3. Pastikan `composer test` lolos dan jalankan `vendor/bin/pint`
4. Commit dan push, lalu buka Pull Request

## Lisensi

Repositori ini belum memiliki file lisensi. Framework Laravel dilisensikan di bawah [MIT](https://opensource.org/licenses/MIT).
