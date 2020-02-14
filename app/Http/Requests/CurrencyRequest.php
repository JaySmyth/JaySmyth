<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CurrencyRequest extends Request
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
            //'code' => 'required|alpha|size:3|unique:currencies,code,' . $this->id,
            'code' => 'required|alpha|size:3',
            'currency' => 'required|regex:/^[a-zA-Z ]+$/|max:50',
            'display_order' => 'required|numeric|max:99',
            'rate' => 'required|numeric',
        ];
    }
}
