@extends('layouts.master')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
            <h4 class="fw-bold text-primary mb-2 mb-sm-0">Invoices</h4>
            <a href="{{ route('invoices.create', ['unitId' => $unit->id]) }}" class="btn btn-success btn-sm">
                ➕ Create Invoice
            </a>
        </div>

        <div class="card shadow-sm p-3 border-0 rounded-4">
            <div class="table-responsive">
                <table class="table table-column-striped align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Unit</th>
                            <th scope="col">Seller</th>
                            <th scope="col">Buyer</th>
                            <th scope="col">Unit Price</th>
                            <th scope="col">Company Commission (Seller)</th>
                            <th scope="col">Company Commission (Buyer)</th>
                            <th scope="col">Employee Commission</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($invoices as $invoice)
                            <tr>
                                <th scope="row">{{ $invoice->id }}</th>
                                <td>{{ $invoice->unit->name ?? '-' }}</td>
                                <td>{{ $invoice->seller_name }}</td>
                                <td>{{ $invoice->buyer_name }}</td>
                                <td>{{ number_format($invoice->unit_price, 2) }}</td>
                                <td>{{ $invoice->company_commission_from_seller ?? 0 }}</td>
                                <td>{{ $invoice->company_commission_from_buyer ?? 0 }}</td>
                                <td>
                                    @if ($invoice->employee_commission_value)
                                        {{ $invoice->employee_commission_value }}
                                    @elseif($invoice->employee_commission_percent)
                                        {{ $invoice->employee_commission_percent }}%
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColor = match ($invoice->status) {
                                            'available' => 'success',
                                            'reserved' => 'warning text-dark',
                                            'sold' => 'danger',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">{{ ucfirst($invoice->status) }}</span>
                                </td>

                                <td class="d-flex gap-1">
                                    <a href="{{ route('invoices.edit', ['unitId' => $invoice->unit_id, 'invoiceId' => $invoice->id]) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="ri-edit-2-line"></i>
                                    </a>

                                    <button type="button" class="btn btn-outline-danger btn-sm"
                                        onclick="confirmDelete({{ $invoice->unit_id }}, {{ $invoice->id }})">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No invoices found</td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                {{ $invoices->links() }}
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(unitId, invoiceId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This invoice will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/units/${unitId}/invoices/${invoiceId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then(res => {
                        if (res.ok) {
                            Swal.fire('Deleted!', 'Invoice has been deleted.', 'success')
                                .then(() => window.location.reload());
                        } else {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    })
                }
            });
        }
    </script>

    {{-- Custom Styles --}}
    <style>
        /* striped columns */
        .table-column-striped td:nth-child(odd),
        .table-column-striped th:nth-child(odd) {
            background-color: #f8f9fa !important;
        }

        .badge {
            font-size: 0.85rem;
            padding: 0.4em 0.6em;
            border-radius: 0.5rem;
        }

        .btn-outline-primary,
        .btn-outline-danger {
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }

        .card {
            transition: all 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
