@extends('layouts.master')

@section('title', 'General Revenues')

@section('css')
    <link href="{{ URL::asset('/assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">

    <style>
        .avatar-sm {
            width: 3rem;
            height: 3rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-title {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-soft-primary {
            background-color: rgba(76, 139, 245, 0.1);
        }

        .bg-soft-success {
            background-color: rgba(16, 185, 129, 0.1);
        }

        .bg-soft-warning {
            background-color: rgba(245, 158, 11, 0.1);
        }

        .bg-soft-info {
            background-color: rgba(59, 130, 246, 0.1);
        }

        .text-primary {
            color: #4C8BF5;
        }

        .text-success {
            color: #10B981;
        }

        .text-warning {
            color: #F59E0B;
        }

        .text-info {
            color: #3B82F6;
        }
    </style>
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Accounting
        @endslot
        @slot('title')
            General Revenues
        @endslot
    @endcomponent

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
        {{-- Total Revenues --}}
        <div class="col-md-3">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-muted mb-1">Total Revenues</h6>
                            <h4 class="fw-bold mb-0 text-success">{{ number_format($totalRevenues, 2) }}</h4>
                            <small class="text-muted">EGP</small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success text-success rounded-3">
                                <i class="ri-money-dollar-circle-line fs-3"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- This Month --}}
        <div class="col-md-3">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-muted mb-1">This Month</h6>
                            <h4 class="fw-bold mb-0 text-success">{{ number_format($thisMonthRevenues, 2) }}</h4>
                            <small class="text-muted">EGP</small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary text-primary rounded-3">
                                <i class="ri-calendar-line fs-3"></i>
                            </span>
                        </div>
                    </div>
                    @if ($totalRevenues > 0)
                        <div class="mt-2">
                            <span class="badge bg-soft-success text-success">
                                {{ number_format(($thisMonthRevenues / $totalRevenues) * 100, 1) }}% of total
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- This Week --}}
        <div class="col-md-3">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-muted mb-1">This Week</h6>
                            <h4 class="fw-bold mb-0 text-success">{{ number_format($thisWeekRevenues, 2) }}</h4>
                            <small class="text-muted">EGP</small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning text-warning rounded-3">
                                <i class="ri-calendar-2-line fs-3"></i>
                            </span>
                        </div>
                    </div>
                    @if ($totalRevenues > 0)
                        <div class="mt-2">
                            <span class="badge bg-soft-warning text-warning">
                                {{ number_format(($thisWeekRevenues / $totalRevenues) * 100, 1) }}% of total
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Today --}}
        <div class="col-md-3">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-muted mb-1">Today</h6>
                            <h4 class="fw-bold mb-0 text-success">{{ number_format($todayRevenues, 2) }}</h4>
                            <small class="text-muted">EGP</small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info text-info rounded-3">
                                <i class="ri-calendar-check-line fs-3"></i>
                            </span>
                        </div>
                    </div>
                    @if ($totalRevenues > 0)
                        <div class="mt-2">
                            <span class="badge bg-soft-info text-info">
                                {{ number_format(($todayRevenues / $totalRevenues) * 100, 1) }}% of total
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Stats Row --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 border-0 border-start border-success border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Total Transactions</p>
                            <h5 class="mb-0">{{ number_format($revenues->total()) }}</h5>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-file-list-3-line text-success fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 border-0 border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Average Revenue</p>
                            <h5 class="mb-0">
                                {{ $revenues->total() > 0 ? number_format($totalRevenues / $revenues->total(), 2) : '0.00' }}
                                EGP
                            </h5>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-bar-chart-line text-primary fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 border-0 border-start border-warning border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Active Categories</p>
                            <h5 class="mb-0">{{ $activeCategories }}</h5>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-folder-line text-warning fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4 shadow-sm rounded-4">
        <div class="card-body">
            <form method="GET" id="filtersForm" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="title" class="form-control" placeholder="Search by title"
                        value="{{ request('title') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select">
                        <option value="">All Categories</option>
                        <option value="Unit Sale" {{ request('category') == 'Unit Sale' ? 'selected' : '' }}>Unit Sale
                        </option>
                        <option value="Installments" {{ request('category') == 'Installments' ? 'selected' : '' }}>
                            Installments</option>
                        <option value="Commission" {{ request('category') == 'Commission' ? 'selected' : '' }}>Commission
                        </option>
                        <option value="Finishing" {{ request('category') == 'Finishing' ? 'selected' : '' }}>Finishing
                        </option>
                        <option value="Admin Fees" {{ request('category') == 'Admin Fees' ? 'selected' : '' }}>Admin Fees
                        </option>
                        <option value="Late Penalty" {{ request('category') == 'Late Penalty' ? 'selected' : '' }}>Late
                            Penalty</option>
                        <option value="Rent" {{ request('category') == 'Rent' ? 'selected' : '' }}>Rent</option>
                        <option value="Services" {{ request('category') == 'Services' ? 'selected' : '' }}>Services
                        </option>
                        <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="text" name="from" id="filterFromDate" class="form-control flatpickr-date"
                        placeholder="From" value="{{ request('from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="text" name="to" id="filterToDate" class="form-control flatpickr-date"
                        placeholder="To" value="{{ request('to') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Sort By</label>
                    <select name="sort" class="form-select">
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Date ↓</option>
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Date ↑</option>
                        <option value="amount_desc" {{ request('sort') == 'amount_desc' ? 'selected' : '' }}>Amount ↓
                        </option>
                        <option value="amount_asc" {{ request('sort') == 'amount_asc' ? 'selected' : '' }}>Amount ↑
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line"></i> Search
                    </button>
                </div>

                <div class="col-md-2">
                    <button type="button" id="resetFilters" class="btn btn-outline-secondary w-100">
                        <i class="ri-refresh-line"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Revenues Table --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Revenues List</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEditRevenueModal">
                <i class="ri-add-line"></i> Add Revenue
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Received From</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Project</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($revenues as $revenue)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($revenue->date)->format('d M Y') }}</td>
                                <td>{{ $revenue->title }}</td>
                                <td>{{ $revenue->received_from ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $revenue->category }}</span>
                                </td>
                                <td class="fw-bold text-success">{{ number_format($revenue->amount, 2) }} EGP</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $revenue->payment_method == 'cash' ? 'success' : ($revenue->payment_method == 'bank' ? 'primary' : 'warning') }}">
                                        {{ ucfirst($revenue->payment_method) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($revenue->project)
                                        <a href="{{ route('projects.show', $revenue->project_id) }}"
                                            class="text-decoration-none">
                                            {{ $revenue->project->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">General</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary btn-edit-revenue" data-id="{{ $revenue->id }}"
                                        data-title="{{ $revenue->title }}"
                                        data-received_from="{{ $revenue->received_from }}"
                                        data-amount="{{ $revenue->amount }}" data-category="{{ $revenue->category }}"
                                        data-payment="{{ $revenue->payment_method }}"
                                        data-project="{{ $revenue->project_id }}" data-unit="{{ $revenue->unit_id }}"
                                        data-date="{{ $revenue->date }}" data-notes="{{ $revenue->notes }}"
                                        data-reference="{{ $revenue->reference_number }}">
                                        <i class="ri-edit-2-line"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-delete-revenue"
                                        data-id="{{ $revenue->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="ri-file-list-line fs-1 d-block mb-2"></i>
                                    No revenues found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($revenues->hasPages())
                <div class="mt-3">
                    {{ $revenues->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    @include('general-revenues.partials.add-edit-modal')
@endsection

@section('scripts')
    <script src="{{ URL::asset('/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ============= FLATPICKR INITIALIZATION =============
            // Filter dates
            flatpickr('#filterFromDate', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                allowInput: true
            });

            flatpickr('#filterToDate', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                allowInput: true
            });

            // Modal date (will be re-initialized on modal open)
            let modalDatePicker = flatpickr('#revenueDate', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                allowInput: true
            });

            // ============= RESET FILTERS =============
            document.getElementById('resetFilters')?.addEventListener('click', function() {
                window.location.href = window.location.pathname;
            });

            // ============= ADD NEW REVENUE =============
            const addButton = document.querySelector('[data-bs-target="#addEditRevenueModal"]');
            if (addButton) {
                addButton.addEventListener('click', function() {
                    resetForm();
                    document.getElementById('modalTitle').textContent = 'Add General Revenue';
                    document.getElementById('revenueForm').action =
                        '{{ route('general-revenues.store') }}';
                    document.getElementById('formMethod').value = 'POST';

                    // Reset datepicker
                    if (modalDatePicker) {
                        modalDatePicker.clear();
                    }
                });
            }

            // ============= EDIT REVENUE =============
            document.querySelectorAll('.btn-edit-revenue').forEach(btn => {
                btn.addEventListener('click', function() {
                    const revenueId = this.dataset.id;

                    document.getElementById('modalTitle').textContent = 'Edit General Revenue';
                    document.getElementById('revenueForm').action =
                        `/general-revenues/${revenueId}`;
                    document.getElementById('formMethod').value = 'PUT';

                    document.getElementById('revenueTitle').value = this.dataset.title || '';
                    document.getElementById('revenueReceivedFrom').value = this.dataset
                        .received_from || '';
                    document.getElementById('revenueAmount').value = this.dataset.amount || '';
                    document.getElementById('revenueCategory').value = this.dataset.category || '';
                    document.getElementById('revenuePayment').value = this.dataset.payment || '';
                    document.getElementById('revenueProject').value = this.dataset.project || '';
                    document.getElementById('revenueUnit').value = this.dataset.unit || '';
                    document.getElementById('revenueNotes').value = this.dataset.notes || '';
                    document.getElementById('revenueReference').value = this.dataset.reference ||
                        '';

                    // Set date with flatpickr
                    if (modalDatePicker && this.dataset.date) {
                        modalDatePicker.setDate(this.dataset.date);
                    }

                    const modal = new bootstrap.Modal(document.getElementById(
                        'addEditRevenueModal'));
                    modal.show();
                });
            });

            // ============= DELETE REVENUE =============
            document.querySelectorAll('.btn-delete-revenue').forEach(btn => {
                btn.addEventListener('click', function() {
                    const revenueId = this.dataset.id;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This revenue will be permanently deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Deleting...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            fetch(`/general-revenues/${revenueId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Deleted!',
                                            text: data.message ||
                                                'Revenue has been deleted.',
                                            showConfirmButton: false,
                                            timer: 1500
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        throw new Error(data.message ||
                                            'Failed to delete');
                                    }
                                })
                                .catch(error => {
                                    console.error('Delete error:', error);
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: error.message ||
                                            'Failed to delete revenue'
                                    });
                                });
                        }
                    });
                });
            });

            // ============= RESET FORM =============
            function resetForm() {
                document.getElementById('revenueForm').reset();
                document.getElementById('formMethod').value = 'POST';
            }

            // ============= SESSION MESSAGES =============
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}'
                });
            @endif

        });
    </script>
@endsection
