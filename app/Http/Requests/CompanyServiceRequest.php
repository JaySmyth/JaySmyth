<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CompanyServiceRequest extends Request
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
            'name' => 'nullable|string',
            'country_filter' => 'nullable|string|min:2|max:100|regex:/^[A-Z,!]+$/',
            'monthly_limit' => 'nullable|min:1',
            'max_weight_limit' => 'nullable|min:1',
            'company_id' => 'required|exists:companies,id',
            'service_id' => 'required|exists:services,id',
        ];
    }
}
