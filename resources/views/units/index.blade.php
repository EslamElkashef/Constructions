@extends('layouts.master')

@section('title', 'Units Dashboard')

@section('css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        .stat-card,
        .unit-card {
            font-family: 'Poppins', sans-serif;
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .stat-card:hover,
        .unit-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        /* Stats Card Colors */
        .stat-card[data-status="available"] {
            background-color: #d1fae5;
            color: #065f46;
        }

        .stat-card[data-status="reserved"] {
            background-color: #fef3c7;
            color: #78350f;
        }

        .stat-card[data-status="sold"] {
            background-color: #bfdbfe;
            color: #1e3a8a;
        }

        .stat-card[data-status="all"] {
            background-color: #e0e7ff;
            color: #3730a3;
        }

        .stat-card h6 {
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stat-card h3 {
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .stat-card i {
            color: #6b7280;
        }

        /* Unit Card */
        .unit-card {
            height: 360px;
            /* حجم ثابت */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .unit-card img,
        .unit-card .placeholder {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            background-color: #e0e7ff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 600;
            color: #3730a3;
        }

        /* Favorite Button */
        .favorite-btn {
            z-index: 10;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        /* Card Body */
        .unit-card .card-body h6 {
            font-weight: 600;
            font-size: 1rem;
        }

        .unit-card .card-body small {
            color: #6c757d;
        }

        .unit-card .card-body p {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        /* Footer Buttons */
        .unit-card .card-footer {
            padding: 0.5rem;
        }
    </style>
@endsection

@section('content')
    {{-- Stats Cards --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-4">
        @php
            $stats = [
                ['title' => 'Total Units', 'count' => $units->count(), 'status' => 'all'],
                [
                    'title' => 'Available Units',
                    'count' => $units->where('status', 'available')->count(),
                    'status' => 'available',
                ],
                [
                    'title' => 'Reserved Units',
                    'count' => $units->where('status', 'reserved')->count(),
                    'status' => 'reserved',
                ],
                ['title' => 'Sold Units', 'count' => $units->where('status', 'sold')->count(), 'status' => 'sold'],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div class="col-md-2 col-6">
                <div class="card stat-card text-center p-3 shadow-sm rounded-4 cursor-pointer"
                    data-status="{{ $stat['status'] }}">
                    <h6>{{ $stat['title'] }}</h6>
                    <h3>{{ $stat['count'] }}</h3>
                    <i class="ri-building-2-line fs-2"></i>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Header + Add Button --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <h4 class="fw-bold text-primary mb-0"><i class="ri-building-line me-2"></i> Units Dashboard</h4>
        <a href="{{ route('units.create') }}" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
            <i class="ri-add-line me-1"></i> Add New Unit
        </a>
    </div>

    {{-- Search + Filters --}}
    <form action="{{ route('units.index') }}" method="GET"
        class="d-flex flex-wrap align-items-center gap-2 bg-light border rounded-4 p-3 shadow-sm mb-4">
        <div class="input-group input-group-sm w-auto">
            <span class="input-group-text bg-white"><i class="ri-search-line"></i></span>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0"
                placeholder="Search by name, type, or phone...">
        </div>
        <select name="type" class="form-select form-select-sm w-auto">
            <option value="">All Types</option>
            @foreach ($unitTypes as $type)
                <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>
                    {{ $type->name_ar ?? $type->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select form-select-sm w-auto">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="reserved" {{ request('status') == 'reserved' ? 'selected' : '' }}>Reserved</option>
            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Sold</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3"><i class="ri-filter-2-line me-1"></i>
            Search</button>
        <a href="{{ route('units.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3"><i
                class="ri-refresh-line me-1"></i> Reset</a>
    </form>

    {{-- Units Cards --}}
    <div class="row g-4">
        @forelse($units as $u)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="card unit-card shadow-sm position-relative">
                    {{-- Favorite --}}
                    <button type="button" class="btn btn-light btn-sm rounded-circle favorite-btn toggle-favorite">
                        <i class="{{ $u->is_favorite ? 'ri-heart-fill text-danger' : 'ri-heart-line text-muted' }}"></i>
                    </button>

                    {{-- Image or Placeholder --}}
                    @if ($u->media && $u->media->count() > 0)
                        <img src="{{ asset('storage/' . $u->media->first()->path) }}" alt="Unit Image">
                    @else
                        <div class="placeholder">{{ strtoupper(substr($u->name, 0, 2)) }}</div>
                    @endif

                    {{-- Content --}}
                    <div class="card-body text-center py-2">
                        <h6>{{ $u->name }}</h6>
                        <small>{{ $u->type->name_ar ?? ($u->type->name ?? 'Unknown') }}</small>
                        <p class="mb-1"><i class="ri-map-pin-line me-1"></i>{{ $u->address ?? 'No address' }}</p>
                        <p><i class="ri-phone-line me-1"></i>{{ $u->phone ?? '—' }}</p>
                        <span
                            class="badge bg-{{ $u->status == 'active' ? 'success' : ($u->status == 'reserved' ? 'warning' : ($u->status == 'sold' ? 'info' : 'secondary')) }}">{{ ucfirst($u->status ?? 'Unknown') }}</span>
                    </div>

                    {{-- Footer Buttons --}}
                    <div class="card-footer d-flex justify-content-center gap-2 border-top py-2">
                        <a href="{{ route('units.show', $u->id) }}" class="btn btn-outline-info btn-sm"><i
                                class="ri-eye-line"></i></a>
                        <a href="{{ route('units.edit', $u->id) }}" class="btn btn-outline-warning btn-sm"><i
                                class="ri-edit-line"></i></a>
                        <a href="{{ route('invoices.index', ['unitId' => $u->id]) }}" class="btn btn-outline-info btn-sm">
                            <i class="ri-file-list-line"></i>
                        </a>

                        @if (request('deleted') == 1)
                            <form action="{{ route('units.forceDelete', $u->id) }}" method="POST" class="d-inline">@csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"><i class="ri-delete-bin-line"></i></button>
                            </form>
                            <form action="{{ route('units.restore', $u->id) }}" method="POST" class="d-inline">@csrf
                                @method('PATCH')
                                <button class="btn btn-outline-success btn-sm"><i class="ri-refresh-line"></i></button>
                            </form>
                        @else
                            <form action="{{ route('units.forceDelete', $u->id) }}" method="POST" class="d-inline">@csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm"><i class="ri-delete-bin-line"></i></button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <i class="ri-emotion-unhappy-line fs-1 d-block mb-3 text-warning"></i>
                <h6>No units found</h6>
            </div>
        @endforelse
    </div>

@endsection

@section('script')
    <script>
        document.querySelectorAll('.toggle-favorite').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const icon = this.querySelector('i');
                fetch(`/units/${id}/toggle-favorite`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            icon.classList.toggle('ri-heart-fill', data.is_favorite);
                            icon.classList.toggle('text-danger', data.is_favorite);
                            icon.classList.toggle('ri-heart-line', !data.is_favorite);
                            icon.classList.toggle('text-muted', !data.is_favorite);
                        }
                    });
            });
        });

        // Stats Card Click
        document.querySelectorAll('.stat-card').forEach(card => {
            card.addEventListener('click', function() {
                const status = this.dataset.status;
                let url = '{{ route('units.index') }}';
                if (status !== 'all') url += '?status=' + status;
                window.location.href = url;
            });
        });
    </script>
@endsection
