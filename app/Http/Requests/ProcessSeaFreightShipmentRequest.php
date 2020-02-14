<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ProcessSeaFreightShipmentRequest extends Request
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
            'shipping_line_id' => 'required',
            'bill_of_lading' => 'required|min:3',
            'port_of_loading' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:30',
            'port_of_discharge' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:30',
            'vessel' => 'required',
            'scs_job_number' => 'required',
        ];
    }
}
