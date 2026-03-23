@extends('layouts.master')

@section('title', 'Salaries')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            HR
        @endslot
        @slot('title')
            Salaries Overview
        @endslot
    @endcomponent

    {{-- Header + Filters --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">

        <h4 class="fw-bold text-primary mb-0">
            <i class="ri-money-dollar-circle-line me-2"></i> Employee Salaries
        </h4>
        <form action="{{ route('salaries.index') }}" method="GET"
            class="d-flex flex-wrap align-items-center gap-2 bg-light border rounded-4 p-3 shadow-sm">

            {{-- 🔍 Search by Employee Name --}}
            <div class="input-group input-group-sm w-auto">
                <span class="input-group-text bg-white"><i class="ri-user-search-line"></i></span>
                <input type="text" name="employee" value="{{ request('employee') }}" class="form-control border-start-0"
                    placeholder="Search by name...">
            </div>

            {{-- 📅 Month --}}
            <select name="month" class="form-select form-select-sm w-auto">
                <option value="">All Months</option>
                @foreach (range(1, 12) as $m)
                    @php $monthName = date('F', mktime(0, 0, 0, $m, 1)); @endphp
                    <option value="{{ $monthName }}" {{ request('month') == $monthName ? 'selected' : '' }}>
                        {{ $monthName }}
                    </option>
                @endforeach
            </select>

            {{-- 🗓️ Year --}}
            <input type="number" name="year" class="form-control form-control-sm w-auto text-center"
                value="{{ request('year') }}" min="2000" max="2100" placeholder="Year">

            {{-- 💰 Status --}}
            <select name="status" class="form-select form-select-sm w-auto">
                <option value="">All Status</option>
                <option value="Paid" {{ request('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            </select>

            {{-- 🔘 Filter + Reset --}}
            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="ri-filter-2-line me-1"></i> Filter
            </button>
            <a href="{{ route('salaries.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="ri-refresh-line me-1"></i> Reset
            </a>
        </form>

        {{-- 🔹 زرار Add + Generate خارج الفورم --}}
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-gradient-primary btn-sm rounded-pill px-3 shadow-sm"
                data-bs-toggle="modal" data-bs-target="#salaryModal">
                <i class="ri-add-line me-1"></i> Add Salary
            </button>

            <form action="{{ route('salaries.generate') }}" method="POST" id="generateSalariesForm">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-sm">
                    <i class="ri-currency-fill me-1"></i> Generate Salaries
                </button>
            </form>
        </div>

    </div>


    {{-- Salary Cards --}}
    <div class="row g-4">
        @forelse ($salaries as $salary)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card salary-card border-0 shadow-lg rounded-4 h-100 hover-lift">
                    <div class="card-header border-0 bg-gradient text-white py-3 text-center rounded-top-4">
                        <h6 class="fw-semibold mb-0">{{ $salary->employee->name ?? 'Unknown' }}</h6>
                        <small><i class="ri-calendar-line me-1"></i>{{ $salary->month }} {{ $salary->year }}</small>
                    </div>

                    <div class="card-body px-4 py-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Basic</span>
                            <span>{{ number_format($salary->basic_salary, 2) }} EGP</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Allowances</span>
                            <span class="text-success">+{{ number_format($salary->allowances, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Deductions</span>
                            <span class="text-danger">-{{ number_format($salary->deductions, 2) }}</span>
                        </div>
                        <hr>
                        <div class="text-center mb-3">
                            <h6 class="fw-semibold text-dark mb-1">Net Salary</h6>
                            <h5 class="fw-bold text-success">{{ number_format($salary->net_salary, 2) }} EGP</h5>
                        </div>
                        <div class="text-center">
                            <span
                                class="badge rounded-pill px-3 py-2 bg-{{ $salary->status == 'Paid' ? 'success' : 'warning' }}">
                                {{ $salary->status }}
                            </span>
                        </div>
                    </div>

                    <div class="card-footer bg-light border-0 text-center small py-3">
                        <i class="ri-time-line me-1 text-muted"></i>
                        {{ $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') : '—' }}

                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <button class="btn btn-outline-primary btn-sm rounded-pill px-3"
                                onclick='openEditModal(@json($salary))'>
                                <i class="ri-edit-2-line"></i>
                            </button>

                            <a href="{{ route('salaries.show', $salary->id) }}"
                                class="btn btn-outline-info btn-sm rounded-pill px-3">
                                <i class="ri-eye-line"></i>
                            </a>

                            <form action="{{ route('salaries.destroy', $salary->id) }}" method="POST"
                                class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="ri-emotion-unhappy-line fs-1 d-block mb-3 text-warning"></i>
                <h6>No salary records found</h6>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-end mt-4">
        {{ $salaries->links() }}
    </div>

    {{-- Include Add + Edit Modals --}}
    @include('salaries.partials.form', ['employees' => $employees])
    @include('salaries.partials.edit')
@endsection

{{-- ✅ Custom Styles --}}
@section('css')
    <style>
        .bg-gradient {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
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

        .salary-card {
            transition: all 0.3s ease;
        }

        .salary-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);
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

        .input-group-text {
            border: none;
            border-radius: 10px 0 0 10px;
        }
    </style>
@endsection

@section('script')
    @include('salaries.partials.script')
@endsection
