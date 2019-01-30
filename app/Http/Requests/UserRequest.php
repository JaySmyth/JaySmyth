<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
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
        $rules = [
            'name' => 'required|min:4',
            'email' => 'email|required|unique:users,email,' . $this->id,
            'role_id' => 'required|sometimes|integer',
            'telephone' => 'required|min:3|max:17',
        ];

        if ($this->method() == 'POST') {
            $rules['company_id'] = 'required|integer';
        }

        return $rules;
    }

}
