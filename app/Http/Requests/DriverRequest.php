<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class DriverRequest extends Request
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
            'name' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:30',
            'telephone' => 'required',
            'vehicle_id' => 'integer',
            'depot_id' => 'required|integer',
            'enabled' => 'required|boolean',
        ];
    }

}
