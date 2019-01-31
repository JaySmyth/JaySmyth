<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ImportConfigRequest extends Request
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
            'company_id' => 'required|integer',
            'user_id' => 'sometimes|integer',
            //'company_name' => 'required|min:2|max:50|unique:import_configs,company_name,' . $this->id . ',id',
            'company_name' => 'required|min:2|max:50',
            'start_row' => 'required|integer',
            'delim' => 'required',
            'enabled' => 'boolean',
            'test_mode' => 'boolean',
            'default_service' => 'required',
            'default_terms' => 'required',
            'default_goods_description' => 'required|min:2|max:35',
            'default_pieces' => 'integer',
            'default_weight' => 'numeric',
            'default_customs_value' => 'numeric',
            'default_recipient_name' => 'min:2|max:50',
            'default_recipient_email' => 'email',
            'ship_ref_sep' => 'size:1',
        ];
    }

}
