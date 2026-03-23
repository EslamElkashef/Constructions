<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employeeId = optional($this->route('employee'))->id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,'.$employeeId,
            'phone' => 'required|string|max:20', // ✔ string وليس رقم
            'address' => 'required|string|max:255',
            'personal_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'national_id' => 'required|numeric',
            'national_id_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'birthday' => 'required|date',
            'position' => 'required|string|max:255',
            'salary' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'status' => 'required|in:pending,active,terminated,resigned',
            'status_reason' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The employee name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'phone.required' => 'The phone number is required.',
            // ❌ احذف phone.numeric لأنه ليس له rule
            'address.required' => 'The address is required.',
            'personal_image.image' => 'The personal image must be a valid image file.',
            'personal_image.mimes' => 'Allowed image types: jpg, jpeg, png, gif.',
            'personal_image.max' => 'Personal image must not exceed 2MB.',
            'national_id.required' => 'The national ID is required.',
            'national_id.numeric' => 'The national ID must contain digits only.',
            'national_id_image.image' => 'The national ID image must be a valid image file.',
            'national_id_image.mimes' => 'Allowed types: jpg, jpeg, png, gif.',
            'birthday.required' => 'The birthday is required.',
            'position.required' => 'The job position is required.',
            'salary.numeric' => 'The salary must be a number.',
            'status.required' => 'The employee status is required.',
            'status.in' => 'Invalid status selected.',
            'status_reason.max' => 'The reason cannot exceed 500 characters.',
        ];
    }
}
