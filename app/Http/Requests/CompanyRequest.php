<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CompanyRequest extends Request
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
            'company_name' => 'required|min:2|max:50',
            'address_type' => 'required|alpha|size:1',
            'address1' => 'required|min:2|max:35',
            'address2' => 'nullable|max:35',
            'address3' => 'nullable|max:35',
            'city' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'state' => 'required|regex:/^[a-zA-Z ]+$/|min:2|max:35',
            'postcode' => 'required|min:2|max:9',
            'country_code' => 'required|alpha|size:2',
            'telephone' => 'required|min:3|max:15',
            'email' => 'nullable|email|max:50',
            'site_name' => 'required|min:3|max:35',
            'notes' => 'nullable|max:255',
            'company_code' => 'alpha|unique',
            'scs_code' => 'required|size:7',
            'eori' => 'nullable|size:14',
            'group_account' => 'nullable|max:10',
            'vat_exempt' => 'required|boolean',
            'enabled' => 'boolean',
            'testing' => 'required|boolean',
            'print_format_id' => 'required|integer',
            'sale_id' => 'required|integer',
            'depot_id' => 'required|integer',
            'localisation_id' => 'required|integer',
        ];
    }
}
