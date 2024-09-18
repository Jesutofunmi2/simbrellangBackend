<?php

namespace App\Http\Requests\Api;

use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'status' => ['nullable', Rule::in([Status::TODO->value, Status::PROGRESS->value, Status::COMPLETED->value])],
            'files' => ['array', 'min:1'],
            'files.*' => [
                'bail',
                'image',
                'mimetypes:image/jpeg,image/png,image/webp',
                'max:10048',
            ],
        ];
    }
}
