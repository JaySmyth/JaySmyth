<?php

namespace App\Http\Requests;

class DocumentRequest extends Request
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
            'file' => 'required|mimes:pdf,jpg,jpeg,png',
            'document_type' => 'required|min:3|max:80',
            'description' => 'required|min:3|max:80',
            'parent' => 'required',
            'id' => 'required|integer',
        ];
    }
}
