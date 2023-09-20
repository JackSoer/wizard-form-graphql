<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMemberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstName' => ['required', 'between: 2, 30', 'string'],
            'lastName' => ['required', 'between: 2, 30', 'string'],
            'birthdate' => ['required', 'date_format:Y-m-d', 'before:tomorrow', 'date'],
            'reportSubject' => ['required', 'between: 2, 100', "string"],
            'country' => ['required', 'string', 'between: 2, 100'],
            'phone' => ['required', 'string', 'between: 10, 100'],
            'email' => ['required', Rule::unique('members'), 'email:rfc, dns'],
            'company' => ['nullable', 'string', 'max:40'],
            'position' => ['nullable', 'string', 'max:40'],
            'aboutMe' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'file', 'max:5242880', 'image'],
            'isVisible' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'about_me' => $this->aboutMe,
            'report_subject' => $this->reportSubject,
            'is_visible' => $this->isVisible,
        ]);
    }
}
