<?php
/**
 * Project: Caller_Core
 * Package: Novaon\User\Request\Tenant
 * Author: tony
 * Create time: 15:52 10/21/20
 * Copyright (c) 2020 NOVAON.
 **/


namespace Novaon\User\Request\Tenant;


use Illuminate\Foundation\Http\FormRequest;

class ViewUserStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
       return true;
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
