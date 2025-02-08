<?php

namespace App\Http\Requests\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data' => ['required', 'array'],
            'data.attributes' => ['required', 'array'],
            'data.attributes.name' => ['required', 'string', 'max:255'],
            'data.attributes.description' => ['required', 'string', 'min:10', 'max:1000'],
            'data.attributes.relationships' => ['required', 'array'],
            'data.attributes.relationships.language' => ['required', 'array'],
            'data.attributes.relationships.language.id' => ['required', 'integer', 'exists:languages,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'data.attributes.relationships.language.id' => 'data.attributes.relationships.language.id must be a one of existing language IDs'
        ];
    }
}
