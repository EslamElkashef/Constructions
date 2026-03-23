@extends('layouts.master')

@section('title', 'Employee Profile')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            HR
        @endslot
        @slot('title')
            Employee Profile
        @endslot
    @endcomponent

    <div class="container mt-4 mb-5">

        {{-- Profile Header --}}
        <div class="profile-header text-center mb-4">
            @if ($employee->personal_image)
                <img src="{{ asset('storage/' . $employee->personal_image) }}" alt="{{ $employee->name }}" class="profile-img">
            @else
                <div class="profile-img d-flex align-items-center justify-content-center bg-light text-primary fw-bold fs-1">
                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                </div>
            @endif
            <h3 class="fw-bold mt-2">{{ $employee->name }}</h3>
            <p class="fs-6 mb-1">{{ $employee->position ?? '—' }}</p>

            @php
                $statusColors = [
                    'pending' => 'warning',
                    'active' => 'success',
                    'terminated' => 'danger',
                    'resigned' => 'secondary',
                ];
            @endphp

            <span class="badge bg-{{ $statusColors[$employee->status] ?? 'secondary' }}">
                {{ ucfirst($employee->status ?? 'Pending') }}
            </span>

            @if ($employee->status_reason)
                <p class="text-muted mt-2 small fst-italic">{{ $employee->status_reason }}</p>
            @endif

        </div>

        {{-- If profile exists --}}
        @if ($employee->profile)
            <div class="profile-card">
                <div class="row">
                    <div class="col-md-6 profile-info">
                        <p><span class="info-label">📧 Email:</span> <span
                                class="info-value">{{ $employee->email ?? '—' }}</span></p>
                        <p><span class="info-label">📞 Phone:</span> <span
                                class="info-value">{{ $employee->phone ?? '—' }}</span></p>
                        <p><span class="info-label">🏠 Address:</span> <span
                                class="info-value">{{ $employee->profile->address ?? '—' }}</span></p>
                        <p><span class="info-label">🎂 Birthday:</span> <span
                                class="info-value">{{ $employee->profile->birthday ?? '—' }}</span></p>
                    </div>
                    <div class="col-md-6 profile-info">
                        <p><span class="info-label">💳 National ID:</span> <span
                                class="info-value">{{ $employee->profile->national_id ?? '—' }}</span></p>
                        <p><span class="info-label">💼 Position:</span> <span
                                class="info-value">{{ $employee->position ?? '—' }}</span></p>
                        <p><span class="info-label">📅 Start Date:</span> <span
                                class="info-value">{{ $employee->start_date ?? '—' }}</span></p>
                        <p><span class="info-label">💰 Salary:</span> <span
                                class="info-value">{{ number_format($employee->salary, 2) }} EGP</span></p>
                    </div>
                </div>

                {{-- Experiences --}}
                @if ($employee->profile->experiences && $employee->profile->experiences->count())
                    <div class="divider my-4"></div>
                    <h5>Work Experiences</h5>
                    @foreach ($employee->profile->experiences as $exp)
                        <p>• <strong>{{ $exp->job_title }}</strong> at {{ $exp->company_name }} ({{ $exp->from_year }} -
                            {{ $exp->to_year ?? 'Present' }})</p>
                    @endforeach
                @endif

                {{-- National ID Image --}}
                @if ($employee->profile->national_id_image)
                    <div class="text-center mt-3">
                        <img src="{{ asset('storage/' . $employee->profile->national_id_image) }}" class="id-img"
                            style="max-width: 220px;">
                    </div>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-rounded">
                        <i class="ri-arrow-go-back-line me-1"></i> Back
                    </a>
                    <a href="{{ route('profiles.edit', $employee->profile->id) }}" class="btn btn-warning btn-rounded">
                        <i class="ri-edit-2-line me-1"></i> Edit Profile
                    </a>
                </div>
            </div>
        @else
            {{-- No profile yet – Show Create Profile Form --}}
            <div class="card p-4">
                <h5>Create Profile for {{ $employee->name }}</h5>
                <form action="{{ route('profiles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Birthday</label>
                            <input type="date" name="birthday" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>National ID</label>
                            <input type="text" name="national_id" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>National ID Image</label>
                            <input type="file" name="national_id_image" class="form-control">
                        </div>
                    </div>

                    {{-- Experiences --}}
                    <h6 class="mt-3">Work Experience</h6>
                    <div id="experiences">
                        <div class="experience-item mb-2">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <input type="text" name="job_title[]" class="form-control" placeholder="Job Title">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="company_name[]" class="form-control"
                                        placeholder="Company Name">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="from_year[]" class="form-control" placeholder="From Year">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="to_year[]" class="form-control" placeholder="To Year">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="job_description[]" class="form-control"
                                        placeholder="Description">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary my-2" id="addExperience">+ Add
                        Experience</button>

                    <button type="submit" class="btn btn-primary mt-2">Create Profile</button>
                </form>
            </div>
        @endif
    </div>

    {{-- JS to add experiences dynamically --}}
    <script>
        document.getElementById('addExperience').addEventListener('click', function() {
            let container = document.getElementById('experiences');
            let newItem = document.querySelector('.experience-item').cloneNode(true);
            newItem.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(newItem);
        });
    </script>
@endsection
@section('css')
    <style>
        .profile-header {
            position: relative;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: auto;
            border: 4px solid #e5e7eb;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.45em 0.75em;
            border-radius: 10px;
        }
    </style>
@endsection
