<?php
/**
 * Project: Caller_Core
 * Package: Novaon\User\Request\Tenant
 * Author: taitn
 * Create time: 11:15 5/27/21
 * Copyright (c) 2021 NOVAON.
 **/

namespace Novaon\User\Request\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class ViewUserRequest extends FormRequest
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
