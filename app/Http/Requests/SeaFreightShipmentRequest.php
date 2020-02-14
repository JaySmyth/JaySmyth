<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SeaFreightShipmentRequest extends Request
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
            'company_id' => 'required',
            'reference' => 'required|min:3',
            'final_destination' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:30',
            'final_destination_country_code' => 'required|alpha|size:2',
            'weight' => 'required',
            'value' => 'required',
        ];
    }
}
