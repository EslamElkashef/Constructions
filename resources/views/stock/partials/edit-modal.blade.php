<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm" method="POST" action="">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_product_id">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">SKU</label>
                        <input id="edit_sku" name="sku" class="form-control" type="text">
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Name</label>
                        <input id="edit_name" name="name" class="form-control" type="text" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Description</label>
                        <textarea id="edit_description" name="description" class="form-control"></textarea>
                    </div>

                    <div class="row g-2">
                        <div class="col">
                            <label class="form-label">Cost Price</label>
                            <input id="edit_cost_price" name="cost_price" class="form-control" type="number"
                                step="0.01">
                        </div>
                        <div class="col">
                            <label class="form-label">Sell Price</label>
                            <input id="edit_sell_price" name="sell_price" class="form-control" type="number"
                                step="0.01">
                        </div>
                    </div>

                    <div class="mt-2">
                        <label class="form-label">Low Stock Threshold</label>
                        <input id="edit_low_stock_threshold" name="low_stock_threshold" class="form-control"
                            type="number" step="0.01">
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
