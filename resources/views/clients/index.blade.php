@extends('layouts.master')

@section('title', 'Clients')

@section('content')
    <div class="container-fluid mt-3">

        {{-- ===== Page Header ===== --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">Clients</h4>
            <button id="btnAddClient" class="btn btn-primary">
                <i class="ri-user-add-line me-1"></i> Add Client
            </button>
        </div>

        {{-- ===== Filters ===== --}}
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body">
                <form method="GET" action="{{ route('clients.index') }}" class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label fw-semibold">Search by Name or Type</label>
                        <input type="text" name="q" class="form-control" placeholder="Search..."
                            value="{{ request('q') }}">
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <label class="form-label fw-semibold">Sort by</label>
                        <select name="sort" class="form-select">
                            <option value="">Default</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="type" {{ request('sort') == 'type' ? 'selected' : '' }}>Type</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <button class="btn btn-success w-100"><i class="ri-search-line me-1"></i> Search</button>
                    </div>
                    <div class="col-lg-2 col-md-3">
                        <a href="{{ route('clients.index') }}" class="btn btn-light w-100">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- ===== Client Cards ===== --}}
        <div class="row">
            @forelse ($clients as $client)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="card shadow-sm border-0 mb-4 hover-card position-relative">

                        {{-- Favorite Button --}}
                        <button class="btn btn-light btn-sm position-absolute top-0 start-0 m-2 favorite-btn"
                            data-id="{{ $client->id }}">
                            <i class="{{ $client->is_favorite ? 'ri-heart-fill text-warning' : 'ri-heart-line text-muted' }}"
                                style="font-size: 22px;"></i>
                        </button>

                        {{-- Dropdown --}}
                        <div class="dropdown position-absolute top-0 end-0 m-2">
                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a href="#" class="dropdown-item edit-client" data-id="{{ $client->id }}"
                                        data-name="{{ $client->name }}" data-phone="{{ $client->phone }}"
                                        data-address="{{ $client->address }}" data-type="{{ $client->type }}"
                                        data-notes="{{ $client->notes }}" data-join_date="{{ $client->join_date }}">
                                        <i class="ri-pencil-line me-1"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('clients.show', $client->id) }}" class="dropdown-item">
                                        <i class="ri-eye-line me-1"></i> View
                                    </a>
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger delete-client"
                                        data-action="{{ route('clients.destroy', $client->id) }}">
                                        <i class="ri-delete-bin-line me-1"></i> Delete
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body text-center p-4">
                            {{-- Avatar --}}
                            <div class="d-inline-block mb-3">
                                <div class="avatar-title bg-light text-primary rounded-circle fs-3 fw-bold d-flex align-items-center justify-content-center"
                                    style="width:90px;height:90px;">
                                    {{ strtoupper(substr($client->name, 0, 1)) }}
                                </div>
                            </div>

                            {{-- Info --}}
                            <h6 class="fw-semibold mb-1 mt-2">{{ $client->name }}</h6>
                            <p class="text-muted mb-2">{{ ucfirst($client->type) ?? '—' }}</p>
                            <p class="text-muted small mb-3">
                                <i class="ri-phone-line me-1 align-bottom"></i> {{ $client->phone ?? '—' }}<br>
                                <i class="ri-map-pin-line me-1 align-bottom"></i> {{ $client->address ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="ri-user-search-line fs-1 d-block mb-3"></i>
                    <p>No clients found</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-end mt-4">
            {{ $clients->links() }}
        </div>
    </div>

    {{-- ======= Modal (Create / Edit) ======= --}}
    <div id="clientCard" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- Header --}}
                <div class="modal-header text-white py-3 px-4"
                    style="background: linear-gradient(90deg, #007bff, #6610f2); border-bottom: none;">
                    <div class="d-flex align-items-center">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm"
                            style="width:45px; height:45px; font-size:20px;">
                            <i class="ri-user-add-line"></i>
                        </div>
                        <h5 class="modal-title fw-bold mb-0" id="clientCardTitle">Add Client</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                {{-- Body --}}
                <form id="clientForm" method="POST" action="{{ route('clients.store') }}">
                    @csrf
                    <div id="methodField"></div>
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name</label>
                                <input type="text" name="name" id="clientName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone</label>
                                <input type="text" name="phone" id="clientPhone" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Address</label>
                                <input type="text" name="address" id="clientAddress" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type</label>
                                <select name="type" id="clientType" class="form-select">
                                    <option value="">Select Type</option>
                                    <option value="company">Company</option>
                                    <option value="personal">Personal</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Notes</label>
                                <textarea name="notes" id="clientNotes" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Join Date</label>
                                <input type="text" class="form-control flatpickr" name="join_date" id="joinDate">
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="modal-footer bg-light border-top-0 py-3 px-4">
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="ri-save-line me-1"></i> Save
                        </button>
                        <button type="button" class="btn btn-outline-secondary px-4"
                            data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <style>
        .hover-card {
            transition: all 0.2s ease;
        }

        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .modal-content {
            border-radius: 1rem !important;
            overflow: hidden;
        }

        .modal-header h5 {
            font-weight: 700;
            letter-spacing: 0.3px;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize Flatpickr
        flatpickr(".flatpickr", {
            dateFormat: "Y-m-d"
        });

        // Create a single instance of the modal
        const clientModalEl = document.getElementById('clientCard');
        const clientModal = new bootstrap.Modal(clientModalEl, {
            backdrop: 'static', // optional: يمنع الإغلاق عند الضغط على الخلفية
            keyboard: false // optional: يمنع الإغلاق بالـ ESC
        });

        // Open modal (Create)
        document.getElementById('btnAddClient').addEventListener('click', () => {
            resetForm();
            document.getElementById('clientCardTitle').textContent = 'Add Client';
            document.getElementById('clientForm').action = "{{ route('clients.store') }}";
            document.getElementById('methodField').innerHTML = '';
            clientModal.show();
        });

        // Open modal (Edit)
        document.querySelectorAll('.edit-client').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                resetForm();
                document.getElementById('clientCardTitle').textContent = 'Edit Client';
                document.getElementById('clientForm').action = `/clients/${this.dataset.id}`;
                document.getElementById('methodField').innerHTML =
                    '<input type="hidden" name="_method" value="PUT">';
                document.getElementById('clientName').value = this.dataset.name || '';
                document.getElementById('clientPhone').value = this.dataset.phone || '';
                document.getElementById('clientAddress').value = this.dataset.address || '';
                document.getElementById('clientType').value = this.dataset.type || '';
                document.getElementById('clientNotes').value = this.dataset.notes || '';
                document.getElementById('joinDate').value = this.dataset.join_date || '';
                clientModal.show();
            });
        });

        // Reset form
        function resetForm() {
            document.getElementById('clientForm').reset();
        }

        // SweetAlert delete
        document.querySelectorAll('.delete-client').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This client will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                }).then(result => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = action;
                        form.innerHTML = `
                        @csrf
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Favorite toggle
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const clientId = this.dataset.id;
                const icon = this.querySelector('i');

                fetch(`/clients/${clientId}/favorite`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.is_favorite) {
                                icon.classList.remove('ri-heart-line', 'text-muted');
                                icon.classList.add('ri-heart-fill', 'text-warning');
                            } else {
                                icon.classList.remove('ri-heart-fill', 'text-warning');
                                icon.classList.add('ri-heart-line', 'text-muted');
                            }
                        }
                    })
                    .catch(err => console.error(err));
            });
        });
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}',
            });
        </script>
    @endif
@endsection
