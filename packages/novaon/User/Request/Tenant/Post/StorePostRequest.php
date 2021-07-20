<?php
/**
 * Project: Caller_Core
 * Package: Novaon\Product\Request\Tenant
 * Author:  duong
 * Create time: 11:15 5/27/21
 * Copyright (c) 2021 NOVAON.
 **/


namespace Novaon\User\Request\Tenant\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
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
        return [
            'title'=> ['string','max:255'],
            'content' =>['required','string'],
            'image' => ['nullable'],
            'brief' => ['required','string','max:255'],
            'slug' => ['required','string','max:100'],
            'tags' => ['nullable'],
        ];
    }
}
