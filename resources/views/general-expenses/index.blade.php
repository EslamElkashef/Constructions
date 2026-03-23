@extends('layouts.master')

@section('title', 'General Expenses')

@section('css')
    <link href="{{ URL::asset('/assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

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
            General Expenses
        @endslot
    @endcomponent

    {{-- KPIs --}}
    <div class="row g-3 mb-4">
        {{-- Total Expenses --}}
        <div class="col-md-3">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-muted mb-1">Total Expenses</h6>
                            <h4 class="fw-bold mb-0">{{ number_format($totalExpenses, 2) }}</h4>
                            <small class="text-muted">EGP</small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-primary text-primary rounded-3">
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
                            <h4 class="fw-bold mb-0">{{ number_format($thisMonthExpenses, 2) }}</h4>
                            <small class="text-muted">EGP</small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-success text-success rounded-3">
                                <i class="ri-calendar-line fs-3"></i>
                            </span>
                        </div>
                    </div>
                    @if ($totalExpenses > 0)
                        <div class="mt-2">
                            <span class="badge bg-soft-success text-success">
                                {{ number_format(($thisMonthExpenses / $totalExpenses) * 100, 1) }}% of total
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
                            <h4 class="fw-bold mb-0">{{ number_format($thisWeekExpenses, 2) }}</h4>
                            <small class="text-muted">EGP</small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-warning text-warning rounded-3">
                                <i class="ri-calendar-2-line fs-3"></i>
                            </span>
                        </div>
                    </div>
                    @if ($totalExpenses > 0)
                        <div class="mt-2">
                            <span class="badge bg-soft-warning text-warning">
                                {{ number_format(($thisWeekExpenses / $totalExpenses) * 100, 1) }}% of total
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
                            <h4 class="fw-bold mb-0">{{ number_format($todayExpenses, 2) }}</h4>
                            <small class="text-muted">EGP</small>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-soft-info text-info rounded-3">
                                <i class="ri-calendar-check-line fs-3"></i>
                            </span>
                        </div>
                    </div>
                    @if ($totalExpenses > 0)
                        <div class="mt-2">
                            <span class="badge bg-soft-info text-info">
                                {{ number_format(($todayExpenses / $totalExpenses) * 100, 1) }}% of total
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Additional Stats Row (Optional) --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 border-0 border-start border-primary border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Total Transactions</p>
                            <h5 class="mb-0">{{ number_format($expenses->total()) }}</h5>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-file-list-3-line text-primary fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm rounded-4 border-0 border-start border-success border-3">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <p class="text-muted mb-1">Average Expense</p>
                            <h5 class="mb-0">
                                {{ $expenses->total() > 0 ? number_format($totalExpenses / $expenses->total(), 2) : '0.00' }}
                                EGP
                            </h5>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ri-bar-chart-line text-success fs-2"></i>
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
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">From</label>
                    <input type="text" name="from" class="form-control flatpickr-date" placeholder="From"
                        value="{{ request('from') }}">
                </div>

                <div class="col-md-2">
                    <label class="form-label">To</label>
                    <input type="text" name="to" class="form-control flatpickr-date" placeholder="To"
                        value="{{ request('to') }}">
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

    {{-- Expenses Table --}}
    <div class="card shadow-sm rounded-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Expenses List</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEditExpenseModal">
                <i class="ri-add-line"></i> Add Expense
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Project</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $expense)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</td>
                                <td>{{ $expense->title }}</td>
                                <td>
                                    @if ($expense->category)
                                        <span class="badge bg-info">{{ $expense->category->name }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-danger">{{ number_format($expense->amount, 2) }} EGP</td>
                                <td>
                                    @if ($expense->payment_method)
                                        <span
                                            class="badge bg-{{ $expense->payment_method == 'cash' ? 'success' : ($expense->payment_method == 'bank' ? 'primary' : 'warning') }}">
                                            {{ ucfirst($expense->payment_method) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($expense->project)
                                        <a href="{{ route('projects.show', $expense->project_id) }}"
                                            class="text-decoration-none">
                                            {{ $expense->project->title }}
                                        </a>
                                    @else
                                        <span class="text-muted">General</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary btn-edit-expense" data-id="{{ $expense->id }}"
                                        data-title="{{ $expense->title }}" data-amount="{{ $expense->amount }}"
                                        data-category="{{ $expense->category_id }}"
                                        data-payment="{{ $expense->payment_method }}"
                                        data-project="{{ $expense->project_id }}" data-notes="{{ $expense->notes }}"
                                        data-date="{{ $expense->expense_date }}">
                                        <i class="ri-edit-2-line"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-delete-expense"
                                        data-id="{{ $expense->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="ri-file-list-line fs-1 d-block mb-2"></i>
                                    No expenses found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($expenses->hasPages())
                <div class="mt-3">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    @include('general-expenses.partials.add-edit-modal', [
        'categories' => $categories,
        'projects' => $projects ?? [],
    ])
@endsection

@section('scripts')
    <script src="{{ URL::asset('/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Flatpickr dates
            flatpickr('.flatpickr-date', {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'd M Y',
                allowInput: true
            });

            // Month picker
            flatpickr('.flatpickr-month', {
                plugins: [new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: 'Y-m',
                    altFormat: 'F Y'
                })]
            });

            // Reset filters
            document.getElementById('resetFilters')?.addEventListener('click', function() {
                window.location.href = window.location.pathname;
            });

            // ============= ADD NEW EXPENSE =============
            const addButton = document.querySelector('[data-bs-target="#addEditExpenseModal"]');
            if (addButton) {
                addButton.addEventListener('click', function() {
                    resetForm();
                    document.getElementById('modalTitle').textContent = 'Add General Expense';
                    document.getElementById('expenseForm').action =
                        '{{ route('general-expenses.store') }}';
                    document.getElementById('formMethod').value = 'POST';
                });
            }

            // ============= EDIT EXPENSE =============
            document.querySelectorAll('.btn-edit-expense').forEach(btn => {
                btn.addEventListener('click', function() {
                    const expenseId = this.dataset.id;

                    // Update modal title and form action
                    document.getElementById('modalTitle').textContent = 'Edit General Expense';
                    document.getElementById('expenseForm').action =
                        `/general-expenses/${expenseId}`;
                    document.getElementById('formMethod').value = 'PUT';

                    // Fill form data
                    document.getElementById('expenseTitle').value = this.dataset.title || '';
                    document.getElementById('expenseAmount').value = this.dataset.amount || '';
                    document.getElementById('expenseCategory').value = this.dataset.category || '';
                    document.getElementById('expenseProject').value = this.dataset.project || '';
                    document.getElementById('expensePayment').value = this.dataset.payment || '';
                    document.getElementById('expenseNotes').value = this.dataset.notes || '';
                    document.getElementById('expenseDate').value = this.dataset.date || '';

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById(
                        'addEditExpenseModal'));
                    modal.show();
                });
            });

            // ============= DELETE EXPENSE =============
            document.querySelectorAll('.btn-delete-expense').forEach(btn => {
                btn.addEventListener('click', function() {
                    const expenseId = this.dataset.id;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This expense will be permanently deleted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading
                            Swal.fire({
                                title: 'Deleting...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            // Send DELETE request
                            fetch(`/general-expenses/${expenseId}`, {
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
                                                'Expense has been deleted.',
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
                                            'Failed to delete expense'
                                    });
                                });
                        }
                    });
                });
            });

            // ============= RESET FORM =============
            function resetForm() {
                document.getElementById('expenseForm').reset();
                document.getElementById('formMethod').value = 'POST';
                document.getElementById('expenseTitle').value = '';
                document.getElementById('expenseAmount').value = '';
                document.getElementById('expenseCategory').value = '';
                document.getElementById('expenseProject').value = '';
                document.getElementById('expensePayment').value = '';
                document.getElementById('expenseNotes').value = '';
                document.getElementById('expenseDate').value = '';
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

            // ============= FORM VALIDATION =============
            document.getElementById('expenseForm').addEventListener('submit', function(e) {
                const amount = parseFloat(document.getElementById('expenseAmount').value);
                if (amount <= 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Amount',
                        text: 'Amount must be greater than zero'
                    });
                }
            });

        });
    </script>
@endsection
