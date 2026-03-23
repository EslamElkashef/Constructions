@extends('layouts.master')

@section('content')
    <div class="container">
        <h2>Expenses for Project: {{ $project->name }}</h2>

        <a href="{{ route('projects.expenses.create', $project->id) }}" class="btn btn-primary mb-3">Add New Expense</a>
        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary mb-3 ms-2">Back</a>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Spent By</th>
                    <th>Spent At</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($expenses as $expense)
                    <tr>
                        <td>{{ $expense->id }}</td>
                        <td>{{ $expense->title }}</td>
                        <td class="text-success fw-bold">{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>{{ $expense->user?->name }}</td>
                        <td>{{ $expense->spent_at }}</td>
                        <td class="text-center">
                            <a href="{{ route('projects.expenses.edit', [$project->id, $expense->id]) }}"
                                class="btn btn-sm btn-warning">
                                <i class="ri-edit-line"></i> Edit
                            </a>

                            <form action="{{ route('projects.expenses.destroy', [$project->id, $expense->id]) }}"
                                method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger delete-item">
                                    <i class="ri-delete-bin-line"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No expenses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-3">
            <h4>
                Total Expenses: <span class="text-success">{{ number_format($total, 2) }}</span> <br>
                Budget: <span class="text-primary">{{ number_format($project->budget, 2) }}</span>
            </h4>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // نفس الكود بتاع SweetAlert اللي عندك
            document.querySelectorAll(".delete-form").forEach(form => {
                form.addEventListener("submit", function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This expense will be deleted permanently!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endpush
