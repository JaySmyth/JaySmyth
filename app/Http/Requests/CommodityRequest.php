<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CommodityRequest extends Request
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
            'description' => 'required|string|min:3|max:100|not_regex:/^[0-9]+$/',
            'product_code' => 'nullable|min:1',
            'manufacturer' => 'nullable|min:3',
            'country_of_manufacture' => 'required|alpha|size:2',
            'commodity_code' => 'nullable|numeric',
            'harmonized_code' => 'required|numeric',
            'unit_value' => 'required|numeric',
            'currency_code' => 'required|alpha|size:3',
            'uom' => 'required|regex:/^[a-zA-Z ]+$/',
            'unit_weight' => 'required|numeric',
            'weight_uom' => 'alpha|min:2',
            'shipping_cost' => 'digits_between:0,9999',
        ];
    }
}
