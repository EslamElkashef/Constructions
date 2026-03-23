<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Flatpickr -->
<link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // ✅ Initialize flatpickr
    document.addEventListener('DOMContentLoaded', function() {
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "F j, Y",
            allowInput: true
        });
    });

    // ✅ Open Edit Modal and fill data
    function openEditModal(salary) {
        const modal = new bootstrap.Modal(document.getElementById('editSalaryModal'));
        modal.show();

        document.getElementById('edit_salary_id').value = salary.id;
        document.getElementById('edit_employee_id').value = salary.employee_id;
        document.getElementById('edit_month').value = salary.month;
        document.getElementById('edit_year').value = salary.year;
        document.getElementById('edit_basic_salary').value = salary.basic_salary;
        document.getElementById('edit_allowances').value = salary.allowances;
        document.getElementById('edit_allowance_reason').value = salary.allowance_reason ?? '';
        document.getElementById('edit_deductions').value = salary.deductions;
        document.getElementById('edit_deduction_reason').value = salary.deduction_reason ?? '';
        document.getElementById('edit_status').value = salary.status;
        document.getElementById('editSalaryForm').action = `/salaries/${salary.id}`;

        if (salary.payment_date) {
            document.getElementById('edit_payment_date')._flatpickr.setDate(salary.payment_date, true);
        } else {
            document.getElementById('edit_payment_date').value = '';
        }
    }

    // ✅ SweetAlert Delete confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This will delete the salary record permanently!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // ✅ SweetAlert success & error
    @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#4f46e5',
            timer: 2000,
            showConfirmButton: false
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#dc3545'
        });
    @endif
</script>
<script>
    document.getElementById("generateSalariesForm").addEventListener("submit", function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Generate Salaries?",
            text: "This will create salary records for all ACTIVE employees for {{ date('F Y') }}.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, generate",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#10B981",
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
</script>
