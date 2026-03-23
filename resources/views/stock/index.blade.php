{{-- resources/views/stock/index.blade.php --}}

@extends('layouts.master')

@section('title', 'Stock')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Inventory
        @endslot
        @slot('title')
            Stock
        @endslot
    @endcomponent

    {{-- Filter & Add Product --}}
    <div class="d-flex align-items-center mb-3 gap-2">

        {{-- Filters Form --}}
        <form method="GET" action="{{ route('stock.index') }}" class="d-flex align-items-center gap-2 flex-grow-1">

            {{-- Search --}}
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm"
                placeholder="Search product or SKU" style="min-width: 200px;">

            {{-- Stock Status --}}
            <select name="status" class="form-select form-select-sm" style="width: 140px;">
                <option value="">All Stock</option>
                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                <option value="ok" {{ request('status') == 'ok' ? 'selected' : '' }}>Sufficient</option>
            </select>

            {{-- Min Price --}}
            <input type="number" name="min_price" value="{{ request('min_price') }}" class="form-control form-control-sm"
                placeholder="Min Price" min="0" style="width: 100px;">

            {{-- Max Price --}}
            <input type="number" name="max_price" value="{{ request('max_price') }}" class="form-control form-control-sm"
                placeholder="Max Price" min="0" style="width: 100px;">

            {{-- Filter & Reset Buttons --}}
            <button type="submit" class="btn btn-sm btn-primary">Search</button>
            <a href="{{ route('stock.index') }}" class="btn btn-sm btn-secondary">Reset</a>
        </form>

        {{-- Add Product --}}
        <button class="btn btn-sm btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#productModal">
            + Add Product
        </button>
    </div>

    {{-- Products Grid --}}
    <div class="row g-3">
        @foreach ($products as $product)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">
                            {{ $product->name }}
                            <small class="text-muted">({{ $product->sku }})</small>
                        </h6>
                        <p class="mb-1 small text-muted">{{ Str::limit($product->description, 100) }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <div>Qty:
                                    <strong>{{ rtrim(rtrim(number_format($product->quantity, 2, '.', ' '), '0'), '.') }}</strong>
                                </div>
                                <div class="small text-muted">Cost: {{ number_format($product->cost_price, 2) }}</div>
                            </div>

                            <div class="text-end">
                                @if ($product->isLowStock())
                                    <span class="badge bg-danger">Low</span>
                                @endif
                                <div class="mt-2">
                                    <button class="btn btn-sm btn-outline-success"
                                        onclick="openAdjustModal({{ $product->toJson() }})" data-bs-toggle="modal"
                                        data-bs-target="#adjustModal">
                                        Adjust
                                    </button>
                                    <button class="btn btn-sm btn-outline-secondary"
                                        onclick="openEditProduct({{ $product->toJson() }})">Edit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('stock.movements', $product) }}" class="btn btn-sm btn-link">Movements</a>

                        <form action="{{ route('stock.destroy', $product) }}" method="POST"
                            onsubmit="return confirm('Delete product?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $products->withQueryString()->links() }}
    </div>

    {{-- Modals --}}
    @include('stock.partials.product-modal')
    @include('stock.partials.adjust-modal')
    @include('stock.partials.edit-modal')

@endsection

@section('script')
    <script>
        function openAdjustModal(product) {
            document.getElementById('adj_product_id').value = product.id;
            document.getElementById('adj_product_name').innerText = product.name;
            document.getElementById('adj_quantity').value = '';
            document.getElementById('adj_reason').value = '';
            document.querySelector('input[name="type"][value="in"]').checked = true;
            var myModal = new bootstrap.Modal(document.getElementById('adjustModal'));
            myModal.show();
        }

        function openEditProduct(product) {
            document.getElementById('edit_product_id').value = product.id;
            document.getElementById('edit_sku').value = product.sku || '';
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_description').value = product.description || '';
            document.getElementById('edit_cost_price').value = product.cost_price || 0;
            document.getElementById('edit_sell_price').value = product.sell_price || 0;
            document.getElementById('edit_low_stock_threshold').value = product.low_stock_threshold || 0;
            document.getElementById('editForm').action = '/stock/' + product.id;
            var myModal = new bootstrap.Modal(document.getElementById('editProductModal'));
            myModal.show();
        }
    </script>
@endsection
