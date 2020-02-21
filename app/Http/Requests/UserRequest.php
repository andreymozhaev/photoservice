<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'=>'required',
            'surname'=>'required',
            'phone'=>'required|unique:users|size:11|digits:11',
            'password'=>'required'
        ];
    }
}
