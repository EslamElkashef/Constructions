@extends('layouts.master')

@section('title', 'Edit Profile')

@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ URL::asset('assets/images/profile-bg.jpg') }}" class="profile-wid-img" alt="">
        </div>
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body text-center p-4">
                    <div class="profile-user position-relative d-inline-block mx-auto mb-4">
                        {{-- صورة المستخدم --}}
                        @if ($profile->avatar)
                            <img id="avatarPreview" src="{{ asset('storage/' . $profile->avatar) }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image shadow-sm" alt="Avatar"
                                style="object-fit:cover; width:120px; height:120px;">
                        @else
                            <img id="avatarPreview" src="{{ asset('assets/images/users/default-avatar.jpg') }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image shadow-sm"
                                alt="Default Avatar" style="object-fit:cover; width:120px; height:120px;">
                        @endif

                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                            <input id="profile-img-file-input" type="file" name="avatar"
                                class="profile-img-file-input d-none" form="updateProfileForm" accept="image/*"
                                onchange="previewAvatar(event)">
                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                <span class="avatar-title rounded-circle bg-light text-body">
                                    <i class="ri-camera-fill"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                    <h5 class="fs-16 mb-1">{{ $profile->full_name }}</h5>
                    <p class="text-muted mb-0">{{ $profile->designation ?? 'No designation' }}</p>
                </div>
            </div>

            <!-- Portfolio -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Portfolio Links</h5>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-dark text-light">
                                <i class="ri-github-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="github" form="updateProfileForm"
                            placeholder="@username" value="{{ old('github', $profile->github) }}">
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-primary">
                                <i class="ri-global-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="website" form="updateProfileForm"
                            placeholder="www.example.com" value="{{ old('website', $profile->website) }}">
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-success">
                                <i class="ri-dribbble-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="dribbble" form="updateProfileForm"
                            placeholder="@username" value="{{ old('dribbble', $profile->dribbble) }}">
                    </div>
                    <div class="d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-danger">
                                <i class="ri-pinterest-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="pinterest" form="updateProfileForm"
                            placeholder="@username" value="{{ old('pinterest', $profile->pinterest) }}">
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header border-bottom-0">
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
                        <!-- Personal Details Tab -->
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form id="updateProfileForm" action="{{ route('profiles.update', $profile->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <!-- First Name -->
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name"
                                            class="form-control @error('first_name') is-invalid @enderror"
                                            value="{{ old('first_name', $profile->first_name) }}">
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Last Name -->
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name"
                                            class="form-control @error('last_name') is-invalid @enderror"
                                            value="{{ old('last_name', $profile->last_name) }}">
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $profile->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $profile->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Joining Date -->
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Joining Date</label>
                                        <input type="text" name="joining_date"
                                            class="form-control flatpickr-input @error('joining_date') is-invalid @enderror"
                                            id="JoiningDateInput" data-provider="flatpickr"
                                            value="{{ old('joining_date', $profile->joining_date) }}">
                                        @error('joining_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Skills -->
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Skills</label>
                                        <select class="form-control" name="skills[]" id="skillsInput" data-choices
                                            multiple>
                                            @php
                                                $skills = [
                                                    'illustrator',
                                                    'photoshop',
                                                    'sales',
                                                    'development',
                                                    'contracting',
                                                ];
                                                $selected = is_array($profile->skills)
                                                    ? $profile->skills
                                                    : json_decode($profile->skills, true);
                                            @endphp
                                            @foreach ($skills as $skill)
                                                <option value="{{ $skill }}"
                                                    {{ in_array($skill, $selected ?? []) ? 'selected' : '' }}>
                                                    {{ ucfirst($skill) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Designation -->
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Designation</label>
                                        <input type="text" name="designation"
                                            class="form-control @error('designation') is-invalid @enderror"
                                            value="{{ old('designation', $profile->designation) }}">
                                    </div>

                                    <!-- City -->
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city"
                                            class="form-control @error('city') is-invalid @enderror"
                                            value="{{ old('city', $profile->city) }}">
                                    </div>

                                    <!-- Country -->
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country"
                                            class="form-control @error('country') is-invalid @enderror"
                                            value="{{ old('country', $profile->country) }}">
                                    </div>

                                    <!-- Description -->
                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $profile->description) }}</textarea>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="submit" class="btn btn-success">
                                                <i class="ri-check-line me-1"></i> Update Profile
                                            </button>
                                            <a href="{{ route('profiles.index') }}" class="btn btn-soft-danger">
                                                <i class="ri-close-line me-1"></i> Cancel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Experience Tab -->
                        <div class="tab-pane" id="experience" role="tabpanel">
                            <form id="experienceForm" action="{{ route('experiences.store') }}" method="POST">
                                @csrf
                                <div id="experience-list">
                                    @foreach ($experiences as $exp)
                                        <div class="experience-item border rounded p-3 mb-4">
                                            <div class="row">
                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Job Title</label>
                                                    <input type="text" name="job_title[]" class="form-control"
                                                        value="{{ $exp->job_title }}">
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Company Name</label>
                                                    <input type="text" name="company_name[]" class="form-control"
                                                        value="{{ $exp->company_name }}">
                                                </div>

                                                <div class="col-lg-6 mb-3">
                                                    <label class="form-label">Years</label>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <select name="from_year[]" class="form-control">
                                                            @for ($i = 2000; $i <= date('Y'); $i++)
                                                                <option value="{{ $i }}"
                                                                    {{ $i == $exp->from_year ? 'selected' : '' }}>
                                                                    {{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                        to
                                                        <select name="to_year[]" class="form-control">
                                                            @for ($i = 2000; $i <= date('Y'); $i++)
                                                                <option value="{{ $i }}"
                                                                    {{ $i == $exp->to_year ? 'selected' : '' }}>
                                                                    {{ $i }}
                                                                </option>
                                                            @endfor
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 mb-3">
                                                    <label class="form-label">Job Description</label>
                                                    <textarea name="job_description[]" class="form-control" rows="3">{{ $exp->job_description }}</textarea>
                                                </div>

                                                <div class="text-end">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-experience">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 d-flex gap-2">
                                    <button type="button" id="addExperience" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line align-bottom me-1"></i> Add New
                                    </button>
                                    <button type="button" id="saveExperience" class="btn btn-success btn-sm">
                                        <i class="ri-save-line align-bottom me-1"></i> Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr("#JoiningDateInput", {
            dateFormat: "d M, Y",
        });

        // Avatar Preview
        function previewAvatar(event) {
            const reader = new FileReader();
            reader.onload = function() {
                document.getElementById('avatarPreview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Experience Add / Remove
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('addExperience');
            const list = document.getElementById('experience-list');

            function attachRemoveEvents() {
                document.querySelectorAll('.remove-experience').forEach(btn => {
                    btn.onclick = function() {
                        const item = this.closest('.experience-item');
                        if (document.querySelectorAll('.experience-item').length > 1) item.remove();
                        else Swal.fire('Note', 'At least one experience must remain.', 'info');
                    };
                });
            }
            attachRemoveEvents();

            addBtn.onclick = () => {
                const first = list.querySelector('.experience-item');
                const clone = first.cloneNode(true);
                clone.querySelectorAll('input, textarea, select').forEach(e => e.value = '');
                list.appendChild(clone);
                attachRemoveEvents();
            };

            document.getElementById('saveExperience').onclick = () => {
                const form = document.getElementById('experienceForm');
                const data = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: data,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(res => res.json()).then(d => {
                    Swal.fire('Success!', d.message || 'Saved successfully.', 'success');
                }).catch(() => Swal.fire('Error', 'Something went wrong.', 'error'));
            };
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonColor: '#28a745',
                timer: 2000,
                timerProgressBar: true
            });
        </script>
    @endif
@endsection
