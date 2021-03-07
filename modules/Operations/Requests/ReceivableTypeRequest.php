<?php

namespace Modules\Operations\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReceivableTypeRequest extends FormRequest
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
            'id' => 'required|string',
            'name' => 'required|string',
            'attributes_schema' => 'required|json',
        ];
    }
}
