@extends('layouts.master')

@section('title', 'Expense Categories')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Accounting
        @endslot
        @slot('title')
            Expense Categories
        @endslot
    @endcomponent

    <div class="card shadow-sm rounded-4">
        <div class="card-header d-flex justify-content-between">
            <h5 class="mb-0">Categories</h5>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#categoryModal">
                + Add Category
            </button>
        </div>

        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th width="120">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="{{ $category->id }}">
                                    <i class="ri-edit-line"></i>
                                </button>

                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $category->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $categories->links() }}
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="categoryModal">
        <div class="modal-dialog">
            <form method="POST" id="categoryForm">
                @csrf
                <input type="hidden" name="_method" id="method">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Category</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ADD
            document.querySelector('[data-bs-target="#categoryModal"]').onclick = () => {
                let form = document.getElementById('categoryForm');
                form.action = '/general-expense-categories';
                document.getElementById('method').value = '';
                form.reset();
            };

            // EDIT
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.onclick = () => {
                    fetch(`/general-expense-categories/${btn.dataset.id}/edit`)
                        .then(res => res.json())
                        .then(data => {
                            let form = document.getElementById('categoryForm');
                            form.action = `/general-expense-categories/${data.id}`;
                            document.getElementById('method').value = 'PUT';
                            form.name.value = data.name;
                            new bootstrap.Modal('#categoryModal').show();
                        });
                };
            });

            // DELETE
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.onclick = () => {
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        showCancelButton: true,
                    }).then(result => {
                        if (result.isConfirmed) {
                            fetch(`/general-expense-categories/${btn.dataset.id}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (!data.success) {
                                        Swal.fire('Error', data.message, 'error');
                                    } else {
                                        location.reload();
                                    }
                                });
                        }
                    });
                };
            });

        });
    </script>
@endsection
