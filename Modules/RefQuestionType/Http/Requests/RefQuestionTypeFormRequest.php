<?php

namespace Modules\RefQuestionType\Http\Requests;

use App\Http\Requests\FormRequest;

class RefQuestionTypeFormRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['QuestionTypeCode'] = ['required','unique:RefQuestionType,QuestionTypeCode'];
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
