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
            'reference' => 'sometimes|required|max:100',
            'pieces' => 'sometimes|required|integer',
            'weight' => 'sometimes|required|numeric|min:0.5|max:9999',
            'goods_description' => 'sometimes|required|max:100',
            'volumetric_weight' => 'sometimes|numeric|min:0.5|max:9999',
            'instructions' => 'sometimes|required|max:255',
            'closing_time' => 'sometimes|required|date_format:H:i',
            'dimensions' => 'sometimes|required|max:255',
            'pod_signature' => 'sometimes|regex:/^[a-zA-Z ]+$/|min:2',
            'scs_job_number' => 'sometimes|size:14',
            'cash_on_delivery' => 'sometimes|required|numeric|max:9999',
            'type' => 'sometimes|required|size:1|in:c,d',
            'from_type' => 'sometimes|size:1|in:c,r',
            'from_name' => 'sometimes|required|min:2|max:35|regex:/^[a-zA-Z ]+$/',
            'from_company_name' => 'sometimes|required|min:2|max:35',
            'from_address1' => 'sometimes|required|min:2|max:35',
            'from_address2' => 'sometimes|required|max:35',
            'from_address3' => 'sometimes|max:35',
            'from_city' => 'sometimes|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'from_state' => 'sometimes|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'from_postcode' => 'sometimes|required|max:15',
            'from_country_code' => 'sometimes|size:2|regex:/^[a-zA-Z ]+$/',
            'from_telephone' => 'sometimes|required|max:15',
            'from_email' => 'sometimes|email',
            'to_type' => 'sometimes|size:1|in:c,r',
            'to_name' => 'sometimes|required|min:2|max:35|regex:/^[a-zA-Z ]+$/',
            'to_company_name' => 'sometimes|required|min:2|max:35',
            'to_address1' => 'sometimes|required|min:2|max:35',
            'to_address2' => 'sometimes|required|max:35',
            'to_address3' => 'sometimes|max:35',
            'to_city' => 'sometimes|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'to_state' => 'sometimes|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'to_postcode' => 'sometimes|required|max:10',
            'to_country_code' => 'sometimes|size:2|regex:/^[a-zA-Z ]+$/',
            'to_telephone' => 'sometimes|required|max:15',
            'to_email' => 'sometimes|email',
            'visible' => 'sometimes|boolean',
            'department_id' => 'sometimes|required|integer',
            'final_destination' => 'sometimes|required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'depot_id' => 'sometimes|required|integer',
            'shipment_id' => 'sometimes|integer',
            'date' => 'sometimes|required|date_format:d-m-Y',
            //'time' => 'sometimes|required|date_format:H:i|time_before:' . getPickupTime('gb', $this->from_postcode),
        ];
    }

}
