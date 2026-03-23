@extends('layouts.master')

@section('title', 'Edit Employee')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            HR
        @endslot
        @slot('title')
            Edit Employee
        @endslot
    @endcomponent

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-semibold mb-4">Update Employee Information</h5>

            <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data"
                class="employee-edit-form">
                class="employee-edit-form">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}" class="form-control"
                            required>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $employee->email) }}"
                            class="form-control">
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                            class="form-control">
                    </div>

                    {{-- Position --}}
                    <div class="col-md-6">
                        <label class="form-label">Position</label>
                        <input type="text" name="position" value="{{ old('position', $employee->position) }}"
                            class="form-control">
                    </div>

                    {{-- Salary --}}
                    <div class="col-md-6">
                        <label class="form-label">Salary (EGP)</label>
                        <input type="number" name="salary" value="{{ old('salary', $employee->salary) }}"
                            class="form-control">
                    </div>

                    {{-- Start Date --}}
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="text" name="start_date" value="{{ old('start_date', $employee->start_date) }}"
                            class="form-control flatpickr">
                    </div>

                    {{-- Address --}}
                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" value="{{ old('address', $employee->address) }}"
                            class="form-control">
                    </div>

                    {{-- Personal Image --}}
                    <div class="col-md-6">
                        <label class="form-label">Personal Image</label>
                        <input type="file" name="personal_image" class="form-control">
                        @if ($employee->personal_image)
                            <img src="{{ asset('storage/' . $employee->personal_image) }}" class="rounded mt-2"
                                style="width: 100px; height:100px; object-fit:cover;">
                        @endif
                    </div>

                    {{-- National ID --}}
                    <div class="col-md-6">
                        <label class="form-label">National ID</label>
                        <input type="text" name="national_id" value="{{ old('national_id', $employee->national_id) }}"
                            class="form-control">
                    </div>

                    {{-- National ID Image --}}
                    <div class="col-md-6">
                        <label class="form-label">National ID Image</label>
                        <input type="file" name="national_id_image" class="form-control">
                        @if ($employee->national_id_image)
                            <img src="{{ asset('storage/' . $employee->national_id_image) }}" class="rounded mt-2"
                                style="width: 100px; height:100px; object-fit:cover;">
                        @endif
                    </div>

                    {{-- Birthday --}}
                    <div class="col-md-6">
                        <label class="form-label">Birthday</label>
                        <input type="text" name="birthday" value="{{ old('birthday', $employee->birthday) }}"
                            class="form-control flatpickr">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="pending" {{ $employee->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="active" {{ $employee->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="terminated" {{ $employee->status == 'terminated' ? 'selected' : '' }}>Terminated
                                (مرفود)</option>
                            <option value="resigned" {{ $employee->status == 'resigned' ? 'selected' : '' }}>Resigned
                                (استقال)</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status Reason (optional)</label>
                        <input type="text" name="status_reason" class="form-control"
                            placeholder="Enter reason for status change"
                            value="{{ old('status_reason', $employee->status_reason) }}">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('employees.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update Employee</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        flatpickr('.flatpickr', {});
    </script>
@endsection
