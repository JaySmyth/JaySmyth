<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContainerRequest extends Request
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
            'size' => 'required',
            'number' => 'required',          
            'goods_description' => 'required',
            'number_of_cartons' => 'required',
            'weight' => 'required'
        ];
    }

}
