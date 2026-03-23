@extends('layouts.master')

@section('title', 'Create Profile')

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
                        <div id="avatarPreview"
                            class="rounded-circle avatar-xl d-flex align-items-center justify-content-center bg-primary text-white fs-2 fw-bold"
                            style="width:100px; height:100px;">
                            <i class="ri-user-line"></i>
                        </div>
                    </div>
                    <h5 class="fs-16 mb-1">New Profile</h5>
                    <p class="text-muted mb-0">Fill in the details below</p>
                </div>
            </div>

            <!-- Portfolio Links -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">Portfolio Links</h5>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-dark text-light">
                                <i class="ri-github-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="github" form="createProfileForm"
                            placeholder="@username">
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-primary">
                                <i class="ri-global-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="website" form="createProfileForm"
                            placeholder="www.example.com">
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-success">
                                <i class="ri-dribbble-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="dribbble" form="createProfileForm"
                            placeholder="@username">
                    </div>
                    <div class="d-flex">
                        <div class="avatar-xs d-block flex-shrink-0 me-3">
                            <span class="avatar-title rounded-circle fs-16 bg-danger">
                                <i class="ri-pinterest-fill"></i>
                            </span>
                        </div>
                        <input type="text" class="form-control" name="pinterest" form="createProfileForm"
                            placeholder="@username">
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-user"></i> Personal Details
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#experience" role="tab">
                                <i class="ri-briefcase-4-line"></i> Experience
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4">
                    <div class="tab-content">
                        <!-- Personal Details Tab -->
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form id="createProfileForm" action="{{ route('profiles.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="row">
                                    @if ($employee ?? false)
                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                    @endif

                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control" required
                                            value="{{ $employee ? Str::before($employee->name, ' ') : old('first_name') }}">
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control"
                                            value="{{ $employee ? Str::after($employee->name, ' ') : old('last_name') }}">
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" required
                                            value="{{ $employee->email ?? old('email') }}">
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ $employee->phone ?? old('phone') }}">
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Joining Date</label>
                                        <input type="text" name="joining_date" id="JoiningdatInput"
                                            class="form-control" placeholder="Select date"
                                            value="{{ old('joining_date') }}">
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Avatar</label>
                                        <input type="file" name="avatar" id="avatarInput" class="form-control"
                                            accept="image/*">
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Skills</label>
                                        <select class="form-control" name="skills[]" data-choices multiple>
                                            <option value="illustrator">Illustrator</option>
                                            <option value="photoshop">Photoshop</option>
                                            <option value="sales">Sales</option>
                                            <option value="development">Development</option>
                                            <option value="contracting">Contracting</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">Designation</label>
                                        <input type="text" name="designation" class="form-control"
                                            value="{{ old('designation') }}">
                                    </div>

                                    <div class="col-lg-3 mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-control"
                                            value="{{ old('city') }}">
                                    </div>

                                    <div class="col-lg-3 mb-3">
                                        <label class="form-label">Country</label>
                                        <input type="text" name="country" class="form-control"
                                            value="{{ old('country') }}">
                                    </div>

                                    <div class="col-lg-12 mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="col-lg-12 text-end">
                                        <button type="submit" class="btn btn-success">Create Profile</button>
                                        <a href="{{ route('profiles.index') }}" class="btn btn-soft-danger">Cancel</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Experience Tab -->
                        <div class="tab-pane" id="experience" role="tabpanel">
                            <form id="experienceForm">
                                @csrf
                                <div id="experience-list">
                                    <div class="experience-item border rounded p-3 mb-4">
                                        <div class="row">
                                            <div class="col-lg-12 mb-3">
                                                <label class="form-label">Job Title</label>
                                                <input type="text" class="form-control" name="job_title[]"
                                                    placeholder="Job title">
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Company Name</label>
                                                <input type="text" class="form-control" name="company_name[]"
                                                    placeholder="Company">
                                            </div>
                                            <div class="col-lg-6 mb-3">
                                                <label class="form-label">Years</label>
                                                <div class="d-flex gap-2">
                                                    <select class="form-control" name="from_year[]">
                                                        @for ($i = 2000; $i <= date('Y'); $i++)
                                                            <option value="{{ $i }}">{{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                    <select class="form-control" name="to_year[]">
                                                        @for ($i = 2000; $i <= date('Y'); $i++)
                                                            <option value="{{ $i }}">{{ $i }}
                                                            </option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="job_description[]" rows="3"></textarea>
                                            </div>
                                            <div class="text-end">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-experience">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button" id="addExperience" class="btn btn-primary btn-sm">
                                        <i class="ri-add-line me-1"></i> Add New
                                    </button>
                                    <button type="button" id="saveExperience" class="btn btn-success btn-sm">
                                        <i class="ri-save-line me-1"></i> Save
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#JoiningdatInput", {
                dateFormat: "d M, Y",
                defaultDate: new Date(),
            });

            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');

            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) {
                    avatarPreview.innerHTML = '<i class="ri-user-line"></i>';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(ev) {
                    avatarPreview.innerHTML =
                        `<img src="${ev.target.result}" class="rounded-circle img-thumbnail" style="width:100px;height:100px;">`;
                };
                reader.readAsDataURL(file);
            });

            // Experience logic
            const list = document.getElementById('experience-list');
            const addBtn = document.getElementById('addExperience');
            const saveBtn = document.getElementById('saveExperience');

            function attachRemoveEvents() {
                document.querySelectorAll('.remove-experience').forEach(btn => {
                    btn.onclick = function() {
                        const item = this.closest('.experience-item');
                        if (document.querySelectorAll('.experience-item').length > 1) {
                            item.remove();
                        } else {
                            Swal.fire('Note', 'At least one experience block must remain.', 'info');
                        }
                    };
                });
            }

            attachRemoveEvents();

            addBtn.onclick = () => {
                const first = list.querySelector('.experience-item');
                const clone = first.cloneNode(true);
                clone.querySelectorAll('input, textarea, select').forEach(el => el.value = '');
                list.appendChild(clone);
                attachRemoveEvents();
            };

            saveBtn.onclick = () => {
                const form = document.getElementById('experienceForm');
                const fd = new FormData(form);
                fetch("{{ route('experiences.store') }}", {
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: fd
                    })
                    .then(res => res.json())
                    .then(() => Swal.fire('Success', 'Experience saved successfully!', 'success'))
                    .catch(() => Swal.fire('Error', 'Something went wrong', 'error'));
            };
        });
    </script>
@endsection
