@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="mb-0">Add Expense for Project: {{ $project->name }}</h3>
            </div>
            <div class="card-body">

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('projects.expenses.store', $project->id) }}" method="POST">
                    @csrf

                    {{-- Title --}}
                    <div class="mb-3">
                        <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title"
                            class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Expense Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="amount" id="amount" step="0.01"
                                class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}"
                                required>
                            <span class="input-group-text">EGP</span>
                        </div>
                        @error('amount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Spent By --}}
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Spent By <span class="text-danger">*</span></label>
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror"
                            required>
                            <option value="">-- Select User --</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Spent Date --}}
                    <div class="mb-3">
                        <label for="spent_at" class="form-label">Spent Date <span class="text-danger">*</span></label>
                        <input type="text" name="spent_at" id="spent_at"
                            class="form-control @error('spent_at') is-invalid @enderror" placeholder="Select spent date"
                            required>
                        @error('spent_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- Actions --}}
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-add-line"></i> Add Expense
                        </button>
                        <div>
                            <button type="reset" class="btn btn-light">Reset</button>
                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Deadline picker (لو موجود)
            if (document.querySelector("#datepicker-deadline-input")) {
                flatpickr("#datepicker-deadline-input", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    minDate: "today",
                    allowInput: true,
                    disableMobile: true,
                });
            }

            // Spent date picker
            if (document.querySelector("#spent_at")) {
                flatpickr("#spent_at", {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: "Y-m-d",
                    maxDate: "today", // ميخليش المستخدم يختار تاريخ في المستقبل
                    allowInput: true,
                    disableMobile: true,
                });
            }
        });
    </script>

    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
