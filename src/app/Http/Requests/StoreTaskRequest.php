<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'date'],
            'tag_id' => ['required', 'exists:tags,id'],
            'status_id' => ['nullable', 'exists:statuses,id'],
            'estimated_minutes' => ['nullable', 'integer', 'min:1', 'max:6000'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
