@extends('layouts.master')

@section('title', 'Profiles')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Pages
        @endslot
        @slot('title')
            Profiles
        @endslot
    @endcomponent

    {{-- 🔍 شريط البحث وزر الإضافة --}}
    <div class="card mb-4">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
            <div class="col-sm-4">
                <div class="search-box">
                    <input type="text" class="form-control" id="searchProfileList"
                        placeholder="Search by name or designation...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <a href="{{ route('profiles.create') }}" class="btn btn-success">
                <i class="ri-add-line me-1 align-bottom"></i> Add Profile
            </a>
        </div>
    </div>

    {{-- 🧑‍💼 عرض البروفايلات --}}
    <div class="row" id="profileContainer">
        @forelse ($profiles as $profile)
            <div class="col-xl-4 col-lg-6 col-md-6 mb-4 profile-card">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden position-relative h-100 text-center p-4">

                    {{-- ⭐ المفضلة والقائمة --}}
                    <div class="d-flex justify-content-between position-absolute top-0 start-0 end-0 m-2 px-2">
                        <button class="favorite-btn btn btn-sm btn-light rounded-circle" data-id="{{ $profile->id }}">
                            <i
                                class="{{ $profile->favourite ? 'ri-bookmark-fill text-warning' : 'ri-bookmark-line text-muted' }}"></i>
                        </button>

                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                <i class="ri-more-2-fill"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profiles.show', $profile->id) }}">
                                        <i class="ri-eye-fill me-2 text-info"></i>View
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profiles.edit', $profile->id) }}">
                                        <i class="ri-pencil-line me-2 text-warning"></i>Edit
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form id="deleteForm-{{ $profile->id }}"
                                        action="{{ route('profiles.destroy', $profile->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item text-danger"
                                            onclick="confirmDelete({{ $profile->id }})">
                                            <i class="ri-delete-bin-line me-2"></i>Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- 👤 الصورة أو الحروف --}}
                    <div class="mt-4 mb-3">
                        @if ($profile->avatar && file_exists(public_path('storage/' . $profile->avatar)))
                            <img src="{{ asset('storage/' . $profile->avatar) }}" alt="{{ $profile->full_name }}"
                                class="avatar-xl rounded-circle border border-3 border-primary-subtle shadow-sm object-fit-cover">
                        @else
                            @php
                                $names = explode(' ', $profile->full_name);
                                $initials = strtoupper(substr($names[0] ?? '', 0, 1) . substr($names[1] ?? '', 0, 1));
                                $colors = ['primary', 'success', 'danger', 'info', 'warning', 'secondary'];
                                $bg = $colors[array_rand($colors)];
                            @endphp
                            <div
                                class="avatar-xl rounded-circle bg-{{ $bg }} text-white d-flex align-items-center justify-content-center fs-3 fw-bold mx-auto shadow-sm">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    {{-- الاسم والوظيفة --}}
                    <h5 class="fw-semibold mb-1">{{ $profile->full_name }}</h5>
                    <p class="text-muted small mb-3">{{ $profile->designation ?? 'No Designation' }}</p>

                    {{-- 🟩 الحالة --}}
                    <span
                        class="badge rounded-pill
                        @switch($profile->status)
                            @case('active') bg-success @break
                            @case('pending') bg-warning @break
                            @case('resigned') bg-info @break
                            @case('terminated') bg-danger @break
                            @default bg-secondary
                        @endswitch">
                        {{ ucfirst($profile->status ?? 'unknown') }}
                    </span>

                    {{-- معلومات التواصل --}}
                    <div class="bg-light rounded-3 py-3 px-4 mb-3 d-inline-block text-start shadow-sm"
                        style="min-width: 80%;">
                        <p class="mb-1"><i class="ri-mail-line me-1 text-primary"></i> {{ $profile->email }}</p>
                        @if ($profile->phone)
                            <p class="mb-1"><i class="ri-phone-line me-1 text-primary"></i> {{ $profile->phone }}</p>
                        @endif
                        @if ($profile->city || $profile->country)
                            <p class="mb-0"><i class="ri-map-pin-line me-1 text-primary"></i> {{ $profile->city }},
                                {{ $profile->country }}</p>
                        @endif
                    </div>

                    {{-- عدادات --}}
                    <div class="d-flex justify-content-center gap-4 border-top pt-3 mt-auto">
                        <div>
                            <h6 class="mb-0">{{ $profile->projects_count ?? 0 }}</h6>
                            <small class="text-muted">Projects</small>
                        </div>
                        <div class="border-start ps-4">
                            <h6 class="mb-0">{{ $profile->tasks_count ?? 0 }}</h6>
                            <small class="text-muted">Tasks</small>
                        </div>
                    </div>

                </div>
            </div>
        @empty
            <div class="text-center py-5 col-12">
                <i class="ri-user-line display-5 d-block mb-3 text-muted"></i>
                <h5>No Profiles Found</h5>
                <p class="mb-0">Start by creating a new profile.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $profiles->links() }}
    </div>
@endsection

@section('css')
    <style>
        .avatar-xl {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .profile-card .card {
            transition: all 0.3s ease;
        }

        .profile-card .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .favorite-btn i {
            transition: 0.3s;
        }

        .favorite-btn:hover i {
            transform: scale(1.2);
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ✅ بحث سريع
        document.getElementById('searchProfileList').addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.profile-card').forEach(card => {
                const name = card.querySelector('h5').textContent.toLowerCase();
                const role = card.querySelector('p').textContent.toLowerCase();
                card.style.display = (name.includes(term) || role.includes(term)) ? '' : 'none';
            });
        });

        // ✅ تأكيد الحذف
        function confirmDelete(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This profile will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm-' + id).submit();
                }
            });
        }

        // ✅ المفضلة AJAX
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const id = this.dataset.id;

                fetch(`/profiles/${id}/favourite`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            const isFav = data.favourite;
                            icon.classList.toggle('ri-bookmark-fill', isFav);
                            icon.classList.toggle('ri-bookmark-line', !isFav);
                            icon.classList.toggle('text-warning', isFav);
                            icon.classList.toggle('text-muted', !isFav);
                        }
                    });
            });
        });
    </script>
@endsection
