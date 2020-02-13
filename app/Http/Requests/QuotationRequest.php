<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class QuotationRequest extends Request
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
            'company_name' => 'required|min:2|max:100',
            'contact' => 'required|min:4',
            'telephone' => 'required|min:3|max:17',
            'email' => 'email|required',
            'sale_id' => 'required|integer',
            'department_id' => 'required|integer',
            'from_city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'from_country_code' => 'required|alpha|size:2',
            'to_city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'to_country_code' => 'required|alpha|size:2',
            'information' => 'required',
            'pieces' => 'required|integer',
            'weight' => 'required|numeric|min:0.5|max:999999',
            'volumetric_weight' => 'numeric|max:999999',
            'dimensions' => 'required',
            'goods_description' => 'required',
            'quote' => 'required|numeric|max:999999',
            'rate_of_exchange' => 'required|numeric|max:9999',
            //'terms' => 'required',
            'valid_to' => 'required|date',
        ];
    }
}
