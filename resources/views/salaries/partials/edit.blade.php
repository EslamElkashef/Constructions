<!-- Edit Salary Modal -->
<div class="modal fade" id="editSalaryModal" tabindex="-1" aria-labelledby="editSalaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-gradient text-white rounded-top-4">
                <h5 class="modal-title fw-semibold" id="editSalaryModalLabel">
                    <i class="ri-edit-2-line me-2"></i> Edit Salary
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <form id="editSalaryForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="salary_id" id="edit_salary_id">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Employee</label>
                            <select name="employee_id" id="edit_employee_id" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Month</label>
                            <select name="month" id="edit_month" class="form-select" required>
                                @foreach (range(1, 12) as $m)
                                    @php $monthName = date('F', mktime(0, 0, 0, $m, 1)); @endphp
                                    <option value="{{ $monthName }}">{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Year</label>
                            <input type="number" name="year" id="edit_year" class="form-control text-center"
                                min="2000" max="2100" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Basic Salary</label>
                            <input type="number" name="basic_salary" id="edit_basic_salary" step="0.01"
                                class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Allowances</label>
                            <input type="number" name="allowances" id="edit_allowances" step="0.01"
                                class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Deductions</label>
                            <input type="number" name="deductions" id="edit_deductions" step="0.01"
                                class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Allowance Reason</label>
                            <input type="text" name="allowance_reason" id="edit_allowance_reason"
                                class="form-control" placeholder="Reason for allowance (optional)">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Deduction Reason</label>
                            <input type="text" name="deduction_reason" id="edit_deduction_reason"
                                class="form-control" placeholder="Reason for deduction (optional)">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="text" name="payment_date" id="edit_payment_date"
                                class="form-control flatpickr">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" id="edit_status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary px-4"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ri-save-3-line me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('script')
    @include('salaries.partials.script')
@endsection
