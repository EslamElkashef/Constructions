<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- مودال التعديل -->
<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-gradient text-white rounded-top-4">
                <h5 class="modal-title">Adjust Stock — <span id="adj_product_name"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="adjustForm">
                    @csrf
                    <input type="hidden" id="adj_product_id" name="product_id">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Type</label><br>
                        <label class="me-3"><input type="radio" name="type" value="in" checked> Stock
                            In</label>
                        <label><input type="radio" name="type" value="out"> Stock Out</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Quantity</label>
                        <input id="adj_quantity" name="quantity" type="number" step="0.01" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reason</label>
                        <input id="adj_reason" name="reason" type="text" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Reference</label>
                        <input id="adj_reference" name="reference" type="text" class="form-control">
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-gradient-primary" onclick="submitAdjust()">Apply</button>
            </div>
        </div>
    </div>
</div>
<script>
    function openAdjustModal(product) {
        document.getElementById('adj_product_id').value = product.id;
        document.getElementById('adj_product_name').textContent = product.name;
        document.getElementById('adjustForm').reset();

        const modal = new bootstrap.Modal(document.getElementById('adjustModal'));
        modal.show();
    }

    function submitAdjust() {
        const productId = document.getElementById('adj_product_id').value;
        const type = document.querySelector('#adjustModal input[name="type"]:checked').value;
        const qty = document.getElementById('adj_quantity').value;
        const reason = document.getElementById('adj_reason').value;
        const reference = document.getElementById('adj_reference').value;

        if (!productId || !qty) return alert('Please fill in the quantity.');

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/stock/${productId}/adjust`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    type,
                    quantity: qty,
                    reason,
                    reference
                })
            })
            .then(res => res.json())
            .then(data => {
                const modalEl = document.getElementById('adjustModal');
                const modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                // تنظيف يدوي في حالة وجود Backdrop متبقي
                setTimeout(() => {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                }, 300);

                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Stock updated.');
                    location.reload();
                }
            })
            .catch(e => {
                console.error(e);
                alert('Error adjusting stock.');
            });
    }

    // تنظيف تلقائي عند إلغاء المودال
    document.addEventListener('hidden.bs.modal', function(event) {
        if (event.target.id === 'adjustModal') {
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
        }
    });
</script>
<style>
    .btn-gradient-primary {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
        color: #fff;
        border: none;
        transition: 0.3s;
    }

    .btn-gradient-primary:hover {
        background: linear-gradient(135deg, #4338ca, #2563eb);
        transform: scale(1.05);
    }

    .modal-content {
        border-radius: 1rem;
    }

    .bg-gradient {
        background: linear-gradient(135deg, #4f46e5, #3b82f6);
    }
</style>
