<div class="col-lg-12">
    <div class="card" id="expensesList">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Expenses for {{ $project->title }}</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createExpenseModal">
                <i class="ri-add-line"></i> Add Expense
            </button>
        </div>

        <div class="card-body">
            @if ($project->generalExpenses->count())
                <div class="table-responsive mb-3">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Spent By</th>
                                <th>Spent At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($project->generalExpenses as $expense)
                                <tr>
                                    <td>{{ $expense->title }}</td>
                                    <td>{{ $expense->category->name ?? '-' }}</td>
                                    <td>{{ number_format($expense->amount, 2) }}</td>
                                    <td>{{ $expense->notes ?? '-' }}</td>
                                    <td>{{ $expense->user?->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->expense_date)->format('Y-m-d') }}</td>
                                    <td class="text-nowrap">
                                        <button class="btn btn-sm btn-warning editExpenseBtn"
                                            data-id="{{ $expense->id }}" data-title="{{ $expense->title }}"
                                            data-amount="{{ $expense->amount }}" data-notes="{{ e($expense->notes) }}"
                                            data-user_id="{{ $expense->created_by }}"
                                            data-category_id="{{ $expense->category_id }}"
                                            data-expense_date="{{ $expense->expense_date }}" data-bs-toggle="modal"
                                            data-bs-target="#editExpenseModal">
                                            <i class="ri-edit-2-line"></i> Edit
                                        </button>

                                        <form
                                            action="{{ route('projects.expenses.destroy', [$project->id, $expense->id]) }}"
                                            method="POST" class="d-inline deleteExpenseForm">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger deleteExpenseBtn">
                                                <i class="ri-delete-bin-5-line"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <strong>Total Expenses:</strong>
                    {{ number_format($project->generalExpenses->sum('amount'), 2) }}<br>
                    <strong>Budget:</strong> {{ number_format($project->budget, 2) }}<br>
                    <strong>Remaining:</strong>
                    {{ number_format($project->budget - $project->generalExpenses->sum('amount'), 2) }}
                </div>
            @else
                <p class="text-muted">No expenses recorded for this project.</p>
            @endif
        </div>
    </div>
</div>

{{-- Create Expense Modal --}}
<div class="modal fade" id="createExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {{-- ✅ عدّل الـ action --}}
            <form action="{{ route('projects.expenses.store', $project->id) }}" method="POST">
                @csrf
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" class="form-control" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expense Date</label>
                        <input type="date" name="expense_date" class="form-control"
                            value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button>
                    <button class="btn btn-success" type="submit">Add Expense</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Expense Modal --}}
<div class="modal fade" id="editExpenseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {{-- ✅ سيتم تعيين الـ action ديناميكياً من JavaScript --}}
            <form id="editExpenseForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="editExpenseTitle" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" name="amount" id="editExpenseAmount" class="form-control"
                            step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="editExpenseCategory" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="editExpenseNotes" class="form-control"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expense Date</label>
                        <input type="date" name="expense_date" id="editExpenseDate" class="form-control"
                            required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@push('@script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const projectId = "{{ $project->id }}";

            // Edit modal fill data
            const editForm = document.getElementById('editExpenseForm');
            const editButtons = document.querySelectorAll('.editExpenseBtn');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const expenseId = this.dataset.id;

                    // ✅ تعيين الـ action بشكل صحيح
                    editForm.action = `/projects/${projectId}/expenses/${expenseId}`;

                    document.getElementById('editExpenseTitle').value = this.dataset.title || '';
                    document.getElementById('editExpenseAmount').value = this.dataset.amount || '';
                    document.getElementById('editExpenseNotes').value = this.dataset.notes || '';
                    document.getElementById('editExpenseCategory').value = this.dataset
                        .category_id || '';
                    document.getElementById('editExpenseDate').value = this.dataset.expense_date ||
                        '';
                });
            });

            // Delete confirmation
            document.querySelectorAll('.deleteExpenseBtn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This expense will be permanently deleted!",
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

            // Success/Error Messages
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
@endpush
