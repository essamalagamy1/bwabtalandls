<?php

namespace App\Console\Commands;

use App\Models\Semester;
use App\Models\Week;
use Illuminate\Console\Command;

class DeactivateExpiredSemestersAndWeeks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deactivate-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate semesters and weeks that have passed their end_date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->format('Y-m-d');

        $semesters = Semester::where('is_active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '<', $today)
            ->update(['is_active' => false]);

        $weeks = Week::where('is_active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '<', $today)
            ->update(['is_active' => false]);

        $this->info("Deactivated $semesters expired semesters.");
        $this->info("Deactivated $weeks expired weeks.");
    }
}
