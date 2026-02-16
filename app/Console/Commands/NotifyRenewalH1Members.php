<?php

namespace App\Console\Commands;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class NotifyRenewalH1Members extends Command
{
    protected $signature = 'members:notify-renewal-h1 {--dry-run : Tampilkan kandidat tanpa kirim/log final}';

    protected $description = 'Notifikasi tagihan perpanjangan membership untuk member H+1 dari tanggal expired.';

    public function handle(): int
    {
        $targetDate = Carbon::now('Asia/Jakarta')->subDay()->toDateString();

        $members = Member::query()
            ->where('parent_id', 0)
            ->whereDate('tgl_expired', $targetDate)
            ->get(['id', 'nama', 'no_hp', 'tgl_expired']);

        $count = $members->count();

        if ($this->option('dry-run')) {
            $this->info("Dry-run: {$count} member kandidat notifikasi H+1 (expired {$targetDate}).");
            return Command::SUCCESS;
        }

        if ($count === 0) {
            $this->info('Tidak ada member H+1 untuk notifikasi perpanjangan.');
            return Command::SUCCESS;
        }

        foreach ($members as $member) {
            // Placeholder delivery channel.
            Log::info('Renewal reminder H+1', [
                'member_id' => $member->id,
                'name' => $member->nama,
                'phone' => $member->no_hp,
                'expired_date' => $member->tgl_expired,
                'message' => 'Masa berlaku membership sudah habis. Silakan lakukan pembayaran perpanjangan.',
            ]);
        }

        $this->info("Reminder H+1 berhasil diproses untuk {$count} member.");
        return Command::SUCCESS;
    }
}
