<?php

namespace Modules\RefInstruction\Http\Requests;

use App\Http\Requests\FormRequest;

class RefInstructionFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['InstructionCode'] = ['required','unique:RefInstruction,InstructionCode'];
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
