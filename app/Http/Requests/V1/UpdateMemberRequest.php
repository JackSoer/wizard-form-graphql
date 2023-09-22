<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMemberRequest extends FormRequest
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
        $method = $this->method();
        $memberId = request()->member->id;

        if ($method === 'PUT') {
            return [
                'firstName' => ['required', 'between: 1, 30', 'string'],
                'lastName' => ['required', 'between: 1, 30', 'string'],
                'birthdate' => ['required', 'date_format:Y-m-d', 'before:tomorrow', 'date'],
                'reportSubject' => ['required', 'between: 1, 100', "string"],
                'country' => ['required', 'string', 'between: 2, 100'],
                'phone' => ['required', 'string', 'between: 10, 100'],
                'email' => ['required', Rule::unique('members')->ignore($memberId, 'id'), 'email:rfc, dns'],
                'company' => ['nullable', 'string', 'max:40'],
                'position' => ['nullable', 'string', 'max:40'],
                'aboutMe' => ['nullable', 'string', 'max:2000'],
                'photo' => ['nullable'],
                'isVisible' => ['nullable', 'boolean'],
            ];
        } else {
            return [
                'firstName' => ['sometimes', 'required', 'between: 2, 30', 'string'],
                'lastName' => ['sometimes', 'required', 'between: 2, 30', 'string'],
                'birthdate' => ['sometimes', 'required', 'date_format:Y-m-d', 'before:tomorrow', 'date'],
                'reportSubject' => ['sometimes', 'required', 'between: 2, 100', "string"],
                'country' => ['sometimes', 'required', 'string', 'between: 2, 100'],
                'phone' => ['sometimes', 'required', 'string', 'between: 10, 100'],
                'email' => ['sometimes', 'required', Rule::unique('members')->ignore($memberId, 'id'), 'email:rfc, dns'],
                'company' => ['nullable', 'string', 'max:40'],
                'position' => ['nullable', 'string', 'max:40'],
                'aboutMe' => ['nullable', 'string', 'max:2000'],
                'photo' => ['nullable', 'file', 'max:5242880', 'image'],
                'isVisible' => ['nullable', 'boolean'],
            ];
        }

    }

    protected function prepareForValidation()
    {
        $mergedArray = [];

        if (isset($this->firstName)) {
            $mergedArray['first_name'] = $this->firstName;
        }
        if (isset($this->lastName)) {
            $mergedArray['last_name'] = $this->lastName;
        }
        if (isset($this->aboutMe) || $this->method() === 'PUT') {
            $mergedArray['about_me'] = $this->aboutMe;
        }
        if (isset($this->reportSubject)) {
            $mergedArray['report_subject'] = $this->reportSubject;
        }
        if (isset($this->isVisible)) {
            $mergedArray['is_visible'] = $this->isVisible;
        }

        $this->merge($mergedArray);
    }
}
