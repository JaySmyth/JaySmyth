<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class SurchargeDetailRequest extends Request
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
            'surcharge_id' => 'required|integer',
            'company_id' => 'integer',
            'code' => 'required|min:2|max:10',
            'from_date' => 'required|date|size:10',
            'to_date' => 'required|date|size:10',
            'weight_rate' => 'required|numeric',
            'package_rate' => 'required|numeric',
            'consignment_rate' => 'required|numeric',
            'min' => 'required|numeric'           
        ];
    }

}
