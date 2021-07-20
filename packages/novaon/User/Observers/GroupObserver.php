<?php
/**
 * Project: Caller_Core
 * Package: Novaon\Observers
 * Author: tony
 * Create time: 17:56 9/25/20
 * Copyright (c) 2020 NOVAON.
 **/


namespace Novaon\User\Observers;


use Novaon\User\Models\Tenant\Group;
use Novaon\User\Services\StringeePccUserApi;

class GroupObserver
{
    /**
     * Handle the group "created" event.
     * @param Group $group
     */
    public function created(Group $group)
    {
        // Create Stringee Group
        $stringeePcc = new StringeePccUserApi(tenancy()->tenant);
        $stringeeGroup = $stringeePcc->createGroup($group->name);

        // If create success
        if($stringeeGroup['r'] == 0) {
            //Update user
            $group->provider_group_id = $stringeeGroup['groupID'];
            $group->save();
        }
    }

    /**
     * Handle the group "updated" event.
     * @param Group $group
     */
    public function updated(Group $group)
    {
        // Create Stringee Group
        $stringeePcc = new StringeePccUserApi(tenancy()->tenant);
        $stringeePcc->updateGroup($group->provider_group_id, $group->name);
    }

    /**
     * Handle the group "deleted" event.
     * @param Group $group
     */
    public function deleted(Group $group)
    {
        //TODO: Delete group
        $stringee = new StringeePccUserApi(tenancy()->tenant);
        $stringee->deleteGroup($group->provider_group_id);
    }

    /**
     * Handle the group "restored" event.
     * @param Group $group
     */
    public function restored(Group $group)
    {
        //
    }

    /**
     * Handle the group "force deleted" event.
     * @param Group $group
     */
    public function forceDeleted(Group $group)
    {
        //TODO
    }
}
