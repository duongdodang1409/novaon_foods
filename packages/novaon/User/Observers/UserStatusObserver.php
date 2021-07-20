<?php
/**
 * Project: caller-core
 * Author: ThachVD
 * Create time: 08:46 27/04/2021
 * Copyright (c) 2021 NOVAON.
 **/

namespace Novaon\User\Observers;


use App\User;
use Illuminate\Validation\ValidationException;
use Novaon\User\Models\Tenant\UserStatus;

class UserStatusObserver
{
    public function saving(UserStatus $userStatus) {
        $query = UserStatus::query();
        $query->select('status_code')->where('status_code', $userStatus->status_code);
        if (!empty($userStatus->id)) {
            $query->where('id', '!=', $userStatus->id);
        }

        $isExist = $query->exists();
        if ($isExist) {
            throw ValidationException::withMessages(['status_code' => trans('caller.user_status.unique_status_code')]);
        }
    }

    public function updated(UserStatus $userStatus) {
        $oldStatusCode = $userStatus->getOriginal('status_code');
        $newStatusCode = $userStatus->status_code;
        $users = User::where('manual_status', $oldStatusCode);
        if ($users->count() > 0) {
            foreach ($users->get() as $user) {
                $user->where('id', $user->id)->update(['manual_status' => $newStatusCode]);
            }
        }
    }
}
