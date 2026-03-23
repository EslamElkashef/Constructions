<div class="modal-header">
    <h5 class="modal-title fw-semibold">Add New Employee</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<form id="createEmployeeForm" method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="modal-body">
        <div class="row g-3">
            {{-- Name --}}
            <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Enter name" required>
            </div>

            {{-- Email --}}
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
            </div>

            {{-- Phone --}}
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" placeholder="+201000000000"required>
            </div>

            {{-- Position --}}
            <div class="col-md-6">
                <label class="form-label">Position</label>
                <input type="text" name="position" class="form-control" placeholder="Job Title"required>
            </div>

            {{-- Salary --}}
            <div class="col-md-6">
                <label class="form-label">Salary (EGP)</label>
                <input type="number" name="salary" class="form-control" placeholder="Enter salary">
            </div>

            {{-- Start Date --}}
            <div class="col-md-6">
                <label class="form-label">Start Date</label>
                <input type="text" name="start_date" class="form-control flatpickr" placeholder="Select start date">
            </div>

            {{-- Address --}}
            <div class="col-md-12">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" placeholder="Enter address" required>
            </div>

            {{-- Personal Image --}}
            <div class="col-md-6">
                <label class="form-label">Personal Image</label>
                <input type="file" name="personal_image" class="form-control">
            </div>

            {{-- National ID --}}
            <div class="col-md-6">
                <label class="form-label">National ID</label>
                <input type="text" name="national_id" class="form-control" placeholder="Enter national ID"required>
            </div>

            {{-- National ID Image --}}
            <div class="col-md-6">
                <label class="form-label">National ID Image</label>
                <input type="file" name="national_id_image" class="form-control">
            </div>

            {{-- Birthday --}}
            <div class="col-md-6">
                <label class="form-label">Birthday</label>
                <input type="text" name="birthday" class="form-control flatpickr"
                    placeholder="Select birthday"required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Status</label>
            <select name="status" class="form-select">
                <option value="pending">Pending</option>
                <option value="active">Active</option>
                <option value="terminated">Terminated</option>
                <option value="resigned">Resigned</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Reason (Optional)</label>
            <textarea name="status_reason" class="form-control" rows="2" placeholder="Status Reason If Any..."></textarea>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success">Save Employee</button>
    </div>
</form>

{{-- Scripts --}}
@section('script')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d'
            });

            const form = document.getElementById('createEmployeeForm');

            if (form) {
                const handleSubmit = function(e) {
                    e.preventDefault(); // منع الإرسال الافتراضي مؤقتًا

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to save this employee?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, save it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // إزالة الـ listener علشان ميدخلش تاني في SweetAlert loop
                            form.removeEventListener('submit', handleSubmit);
                            form.submit(); // إرسال الفورم فعليًا للسيرفر
                        }
                    });
                };

                form.addEventListener('submit', handleSubmit);
            }
        });
    </script>
@endsection
