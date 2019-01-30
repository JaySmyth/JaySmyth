<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CustomsEntryCommodityRequest extends Request
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
            'commodity_code' => 'required',
            'value' => 'required',
            'duty' => 'required',
            'duty_percent' => 'required',
            'vat' => 'required',
            'weight' => 'required',
            'customs_procedure_code_id' => 'required'
        ];
    }

}
