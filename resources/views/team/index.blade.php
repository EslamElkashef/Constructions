@extends('layouts.master')

@section('title', 'Team')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            Pages
        @endslot
        @slot('title')
            Team
        @endslot
    @endcomponent

    {{-- شريط البحث وزر الإضافة --}}
    <div class="card">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-center">
            <div class="col-sm-4">
                <div class="search-box">
                    <input type="text" class="form-control" id="searchMemberList" placeholder="Search by name or role...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                <i class="ri-add-line me-1 align-bottom"></i> Add Member
            </button>
        </div>
    </div>

    {{-- عرض الفريق --}}
    <div class="row mt-4" id="teamContainer">
        @forelse ($members as $member)
            <div class="col-xl-3 col-lg-4 col-sm-6 mb-4 team-card" data-fav="{{ $member->favourite ? '1' : '0' }}">
                <div class="card team-box border-0 shadow-sm position-relative overflow-hidden">

                    {{-- صورة الغلاف --}}
                    <div class="position-relative">
                        <img src="{{ $member->background ? asset('storage/' . $member->background) : asset('assets/images/small/img-9.jpg') }}"
                            class="img-fluid rounded-top" alt="background">

                        {{-- زر المفضلة --}}
                        <button class="favorite-btn position-absolute top-0 start-0 m-2" data-id="{{ $member->id }}">
                            <i
                                class="{{ $member->favourite ? 'ri-bookmark-fill text-warning' : 'ri-bookmark-line text-white' }} fs-5"></i>
                        </button>

                        {{-- قائمة الخيارات --}}
                        <div class="dropdown position-absolute top-0 end-0 m-2">
                            <button class="btn btn-icon text-white" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="ri-more-2-fill fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item edit-btn d-flex align-items-center" href="#"
                                        data-id="{{ $member->id }}">
                                        <i class="ri-pencil-line me-2 text-primary"></i>
                                        <span>Edit</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item delete-btn d-flex align-items-center text-danger" href="#"
                                        data-id="{{ $member->id }}">
                                        <i class="ri-delete-bin-line me-2"></i>
                                        <span>Delete</span>
                                    </a>
                                </li>
                            </ul>

                        </div>
                    </div>

                    {{-- محتوى الكارت --}}
                    <div class="card-body text-center position-relative" id="member-card-{{ $member->id }}">
                        <div class="mb-3 mt-n5">
                            <img src="{{ $member->avatar ? asset('storage/' . $member->avatar) : asset('assets/images/users/user-dummy-img.jpg') }}"
                                alt="{{ $member->name }}"
                                class="avatar-lg rounded-circle border border-3 border-white shadow-sm object-fit-cover">
                        </div>

                        <h5 class="mb-1">{{ $member->name }}</h5>
                        <p class="text-muted mb-3">{{ $member->role ?? 'No Role' }}</p>

                        <div class="d-flex justify-content-center text-center border-top pt-3 gap-5">
                            <div>
                                <h6 class="mb-0">{{ $member->projects_count ?? 0 }}</h6>
                                <small class="text-muted">Projects</small>
                            </div>
                            <div class="border-start ps-4">
                                <h6 class="mb-0">{{ $member->tasks_count ?? 0 }}</h6>
                                <small class="text-muted">Tasks</small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="#" class="btn btn-outline-primary w-100">View Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                    colors="primary:#405189,secondary:#0ab39c" style="width:72px;height:72px"></lord-icon>
                <h5 class="mt-4">No Team Members Found</h5>
            </div>
        @endforelse
    </div>

    {{-- Modal إضافة عضو --}}
    <div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-body">
                    <form action="{{ route('team-members.store', 1) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/images/users/user-dummy-img.jpg') }}" id="member-img"
                                class="avatar-lg rounded-circle border border-2 border-light" alt="">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Avatar</label>
                            <input type="file" name="avatar" class="form-control"
                                accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Background</label>
                            <input type="file" name="background" class="form-control"
                                accept="image/png, image/jpeg, image/jpg">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter member name"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" name="role" class="form-control" placeholder="Enter role">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Project</label>
                            <select class="form-select" name="project_id" required>
                                <option value="">Select Project</option>
                                @foreach (\App\Models\Project::all() as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Add Member</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal تعديل عضو --}}
    <div class="modal fade" id="editMemberModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-body">
                    <form id="editMemberForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="text-center mb-4">
                            <img src="{{ asset('assets/images/users/user-dummy-img.jpg') }}" id="edit-member-img"
                                class="avatar-lg rounded-circle border border-2 border-light" alt="">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Avatar</label>
                            <input type="file" name="avatar" class="form-control"
                                accept="image/png, image/jpeg, image/jpg"
                                onchange="document.getElementById('edit-member-img').src = URL.createObjectURL(this.files[0])">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Background</label>
                            <input type="file" name="background" class="form-control"
                                accept="image/png, image/jpeg, image/jpg">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" name="role" id="edit-role" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Project</label>
                            <select class="form-select" name="project_id" id="edit-project" required>
                                @foreach (\App\Models\Project::all() as $project)
                                    <option value="{{ $project->id }}">{{ $project->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .team-box {
            border-radius: 1rem;
            transition: all 0.3s ease-in-out;
        }

        .team-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .avatar-lg {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .favorite-btn,
        .btn-icon {
            background: none !important;
            border: none !important;
            padding: 5px;
            transition: 0.2s;
        }

        .favorite-btn:hover i,
        .btn-icon:hover i {
            transform: scale(1.2);
            color: gold !important;
        }

        .dropdown-toggle::after {
            display: none !important;
        }
    </style>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function previewImage(event) {
            const img = document.getElementById('member-img');
            img.src = URL.createObjectURL(event.target.files[0]);
        }
        // ✅ بحث مباشر بالاسم أو الدور
        document.getElementById('searchMemberList').addEventListener('keyup', function() {
            const term = this.value.toLowerCase();
            document.querySelectorAll('.team-card').forEach(card => {
                const name = card.querySelector('h5').textContent.toLowerCase();
                const role = card.querySelector('p').textContent.toLowerCase();
                card.style.display = (name.includes(term) || role.includes(term)) ? '' : 'none';
            });
        });
        // SweetAlert حذف
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                Swal.fire({
                    title: "Are you sure?",
                    text: "This member will be deleted permanently.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#6c757d",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/team-members/delete/${id}`)
                            .then(response => response.text())
                            .then(() => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Member deleted successfully!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(() => location.reload(),
                                1500); // 🔁 يحدث الصفحة بعد ثانية ونص
                            });
                    }
                });
            });
        });

        // المفضلة
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const card = this.closest('.team-card');
                const id = this.dataset.id;

                fetch(`/team-members/${id}/favourite`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        const isFav = data.favourite;
                        icon.classList.toggle('ri-bookmark-fill', isFav);
                        icon.classList.toggle('ri-bookmark-line', !isFav);
                        icon.classList.toggle('text-warning', isFav);
                        icon.classList.toggle('text-white', !isFav);
                        card.dataset.fav = isFav ? '1' : '0';
                        const container = document.getElementById('teamContainer');
                        const cards = Array.from(container.children);
                        cards.sort((a, b) => b.dataset.fav - a.dataset.fav);
                        container.innerHTML = '';
                        cards.forEach(c => container.appendChild(c));
                    }
                });
            });
        });

        // تعديل العضو
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                fetch(`/team-members/${id}/edit`)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('edit-name').value = data.name;
                        document.getElementById('edit-role').value = data.role;
                        document.getElementById('edit-project').value = data.project_id;
                        document.getElementById('edit-member-img').src = data.avatar_url;
                        document.getElementById('editMemberForm').action = `/team-members/${id}`;
                        new bootstrap.Modal(document.getElementById('editMemberModal')).show();
                    });
            });
        });
        // ✅ عرض رسالة نجاح باستخدام SweetAlert بعد أي عملية
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                position: 'center',
                backdrop: false
            });
        @endif
    </script>
@endsection
