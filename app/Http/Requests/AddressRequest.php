<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class AddressRequest extends Request
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
            'name' => 'required|min:2|max:35',
            'company_name' => 'min:2|max:35',
            'address1' => 'required|min:2|max:35',
            'address2' => 'max:35',
            'address3' => 'max:35',
            'city' => 'required|regex:/^[a-zA-Z \'-]+$/|min:2|max:35',
            'state' => 'regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'postcode' => 'min:2|max:10',
            'country_code' => 'required|alpha|size:2',
            'telephone' => 'required|min:3|max:20',
            'email' => 'email|max:50',
            'type' => 'required|alpha|size:1',
            'definition' => 'alpha|min:6|max:9',
        ];
    }

}
