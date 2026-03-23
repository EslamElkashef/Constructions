<div class="modal fade" id="addEditRevenueModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form id="revenueForm" method="POST" action="{{ route('general-revenues.store') }}">
            @csrf
            <input type="hidden" name="_method" value="POST" id="formMethod">

            <div class="modal-content rounded-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add General Revenue</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="revenueTitle" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Received From</label>
                        <input type="text" name="received_from" id="revenueReceivedFrom" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Amount <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="amount" id="revenueAmount" class="form-control"
                            required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" id="revenueCategory" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="Unit Sale">Unit Sale</option>
                            <option value="Installments">Installments</option>
                            <option value="Commission">Commission</option>
                            <option value="Finishing">Finishing</option>
                            <option value="Admin Fees">Admin Fees</option>
                            <option value="Late Penalty">Late Penalty</option>
                            <option value="Rent">Rent</option>
                            <option value="Services">Services</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" id="revenueDate" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" id="revenuePayment" class="form-select" required>
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="wallet">Wallet</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Project</label>
                        <select name="project_id" id="revenueProject" class="form-select">
                            <option value="">Select Project (Optional)</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Unit</label>
                        <select name="unit_id" id="revenueUnit" class="form-select">
                            <option value="">Select Unit (Optional)</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name ?? 'Unit #' . $unit->id }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Reference Number</label>
                        <input type="text" name="reference_number" id="revenueReference" class="form-control"
                            placeholder="Invoice/Receipt #">
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="revenueNotes" class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ri-save-line me-1"></i> Save Revenue
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
