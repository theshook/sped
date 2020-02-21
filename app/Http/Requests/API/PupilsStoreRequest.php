<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class PupilsStoreRequest extends FormRequest
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
			'school_id' => 'required',
			'first_name' => 'required|min:3|max:30',
			'last_name' => 'required|min:3|max:30',
			'middle_name' => 'required|min:3|max:30',
			'birth_date' => 'required',
			'prof_pic' => 'required'
        ];
    }
}
