<?php

namespace App\Console\Commands;

use App\Models\History;
use App\Models\HistoryMembership;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurgeExpiredMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:purge-expired {--dry-run : Tampilkan jumlah tanpa menghapus}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus member yang sudah melewati tanggal expired (sekali sehari).';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $today = Carbon::now('Asia/Jakarta')->startOfDay()->toDateString();

        $ids = Member::whereDate('tgl_expired', '<', $today)->pluck('id');
        $count = $ids->count();

        if ($this->option('dry-run')) {
            $this->info("Dry-run: {$count} member akan dihapus.");
            return Command::SUCCESS;
        }

        if ($count === 0) {
            $this->info("Tidak ada member expired untuk dihapus.");
            return Command::SUCCESS;
        }

        DB::transaction(function () use ($ids, $count, $today) {
            HistoryMembership::whereIn('member_id', $ids)->delete();
            History::whereIn('member_id', $ids)->delete();
            Member::whereIn('id', $ids)->delete();

            Log::info('Purge expired members', [
                'date' => $today,
                'count' => $count,
            ]);
        });

        $this->info("Berhasil menghapus {$count} member expired.");

        return Command::SUCCESS;
    }
}
