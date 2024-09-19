<?php

namespace App\Http\Requests\Api;

use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
             'project_id' => ['required', 'exists:projects,id'],
             'assigned_user_id' => ['nullable', 'exists:users,id'],
             'title' => ['required', 'min:3', 'max:250'],
             'description' => ['required', 'min:3', 'max:250'],
             'status' => ['nullable', Rule::in([Status::TODO->value, Status::PROGRESS->value, Status::COMPLETED->value])],
             'priority' => ['nullable', Rule::in([Priority::LOW->value, Priority::MEDIUM->value, Priority::HIGH->value])],
             'due_date' => ['nullable', 'date'],
        ];
    }
}
