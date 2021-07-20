<?php
/**
 * Project: Caller_Core
 * Package: Novaon\User\Request\Tenant
 * Author: tony
 * Create time: 15:39 10/21/20
 * Copyright (c) 2020 NOVAON.
 **/


namespace Novaon\User\Request\Tenant;


use Illuminate\Foundation\Http\FormRequest;

class ViewGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (request()->is_master_client) {
            return true;
        } else {
            $user = auth()->guard('api')->user();
            if ($user->can('tenant.setting.group.view') ||
                $user->can('tenant.setting.queue.create') ||
                $user->can('tenant.setting.queue.update')) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
