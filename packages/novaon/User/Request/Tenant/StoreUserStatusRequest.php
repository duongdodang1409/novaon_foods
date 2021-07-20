<?php
/**
 * Project: Caller_Core
 * Package: Novaon\User\Request\Tenant
 * Author: tony
 * Create time: 14:49 10/21/20
 * Copyright (c) 2020 NOVAON.
 **/


namespace Novaon\User\Request\Tenant;


use App\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->is_master_client ? true : auth()->guard('api')->user()->can('tenant.setting.call');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'name' => ['required', 'string', 'max:255', 'unique:user_statuses'],
            'allow_outbound_calls' => ['required', 'boolean'],
            'color' => ['required', 'string']
        ];

        return $rules;
    }
}
