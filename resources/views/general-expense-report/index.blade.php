@extends('layouts.master')

@section('title', 'General Expenses Report')

@section('css')
    <link href="{{ URL::asset('/assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet">
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Accounting
        @endslot
        @slot('title')
            Expenses Report
        @endslot
    @endcomponent

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" name="from" class="form-control flatpickr-date" placeholder="From"
                        value="{{ $from }}">
                </div>
                <div class="col-md-3">
                    <input type="text" name="to" class="form-control flatpickr-date" placeholder="To"
                        value="{{ $to }}">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        {{-- Table --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-header">
                    <h5 class="mb-0">Expenses by Category</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th class="text-end">Amount (EGP)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($report as $category => $amount)
                                <tr>
                                    <td>{{ $category }}</td>
                                    <td class="text-end fw-bold">{{ number_format($amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pie Chart --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-header">
                    <h5 class="mb-0">Expenses Distribution</h5>
                </div>
                <div class="card-body">
                    <canvas id="expensesChart" style="height:400px;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ URL::asset('/assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        flatpickr(".flatpickr-date", {
            dateFormat: "Y-m-d"
        });

        // Prepare data for chart
        const labels = @json(array_keys($report->toArray()));
        const data = @json(array_values($report->toArray()));

        const ctx = document.getElementById('expensesChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Expenses by Category',
                    data: data,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#8BC34A', '#FF9800',
                        '#9C27B0', '#00BCD4', '#E91E63', '#795548', '#607D8B'
                    ],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
@endsection
