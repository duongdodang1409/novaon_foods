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
use Novaon\User\Services\StringeePccUserApi;

class ProcessAgentAction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @var string action
     */
    protected string $action;

    protected $stringee;

    /**
     * ProcessAgentAction constructor.
     * @param Tenant $tenant
     * @param User $user
     * @param string $action
     */
    public function __construct(Tenant $tenant, User $user, $action = 'create')
    {
        $this->user = $user;
        $this->action = $action;
        $this->stringee = new StringeePccUserApi($tenant);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->action) {
            case 'update':
                //TODO: update
                try {
                    $this->stringee->updateAgent($this->user);
                } catch (GuzzleException $exception) {
                    throwException($exception);
                }

                break;
            case 'delete':
                //TODO: Delete
                try {
                    $this->stringee->deleteAgent($this->user->provider_agent_id);
                } catch (GuzzleException $exception) {
                    throwException($exception);
                }
                break;
            case 'create':
                //TODO: create
                //Create Agent when create user
                try {
                    $agent = $this->stringee->createAgent($this->user);
                    if($agent['r'] == 0) {
                        //Update user
                        $this->user->provider_agent_id = $agent['agentID'];
                        $this->user->save();
                    }
                } catch (ClientException $exception) {
                    throwException($exception);
                }

                break;
        }
    }
}
