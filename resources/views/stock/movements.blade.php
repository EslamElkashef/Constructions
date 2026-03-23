{{-- resources/views/inventory/movements.blade.php --}}

@extends('layouts.master')

@section('title', 'Stock Movements')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Inventory
        @endslot
        @slot('title')
            Movements
        @endslot
    @endcomponent

    <h4>{{ $product->name }} — Movements</h4>

    {{-- Flatpickr CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    {{-- Filter Form --}}
    <form method="GET" class="row g-3 mb-4">
        {{-- Filter by Type --}}
        <div class="col-md-3">
            <label class="form-label">Type</label>
            <select name="type" class="form-select">
                <option value="">All Types</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>IN</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>OUT</option>
            </select>
        </div>

        {{-- Filter by User --}}
        <div class="col-md-3">
            <label class="form-label">User</label>
            <input type="text" name="user" value="{{ request('user') }}" class="form-control" placeholder="User Name">
        </div>

        {{-- Filter by From Date --}}
        <div class="col-md-3">
            <label class="form-label">From Date</label>
            <input type="text" name="from_date" value="{{ request('from_date') }}" class="form-control"
                placeholder="From Date">
        </div>

        {{-- Filter by To Date --}}
        <div class="col-md-3">
            <label class="form-label">To Date</label>
            <input type="text" name="to_date" value="{{ request('to_date') }}" class="form-control"
                placeholder="To Date">
        </div>

        {{-- Buttons --}}
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('stock.movements', $product->id) }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Type</th>
                    <th>Qty</th>
                    <th>Before</th>
                    <th>After</th>
                    <th>Reason</th>
                    <th>Reference</th>
                    <th>User</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movements as $m)
                    <tr>
                        <td>{{ $m->id }}</td>
                        <td>
                            <span class="badge {{ $m->type == 'in' ? 'bg-success' : 'bg-danger' }}">
                                {{ strtoupper($m->type) }}
                            </span>
                        </td>
                        <td>{{ $m->quantity }}</td>
                        <td>{{ $m->before_quantity }}</td>
                        <td>{{ $m->after_quantity }}</td>
                        <td>{{ $m->reason }}</td>
                        <td>{{ $m->reference }}</td>
                        <td>{{ $m->user?->name ?? '-' }}</td>
                        <td>{{ $m->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No movements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    {{ $movements->withQueryString()->links() }}

    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('input[name="from_date"]', {
                dateFormat: "Y-m-d"
            });
            flatpickr('input[name="to_date"]', {
                dateFormat: "Y-m-d"
            });
        });
    </script>
@endsection
