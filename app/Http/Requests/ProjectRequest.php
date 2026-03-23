<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
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
            'title' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'priority' => 'required|in:High,Medium,Low',
            'status' => 'required|in:Pending,New,Inprogress,Completed',
            'deadline' => 'required|date',
            'privacy_status' => 'required|in:Private,Team,Public',
            'categories' => 'nullable|in:Real Estate Development,Real Estate Marketing,Contracting',
            'budget' => 'required|numeric|min:0',
            'attached_files.*' => 'nullable|file|max:10240',
            'skills' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The project title is required.',
            'title.max' => 'The project title must not exceed 255 characters.',
            'thumbnail.image' => 'The thumbnail must be an image.',
            'thumbnail.mimes' => 'The thumbnail must be a JPEG, PNG, or GIF file.',
            'priority.nullable' => 'Please select a priority.',
            'priority.in' => 'Priority must be High, Medium, or Low.',
            'status.nullable' => 'Please select a status.',
            'status.in' => 'Status must be Inprogress or Completed.',
            'deadline.date' => 'Deadline must be a valid date.',
            'privacy.nullable' => 'Please select a privacy option.',
            'privacy.in' => 'Privacy must be Private, Team, or Public.',
            'categories.nullable' => 'Please select a privacy option.',
            'categories.in' => 'Privacy must be Private, Team, or Public.',
            'attached_files.nullable' => 'Please upload at least one file.',
            'attached_files.file' => 'The attached file must be a valid file.',
            'attached_files.mimes' => 'Allowed file types: JPG, JPEG, PNG, or PDF only.',
            'attached_files.max' => 'The attached file size must not exceed 2 MB.',
            'attached_files.*.mimes' => 'Each file must be of type: JPG, JPEG, PNG, or PDF.',
            'attached_files.*.max' => 'Each file must not be larger than 2 MB.',
            'budget.required' => 'The project budget is required.',
            'budget.numeric' => 'The project budget must be number.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'deadline' => $this->deadline ?? now()->format('Y-m-d'),
            'privacy_status' => $this->privacy_status ?? 'Public',
            'budget' => $this->budget ?? 0,
        ]);
    }
}
