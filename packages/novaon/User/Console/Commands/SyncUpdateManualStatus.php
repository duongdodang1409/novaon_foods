<?php

namespace Novaon\User\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class SyncUpdateManualStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:sync-manual-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync username for user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if(tenancy()->tenant) {
            $this->syncCallEvents();
        } else {
            $this->line('Please run inside tenant. Use: php artisan tenants:run user:sync-manual-status');
        }
    }

    public function syncCallEvents()
    {
        User::select('id', 'manual_status')
            ->chunk(20, function ($users) {
                foreach ($users as $user) {
                    $user->manual_status = 'AVAILABLE';
                    $user->save();
                }
            });
        $this->line('Finish sync');
    }
}
