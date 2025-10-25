<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:places,name',
            'slug' => 'nullable|string|max:255|unique:places,slug',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.unique' => 'A place with this name already exists.',
            'city.required' => 'The city field is required.',
            'state.required' => 'The state field is required.',
        ];
    }
}