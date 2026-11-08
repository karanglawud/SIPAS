<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessOverdueLoans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:process-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kalkulasi denda keterlambatan peminjaman aktif dan kirimkan pengingat otomatis ke peminjam';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $today = Carbon::today();

        $overdueLoans = Loan::with(['tool.category.admins', 'user'])
            ->whereIn('status', ['dipinjam', 'disetujui'])
            ->where('end_date', '<', $today)
            ->get();

        if ($overdueLoans->isEmpty()) {
            $this->info('Tidak ada peminjaman yang melewati tenggat waktu hari ini.');

            return Command::SUCCESS;
        }

        $processedCount = 0;
        $remindersSent = 0;

        foreach ($overdueLoans as $loan) {
            $tool = $loan->tool;
            if (! $tool) {
                continue;
            }

            $overdueDays = abs((int) $today->diffInDays($loan->end_date->startOfDay()));
            if ($overdueDays <= 0) {
                continue;
            }

            $fineAmount = $overdueDays * (int) $tool->fine_per_day * (int) $loan->quantity;

            $loan->update([
                'overdue_days' => $overdueDays,
                'fine_amount' => $fineAmount,
                'fine_status' => 'belum_lunas',
            ]);

            $processedCount++;

            // Kirim notifikasi pengingat jika belum pernah dikirim hari ini
            $shouldSendReminder = ! $loan->last_reminder_sent_at || ! $loan->last_reminder_sent_at->isToday();

            if ($shouldSendReminder) {
                $adminPj = $tool->category?->admins->first() ?? User::where('role', 'admin')->first();
                $senderId = $adminPj?->id ?? User::where('role', 'admin')->value('id') ?? $loan->user_id;

                // 1. Pesan ke peminjam
                Message::create([
                    'sender_id' => $senderId,
                    'recipient_id' => $loan->user_id,
                    'category_id' => $tool->category_id,
                    'loan_id' => $loan->id,
                    'type' => 'warning',
                    'message' => 'Peringatan Keterlambatan: Masa peminjaman alat "'.$tool->name.'" (No. Peminjaman: '.$loan->ticket_number.') telah melewati tenggat ('.$loan->end_date->translatedFormat('d M Y').') sebanyak '.$overdueDays.' hari. Denda berjalan saat ini: Rp '.number_format($fineAmount, 0, ',', '.').'. Harap segera mengembalikan alat fisik ke ruang sarana.',
                    'is_read' => false,
                ]);

                // 2. Jika baru pertama kali telat (hari ke-1), beri info juga ke Admin PJ
                if ($overdueDays === 1 && $adminPj && $adminPj->id !== $loan->user_id) {
                    Message::create([
                        'sender_id' => $loan->user_id,
                        'recipient_id' => $adminPj->id,
                        'category_id' => $tool->category_id,
                        'loan_id' => $loan->id,
                        'type' => 'warning',
                        'message' => 'Pemberitahuan Sistem: Peminjaman '.$tool->name.' oleh '.$loan->user->name.' (No. Peminjaman: '.$loan->ticket_number.') telah melewati batas waktu pengembalian hari ini.',
                        'is_read' => false,
                    ]);
                }

                $loan->update(['last_reminder_sent_at' => Carbon::now()]);
                $remindersSent++;
            }
        }

        $this->info("Berhasil memproses {$processedCount} peminjaman terlambat. {$remindersSent} pengingat otomatis telah dikirimkan.");

        return Command::SUCCESS;
    }
}
