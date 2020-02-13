<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class MailReportRecipientRequest extends Request
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
            'name' => 'required|max:255',
            'to' => 'required|max:255',
            'bcc' => 'max:255',
            'format' => 'required|alpha|max:4',
            'criteria' => 'required|min:5',
            'frequency' => 'required|alpha',
            'time' => 'required',
            'enabled' => 'boolean',
        ];
    }
}
