@extends('layouts.master')

@section('title', 'Salary Details')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            HR
        @endslot
        @slot('title')
            Salary Details
        @endslot
    @endcomponent

    <div class="card shadow-lg border-0 rounded-4 p-4">
        <h4 class="fw-bold mb-3 text-primary">
            <i class="ri-user-line me-2"></i> {{ $salary->employee->name }}
        </h4>

        <div class="row mb-2">
            <div class="col-md-4">
                <p><strong>Month:</strong> {{ $salary->month }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>Year:</strong> {{ $salary->year }}</p>
            </div>
            <div class="col-md-4">
                <p><strong>Status:</strong>
                    <span class="badge bg-{{ $salary->status == 'Paid' ? 'success' : 'warning' }}">
                        {{ $salary->status }}
                    </span>
                </p>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-6">
                <p><strong>Basic Salary:</strong> {{ number_format($salary->basic_salary, 2) }} EGP</p>
            </div>
            <div class="col-md-6">
                <p><strong>Net Salary:</strong>
                    <span class="text-success fw-semibold">{{ number_format($salary->net_salary, 2) }} EGP</span>
                </p>
            </div>

            <div class="col-md-6">
                <p><strong>Allowances:</strong>
                    +{{ number_format($salary->allowances, 2) }} EGP
                    @if ($salary->allowance_reason)
                        <br><small class="text-muted"><strong>Reason:</strong> {{ $salary->allowance_reason }}</small>
                    @endif
                </p>
            </div>

            <div class="col-md-6">
                <p><strong>Deductions:</strong>
                    -{{ number_format($salary->deductions, 2) }} EGP
                    @if ($salary->deduction_reason)
                        <br><small class="text-muted"><strong>Reason:</strong> {{ $salary->deduction_reason }}</small>
                    @endif
                </p>
            </div>

            <div class="col-md-6">
                <p><strong>Payment Date:</strong>
                    {{ $salary->payment_date ? \Carbon\Carbon::parse($salary->payment_date)->format('d M Y') : '—' }}
                </p>
            </div>

            <div class="col-md-6">
                <p><strong>Created At:</strong>
                    {{ $salary->created_at->format('d M Y, h:i A') }}
                </p>
            </div>
        </div>

        <hr>

        <a href="{{ route('salaries.index') }}" class="btn btn-outline-primary mt-3">
            <i class="ri-arrow-left-line me-1"></i> Back
        </a>
    </div>
@endsection
