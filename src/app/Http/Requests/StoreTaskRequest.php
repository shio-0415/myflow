<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = auth()->id();

        return [
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['required', 'date'],
            'tag_id' => [
                'required_without:new_tag',
                'nullable',
                Rule::exists('tags', 'id')->where(fn ($q) => $q->whereNull('user_id')->orWhere('user_id', $userId)),
            ],
            'new_tag' => ['nullable', 'string', 'max:30'],
            'status_id' => ['nullable', 'exists:statuses,id'],
            'estimated_minutes' => ['nullable', 'integer', 'min:1', 'max:6000'],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
