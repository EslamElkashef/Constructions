<!-- Add Salary Modal -->
<div class="modal fade" id="salaryModal" tabindex="-1" aria-labelledby="salaryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 shadow-lg border-0">
            <div class="modal-header bg-gradient text-white rounded-top-4">
                <h5 class="modal-title fw-semibold" id="salaryModalLabel">
                    <i class="ri-money-dollar-circle-line me-2"></i> Add Salary
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="salaryForm" method="POST" action="{{ route('salaries.store') }}">
                @csrf
                <input type="hidden" name="_method" value="POST">

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Employee</label>
                            <select name="employee_id" class="form-select" required>
                                <option value="">Select Employee</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Month</label>
                            <select name="month" class="form-select" required>
                                <option value="">Select Month</option>
                                @foreach (range(1, 12) as $m)
                                    @php $monthName = date('F', mktime(0, 0, 0, $m, 1)); @endphp
                                    <option value="{{ $monthName }}">{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Year</label>
                            <input type="number" name="year" class="form-control text-center"
                                value="{{ date('Y') }}" min="2000" max="2100" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Basic Salary</label>
                            <input type="number" step="0.01" name="basic_salary" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Allowances</label>
                            <input type="number" step="0.01" name="allowances" class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Deductions</label>
                            <input type="number" step="0.01" name="deductions" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Allowance Reason</label>
                            <input type="text" name="allowance_reason" class="form-control"
                                placeholder="Reason for allowance (optional)">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Deduction Reason</label>
                            <input type="text" name="deduction_reason" class="form-control"
                                placeholder="Reason for deduction (optional)">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Payment Date</label>
                            <input type="text" name="payment_date" id="payment_date" class="form-control flatpickr"
                                placeholder="Select Date">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="Pending">Pending</option>
                                <option value="Paid">Paid</option>
                            </select>
                        </div>

                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary px-4"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="ri-save-3-line me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
