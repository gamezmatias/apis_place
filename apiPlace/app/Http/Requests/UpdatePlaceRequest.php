<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $placeId = $this->route('place')->id;

        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('places', 'name')->ignore($placeId)
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('places', 'slug')->ignore($placeId)
            ],
            'city' => 'sometimes|required|string|max:255',
            'state' => 'sometimes|required|string|max:255',
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