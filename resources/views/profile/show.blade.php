@extends('layouts.master')

@section('title', 'Profile Details')

@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ URL::asset('assets/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
        </div>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-xxl-3 col-lg-4">
            <div class="card mt-n5 border-0 shadow-sm">
                <div class="card-body text-center p-4">
                    {{-- الصورة الشخصية --}}
                    <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                        @if ($profile->avatar && file_exists(public_path('storage/' . $profile->avatar)))
                            <img src="{{ asset('storage/' . $profile->avatar) }}"
                                class="rounded-circle avatar-xl img-thumbnail shadow" alt="Avatar">
                        @else
                            @php
                                $initials = strtoupper(
                                    substr($profile->first_name, 0, 1) . substr($profile->last_name, 0, 1),
                                );
                                $colors = ['primary', 'success', 'info', 'danger', 'warning', 'secondary'];
                                $bg = $colors[array_rand($colors)];
                            @endphp
                            <div class="avatar-xl rounded-circle bg-{{ $bg }} text-white d-flex align-items-center justify-content-center fs-2 fw-bold mx-auto shadow-sm"
                                style="width:100px; height:100px;">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    {{-- الاسم والوظيفة --}}
                    <h5 class="fs-17 fw-semibold mb-1">{{ $profile->full_name }}</h5>
                    <p class="text-muted mb-3">{{ $profile->designation ?? 'No designation' }}</p>

                    {{-- معلومات التواصل السريعة --}}
                    <ul class="list-unstyled text-start d-inline-block mx-auto mb-3">
                        <li class="mb-2"><i class="ri-mail-line text-primary me-2"></i>{{ $profile->email }}</li>
                        @if ($profile->phone)
                            <li class="mb-2"><i class="ri-phone-line text-success me-2"></i>{{ $profile->phone }}</li>
                        @endif
                        @if ($profile->city || $profile->country)
                            <li class="mb-2"><i class="ri-map-pin-line text-danger me-2"></i>{{ $profile->city }},
                                {{ $profile->country }}</li>
                        @endif
                        @if ($profile->address)
                            <li class="mb-2"><i class="ri-home-4-line text-info me-2"></i>{{ $profile->address }}</li>
                        @endif
                    </ul>

                    {{-- أزرار --}}
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="{{ route('profiles.edit', $profile->id) }}" class="btn btn-sm btn-warning">
                            <i class="ri-pencil-line me-1"></i> Edit
                        </a>
                        <a href="{{ route('profiles.index') }}" class="btn btn-sm btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Back
                        </a>
                    </div>
                </div>
            </div>

            {{-- كارت الإحصائيات --}}
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body text-center p-4">
                    <div class="row">
                        <div class="col-6 border-end">
                            <h5 class="mb-0">{{ $profile->projects_count ?? 0 }}</h5>
                            <small class="text-muted">Projects</small>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-0">{{ $profile->tasks_count ?? 0 }}</h5>
                            <small class="text-muted">Tasks</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xxl-9 col-lg-8">
            <div class="card mt-xxl-n5 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom-0">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-user me-1"></i> Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#experience" role="tab">
                                <i class="ri-briefcase-4-line me-1"></i> Experience
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content">
                        {{-- Personal Details Tab --}}
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <div class="row g-4">
                                <h5>{{ $profile->full_name }} ({{ $profile->employee->name ?? '—' }})</h5>

                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">First Name</h6>
                                    <p class="fw-semibold">{{ $profile->first_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Last Name</h6>
                                    <p class="fw-semibold">{{ $profile->last_name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Email</h6>
                                    <p class="fw-semibold">{{ $profile->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Phone</h6>
                                    <p class="fw-semibold">{{ $profile->phone ?? '—' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Designation</h6>
                                    <p class="fw-semibold">{{ $profile->designation ?? '—' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">City</h6>
                                    <p class="fw-semibold">{{ $profile->city ?? '—' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Country</h6>
                                    <p class="fw-semibold">{{ $profile->country ?? '—' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Address</h6>
                                    <p class="fw-semibold">{{ $profile->address ?? '—' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-1">Joining Date</h6>
                                    <p class="fw-semibold">
                                        {{ $profile->joining_date ? date('d M, Y', strtotime($profile->joining_date)) : '—' }}
                                    </p>
                                </div>
                                <div class="col-md-12">
                                    <h6 class="text-muted mb-1">Description</h6>
                                    <p>{{ $profile->description ?? 'No description available.' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Experience Tab --}}
                        <div class="tab-pane" id="experience" role="tabpanel">
                            <div class="d-flex align-items-center mb-4">
                                <i class="ri-briefcase-4-line fs-4 text-primary me-2"></i>
                                <h5 class="mb-0">Work Experience</h5>
                            </div>

                            @if ($profile->experiences && $profile->experiences->count())
                                <div class="timeline position-relative ps-4 mt-3">
                                    @foreach ($profile->experiences as $exp)
                                        <div
                                            class="timeline-item mb-4 p-4 border rounded-4 bg-white shadow-sm position-relative">
                                            {{-- النقطة على الخط --}}
                                            <span
                                                class="position-absolute top-0 start-0 translate-middle-y bg-primary rounded-circle"
                                                style="width: 12px; height: 12px; left: -6px; top: 35px;"></span>

                                            <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm"
                                                        style="width: 45px; height: 45px;">
                                                        <i class="ri-building-4-line fs-5"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="fw-semibold mb-1 text-dark">{{ $exp->job_title }}</h6>
                                                        <p class="text-muted mb-0 small">
                                                            <i
                                                                class="ri-briefcase-2-line me-1"></i>{{ $exp->company_name }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <span class="badge bg-primary-subtle text-primary mt-2 mt-md-0 fw-normal">
                                                    <i class="ri-calendar-line me-1"></i>{{ $exp->from_year }} -
                                                    {{ $exp->to_year }}
                                                </span>
                                            </div>

                                            @if ($exp->job_description)
                                                <p class="mt-2 mb-0 text-muted small lh-base">
                                                    {{ $exp->job_description }}
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="ri-information-line fs-3 d-block mb-2"></i>
                                    <p class="mb-0">No work experience added yet.</p>
                                </div>
                            @endif


                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .avatar-xl {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .nav-tabs-custom .nav-link.active {
            background-color: var(--bs-primary);
            color: #fff;
            border-radius: 0.5rem;
        }

        .nav-tabs-custom .nav-link {
            color: #495057;
            border: none;
        }

        .card {
            border-radius: 1rem;
        }

        .profile-user img {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .timeline {
            border-left: 2px solid #dee2e6;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 16px;
            width: 10px;
            height: 10px;
            background: var(--bs-primary);
            border-radius: 50%;
            top: 25px;
        }

        .timeline-item {
            transition: all 0.3s ease;
        }

        .timeline-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }
    </style>
@endsection
