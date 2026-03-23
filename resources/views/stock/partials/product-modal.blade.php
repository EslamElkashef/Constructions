<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('stock.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">SKU</label>
                        <input name="sku" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Name</label>
                        <input name="name" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="row g-2">
                        <div class="col">
                            <label class="form-label">Cost Price</label>
                            <input name="cost_price" class="form-control" type="number" step="0.01">
                        </div>
                        <div class="col">
                            <label class="form-label">Sell Price</label>
                            <input name="sell_price" class="form-control" type="number" step="0.01">
                        </div>
                        <div class="col">
                            <label class="form-label">Initial Qty</label>
                            <input name="quantity" class="form-control" type="number" step="0.01">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="form-label">Low stock threshold</label>
                        <input name="low_stock_threshold" class="form-control" type="number" step="0.01">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
