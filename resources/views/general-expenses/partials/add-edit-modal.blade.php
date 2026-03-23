<div class="modal fade" id="addEditExpenseModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="expenseForm" method="POST" action="{{ route('general-expenses.store') }}">
            @csrf
            <input type="hidden" name="_method" value="POST" id="formMethod">

            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add General Expense</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="expenseTitle" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="expenseCategory" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="expenseAmount" class="form-control"
                            required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" id="expenseDate" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="expensePayment" class="form-select" required>
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Project</label>
                        <select name="project_id" id="expenseProject" class="form-select">
                            <option value="">General (Not linked to any project)</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="expenseNotes" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Save Expense
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
