<?php
/**
 * Project: Caller_Core
 * Package: Novaon\User\Request\Tenant
 * Author: tony
 * Create time: 14:58 10/21/20
 * Copyright (c) 2020 NOVAON.
 **/


namespace Novaon\User\Request\Tenant;


use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return request()->is_master_client ? true : auth()->guard('api')->user()->can('tenant.setting.group.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $model = $this->route('group');
        $rules = [
            'name' => ['required', 'unique:groups,name,'.$model->id, 'string'],
            'description' => ['nullable', 'string'],
            'users.*.id' => ['required', 'exists:users,id']
        ];

        return $rules;
    }
}
