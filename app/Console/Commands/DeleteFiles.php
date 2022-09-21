<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeleteFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete not downloaded files';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::table('files')->where('last_downloaded', '<=', Carbon::now()->subDays(14))->delete();
    }
}
