<?php

namespace Novaon\User\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class SyncUsernameForUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:sync-username';

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
            $this->line('Please run inside tenant. Use: php artisan tenants:run user:sync-username');
        }
    }

    public function syncCallEvents()
    {
        User::select('id', 'email', 'username')
            ->chunk(200, function ($users) {
                foreach ($users as $user) {
                    $email = explode('@', $user->email);
                    if(!$user->username) {
                        $user->username = isset($email[0]) ? $email[0] : $user->email;
                    }
                    $user->save();
                }
            });
        $this->line('Finish sync');
    }
}
