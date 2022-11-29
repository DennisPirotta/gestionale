<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHourRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'count' => 'required',
            'date' => 'required_without:start,end',
            'start' => 'required_without:date',
            'end' => 'required_without:date',
            'description' => 'nullable',
            'hour_type_id' => ['required','not_in:0'],
            'user_id' => 'nullable',
        ];
    }
}
