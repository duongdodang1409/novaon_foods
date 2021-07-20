<?php

namespace Novaon\User\Jobs;

use App\Tenant;
use App\User;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Novaon\Base\Stringee\Services\CallerUserApi;
use Novaon\Base\Utilities\CallerUser;
use Novaon\User\Models\Tenant\Agent;
use Novaon\User\Models\Tenant\UserStatus;
use Novaon\User\Services\StringeePccUserApi;

class ProcessUserStatusUpdateAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * ProcessUserStatusUpdateAction constructor.
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userStatus = UserStatus::all();
        Agent::updateAgentStatus();
        $msg = [
            "type" => "live_update",
            "channel" => "sync_user_status",
            "event" => "updated",
            "data" => $userStatus
        ];

        $callerUser = new CallerUser();
        $userIds = User::all()->where('online_status', 1)->pluck('id')->toArray();
        if($userIds) {
            foreach ($userIds as $userId) {
                $callerUser->sendMessage($userId, $msg, 'system');
            }
        }
    }
}
