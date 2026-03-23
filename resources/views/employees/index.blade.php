@extends('layouts.master')

@section('title', 'Employees')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            HR
        @endslot
        @slot('title')
            Employees List
        @endslot
    @endcomponent

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-primary mb-0">
            <i class="ri-team-line me-2"></i> Employees Overview
        </h4>

        <button class="btn btn-gradient-primary btn-sm rounded-pill px-3 shadow-sm" data-bs-toggle="modal"
            data-bs-target="#createEmployeeModal">
            <i class="ri-add-line me-1"></i> Add Employee
        </button>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('employees.index') }}"
        class="d-flex flex-wrap align-items-center gap-2 bg-light border rounded-4 p-3 shadow-sm mb-4">

        {{-- 🔍 Search --}}
        <div class="input-group input-group-sm w-auto">
            <span class="input-group-text bg-white"><i class="ri-user-search-line"></i></span>
            <input type="text" name="q" class="form-control border-start-0"
                placeholder="Search by name or position..." value="{{ request('q') }}">
        </div>

        {{-- 🏢 Department --}}
        <select name="department" class="form-select form-select-sm w-auto stylish-select">
            <option value="">All Departments</option>
            @foreach ($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>

        {{-- 📊 Status --}}
        <select name="status" class="form-select form-select-sm w-auto stylish-select">
            <option value="">All Status</option>
            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Terminated" {{ request('status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
            <option value="Resigned" {{ request('status') == 'Resigned' ? 'selected' : '' }}>Resigned</option>
        </select>

        {{-- 🔘 Filter + Reset --}}
        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="ri-filter-2-line me-1"></i> Filter
        </button>
        <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="ri-refresh-line me-1"></i> Reset
        </a>
    </form>

    {{-- Employee Cards --}}
    <div class="row">
        @forelse ($employees as $employee)
            <div class="col-xl-3 col-lg-4 col-md-6" id="employee-card-{{ $employee->id }}">
                <div class="card shadow-sm border-0 mb-4 hover-card position-relative">

                    {{-- Top Buttons --}}
                    <div class="position-absolute top-0 start-0 end-0 d-flex justify-content-between p-2">
                        <button class="btn btn-light btn-sm toggle-favorite" data-id="{{ $employee->id }}">
                            <i class="{{ $employee->is_favorite ? 'ri-star-fill text-warning' : 'ri-star-line' }}"></i>
                        </button>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="{{ route('employees.show', $employee->id) }}" class="dropdown-item">
                                        <i class="ri-eye-line me-1"></i> Show
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('employees.edit', $employee->id) }}" class="dropdown-item">
                                        <i class="ri-pencil-line me-1"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger delete-employee"
                                        data-action="{{ route('employees.destroy', $employee->id) }}">
                                        <i class="ri-delete-bin-line me-1"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- View Card --}}
                    <div class="card-body text-center p-4 mt-3 employee-view">
                        <div class="position-relative d-inline-block mb-3">
                            @if ($employee->personal_image)
                                <img src="{{ asset('storage/' . $employee->personal_image) }}" alt="{{ $employee->name }}"
                                    class="rounded-circle shadow" style="width:100px;height:100px;object-fit:cover;">
                            @else
                                <div class="avatar-title bg-light text-primary rounded-circle fs-3 fw-bold d-flex align-items-center justify-content-center"
                                    style="width:100px;height:100px;">
                                    {{ strtoupper(substr($employee->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <h6 class="fw-semibold mb-1">{{ $employee->name }}</h6>
                        <p class="text-muted mb-2">{{ $employee->position ?? '—' }}</p>

                        <div class="text-muted small mb-3">
                            <i class="ri-mail-line me-1"></i>{{ $employee->email ?? '—' }}<br>
                            <i class="ri-phone-line me-1"></i>{{ $employee->phone ?? '—' }}<br>
                        </div>

                        {{-- حالة ملونة --}}
                        @php
                            $badgeClass = match (strtolower($employee->status ?? '')) {
                                'active' => 'success',
                                'pending' => 'warning',
                                'terminated' => 'danger',
                                'resigned' => 'info',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge rounded-pill px-3 py-2 bg-{{ $badgeClass }}">
                            {{ ucfirst($employee->status ?? '—') }}
                        </span>

                        @if ($employee->profile)
                            <div class="mt-3">
                                <a href="{{ route('profiles.show', $employee->profile->id) }}"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="ri-user-line me-1"></i> View Profile
                                </a>
                            </div>
                        @else
                            <div class="mt-3">
                                <a href="{{ route('profiles.create', ['employee_id' => $employee->id]) }}"
                                    class="btn btn-outline-success btn-sm">
                                    <i class="ri-user-add-line me-1"></i> Create Profile
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="ri-user-search-line fs-1 d-block mb-3"></i>
                <p>No employees found</p>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $employees->links() }}
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                @include('employees.create')
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .hover-card {
            transition: all 0.2s ease-in-out;
        }

        .hover-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.08);
        }

        .stylish-select {
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background-color: #fafafa;
            min-width: 140px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .stylish-select:hover,
        .stylish-select:focus {
            border-color: #4f46e5;
            background-color: #f0f4ff;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.25);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: #fff;
            transition: 0.3s;
        }

        .btn-gradient-primary:hover {
            background: linear-gradient(135deg, #4338ca, #2563eb);
            transform: scale(1.03);
        }
    </style>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // ✅ Flatpickr (تواريخ الميلاد والتعيين)
            if (typeof flatpickr !== "undefined") {
                flatpickr(".flatpickr", {
                    dateFormat: "Y-m-d"
                });
            }

            // ✅ Toggle بين وضع العرض والتحرير
            window.toggleEditCard = function(id) {
                const card = document.querySelector(`#employee-card-${id}`);
                if (!card) return;
                card.querySelector(".employee-view").classList.toggle("d-none");
                card.querySelector(".employee-edit").classList.toggle("d-none");
            };

            // ✅ عند الضغط على Edit
            document.querySelectorAll(".btn-edit").forEach((btn) => {
                btn.addEventListener("click", function() {
                    toggleEditCard(this.dataset.editId);
                });
            });

            // ✅ عند الضغط على Delete
            function confirmDelete(id) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "This employee will be permanently deleted.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, delete!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/employees/${id}`;

                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';

                        form.appendChild(csrfInput);
                        form.appendChild(methodInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // ✅ عند الضغط على Favorite
            document.querySelectorAll(".toggle-favorite").forEach((btn) => {
                btn.addEventListener("click", function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const icon = this.querySelector("i");

                    fetch(`{{ url('/employees') }}/${id}/favourite`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                "Accept": "application/json",
                            },
                        })
                        .then((res) => res.json())
                        .then((data) => {
                            if (data.success) {
                                icon.classList.toggle("ri-star-fill");
                                icon.classList.toggle("ri-star-line");
                                icon.classList.toggle("text-warning");
                            }
                        })
                        .catch((err) => console.error(err));
                });
            });

            // ✅ عند الضغط على Save في المودال (Create Employee)
            const createForm = document.getElementById("createEmployeeForm");
            if (createForm) {
                createForm.addEventListener("submit", function(e) {
                    e.preventDefault();

                    Swal.fire({
                        title: "Confirm Save",
                        text: "Do you want to save this employee?",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonText: "Yes, save",
                        cancelButtonText: "Cancel",
                        reverseButtons: true,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            createForm.submit();
                        }
                    });
                });
            }
        });
    </script>
@endsection
