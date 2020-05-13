<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class TransportJobRequest extends Request
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
            'reference' => 'nullable|required|max:100',
            'pieces' => 'nullable|required|integer',
            'weight' => 'nullable|required|numeric|min:0.5|max:9999',
            'goods_description' => 'nullable|required|max:100',
            'volumetric_weight' => 'nullable|numeric|min:0.5|max:9999',
            'instructions' => 'nullable|required|max:255',
            'closing_time' => 'nullable|required|date_format:H:i',
            'dimensions' => 'nullable|required|max:255',
            'pod_signature' => 'nullable|regex:/^[a-zA-Z ]+$/|min:2',
            'scs_job_number' => 'nullable|size:14',
            'cash_on_delivery' => 'nullable|required|numeric|max:9999',
            'type' => 'nullable|required|size:1|in:c,d',
            'from_type' => 'nullable|size:1|in:c,r',
            'from_name' => 'nullable|required|min:2|max:35|regex:/^[a-zA-Z ]+$/',
            'from_company_name' => 'nullable|required|min:2|max:35',
            'from_address1' => 'nullable|required|min:2|max:35',
            'from_address2' => 'nullable|required|max:35',
            'from_address3' => 'nullable|max:35',
            'from_city' => 'nullable|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'from_state' => 'nullable|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'from_postcode' => 'nullable|required|max:15',
            'from_country_code' => 'nullable|size:2|regex:/^[a-zA-Z ]+$/',
            'from_telephone' => 'nullable|required|max:15',
            'from_email' => 'nullable|email',
            'to_type' => 'nullable|size:1|in:c,r',
            'to_name' => 'nullable|required|min:2|max:35|regex:/^[a-zA-Z ]+$/',
            'to_company_name' => 'nullable|required|min:2|max:35',
            'to_address1' => 'nullable|required|min:2|max:35',
            'to_address2' => 'nullable|required|max:35',
            'to_address3' => 'nullable|max:35',
            'to_city' => 'nullable|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'to_state' => 'nullable|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'to_postcode' => 'nullable|required|max:10',
            'to_country_code' => 'nullable|size:2|regex:/^[a-zA-Z ]+$/',
            'to_telephone' => 'nullable|required|max:15',
            'to_email' => 'nullable|email',
            'visible' => 'nullable|boolean',
            'department_id' => 'nullable|required|integer',
            'final_destination' => 'nullable|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'depot_id' => 'nullable|required|integer',
            'shipment_id' => 'nullable|integer',
            'date' => 'nullable|required|date_format:d-m-Y',
            //'time' => 'nu|required|date_format:H:i|time_before:' . getPickupTime('gb', $this->from_postcode),
        ];
    }
}
