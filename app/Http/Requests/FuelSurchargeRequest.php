<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class FuelSurchargeRequest extends Request
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
            'carrier_id' => 'required',
            'service_id' => 'required',
            'percentage' => 'required',
            'date_from' => 'required',
            'date_to' => 'required',
        ];
    }
}
