<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ServiceMessageRequest extends Request
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
            'service_id' => 'required|exists:services,id',
            'title' => 'required|min:5|max:100',
            'sticky' => 'required|boolean',
            'enabled' => 'required|boolean',
            'ifs_only' => 'required|boolean',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date',
        ];
    }
}
