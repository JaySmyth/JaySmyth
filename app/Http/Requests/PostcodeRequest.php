<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PostcodeRequest extends Request
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
            'postcode' => 'required|min:3',
            'country_code' => 'required|alpha|size:2',
            'pickup_time' => 'required',
            'collection_route' => 'required|alpha|min:3',
            'delivery_route' => 'required|alpha|min:3',
        ];
    }
}
