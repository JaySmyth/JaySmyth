<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TrackingRequest extends Request
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
            'message' => 'required|min:5|max:255',
            'status' => 'alpha|max:100',
            'city' => 'required|regex:/^[a-zA-Z ]+$/|min:2',
            'state' => 'required|regex:/^[a-zA-Z ]+$/|min:2',
            'country_code' => 'required|alpha|size:2',
            'postcode' => 'min:2|max:8',
            'shipment_id' => 'integer',
        ];
    }
}
