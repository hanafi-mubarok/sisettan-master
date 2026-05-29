<?php

namespace App\Console\Commands;

use App\Services\LelangSettlementService;
use Illuminate\Console\Command;

class SettleExpiredLelangCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lelang:settle-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tetapkan pemenang otomatis untuk lelang yang sudah melewati akhir penawaran';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(LelangSettlementService $settlementService)
    {
        $processed = $settlementService->settleAllExpired();

        $this->info('Auto settlement selesai. Total barang diproses: ' . $processed);

        return self::SUCCESS;
    }
}
