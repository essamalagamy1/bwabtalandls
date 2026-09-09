<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class DeployParentFeature extends Command
{
    protected $signature = 'deploy:parent-feature';

    protected $description = 'Safely deploy the Parent role feature: run migration + seed parent role (safe for live/production)';

    public function handle(): int
    {
        $this->info('🚀 Starting Parent Feature Deployment...');

        // 1. Run migration
        $this->info('▶ Running migrations...');
        $this->call('migrate', ['--force' => true]);

        // 2. Seed parent role (idempotent)
        $this->info('▶ Creating parent role (if not exists)...');
        Role::firstOrCreate(['name' => 'parent'], ['is_main' => true]);
        $this->info('  ✅ Parent role ready.');

        // 3. Clear caches
        $this->info('▶ Clearing caches...');
        $this->call('cache:clear');
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');

        // 4. Restart queue workers so they pick up new code
        $this->info('▶ Restarting queue workers...');
        $this->call('queue:restart');

        $this->info('');
        $this->info('✅ Parent Feature deployed successfully!');
        $this->info('');
        $this->table(
            ['Action', 'Status'],
            [
                ['Migration (add parent_id to users)',   '✅ Done'],
                ['Create "parent" role',                 '✅ Done'],
                ['Cache cleared & rebuilt',              '✅ Done'],
                ['Queue workers restarted',              '✅ Done'],
            ]
        );

        return self::SUCCESS;
    }
}
