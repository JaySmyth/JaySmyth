<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ServiceRequest extends Request
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
        return array();
        /*
        return [
            'company_id' => 'required',
            'description' => 'required|min:2',
            'product_code' => 'required|min:1',
            'commodity_code' => 'required|min:2',
            'harmonized_code' => 'required|min:2',
            'uom' => 'required|regex:/^[a-zA-Z ]+$/|min:2',
            'unit_value' => 'required|digits_between:0,1000000',
            'currency_code' => 'required|alpha|size:3',
            'unit_weight' => 'required|digits_between:0,99',
            'country_code' => 'required|alpha|size:2',
            'manufacturers_name' => 'required|min:2',
            'shipping_cost' => 'digits_between:0,9999',
        ];
        */
    }


}
