@extends('layouts.master')
@section('title', 'Create Task')
@section('css')
    <link href="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="container">
        <h3 class="mb-4">Create New Task</h3>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tasks.store') }}" method="POST" class="tablelist-form" autocomplete="off">
            @csrf
            <div class="modal-body">
                <input type="hidden" id="tasksId" />
                <div class="row g-3">
                    <div class="col-lg-12">
                        <label for="project-field" class="form-label">Project</label>
                        <select id="project-field" name="project_id" class="form-control">
                            <option value="">— بدون مشروع —</option>
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}" {{ old('project_id') == $proj->id ? 'selected' : '' }}>
                                    {{ $proj->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-lg-12">
                        <div>
                            <label for="tasksTitle-field" class="form-label">Title</label>
                            <input type="text" name="title" id="tasksTitle-field" class="form-control"
                                placeholder="Title" required />
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-lg-12">
                        <div>
                            <label for="tasksDescription-field" class="form-label">Description</label>
                            <input type="text" name="description" id="tasksDescription-field" class="form-control"
                                placeholder="Description" required />
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-lg-12">
                        <label for="clientName-field" class="form-label">Client Name</label>
                        <input type="text" id="clientName-field" name="client_name" class="form-control"
                            placeholder="Client name" required />
                    </div>
                    <!--end col-->
                    <div class="col-lg-12">
                        <label class="form-label">Assigned To</label>
                        <div data-simplebar style="height: 150px;">
                            <ul class="list-unstyled vstack gap-2 mb-0">
                                @foreach ($users as $user)
                                    <li>
                                        <div class="form-check d-flex align-items-center">
                                            <input class="form-check-input me-3" type="checkbox" name="assignedTo[]"
                                                value="{{ $user->id }}" id="user-{{ $user->id }}">
                                            <label class="form-check-label d-flex align-items-center"
                                                for="user-{{ $user->id }}">
                                                <span class="flex-shrink-0">
                                                    <img src="{{ $user->avatar_url ?? asset('assets/images/users/default.png') }}"
                                                        alt="{{ $user->name }}" class="avatar-xxs rounded-circle">
                                                </span>
                                                <span class="flex-grow-1 ms-2">{{ $user->name }}</span>
                                            </label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!--end col-->
                    <div class="col-lg-6">
                        <label for="duedate-field" class="form-label">Due Date</label>
                        <input type="text" id="duedate-field" name="due_date" class="form-control"
                            data-provider="flatpickr" placeholder="Due date" required />
                    </div>
                    <!--end col-->
                    <div class="col-lg-6">
                        <label for="ticket-status" class="form-label">Status</label>
                        <select class="form-control" name="status" data-choices data-choices-search-false
                            id="ticket-status">
                            <option value="">Status</option>
                            <option value="New" {{ old('status', $task->status ?? '') == 'New' ? 'selected' : '' }}>New
                            </option>
                            <option value="Inprogress"
                                {{ old('status', $task->status ?? '') == 'Inprogress' ? 'selected' : '' }}>
                                Inprogress</option>
                            <option value="Pending"
                                {{ old('status', $task->status ?? '') == 'Pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="Completed"
                                {{ old('status', $task->status ?? '') == 'Completed' ? 'selected' : '' }}>Completed
                            </option>
                        </select>
                    </div>

                    <div class="col-lg-12">
                        <label for="priority-field" class="form-label">Priority</label>
                        <select class="form-control" name="priority" data-choices data-choices-search-false
                            id="priority-field">
                            <option value="">Priority</option>
                            <option value="High"
                                {{ old('priority', $task->priority ?? '') == 'High' ? 'selected' : '' }}>High
                            </option>
                            <option value="Medium"
                                {{ old('priority', $task->priority ?? '') == 'Medium' ? 'selected' : '' }}>Medium
                            </option>
                            <option value="Low" {{ old('priority', $task->priority ?? '') == 'Low' ? 'selected' : '' }}>
                                Low
                            </option>
                        </select>
                    </div>

                    <!--end col-->
                </div>
                <!--end row-->
            </div>
            <div class="modal-footer">
                <div class="hstack gap-2 justify-content-end">
                    <button type="button" class="btn btn-light" id="close-modal" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" id="add-btn">Add Task</button>
                    {{-- <button type="button" class="btn btn-success" id="edit-btn">Update Task</button> --}}
                </div>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#duedate-field", {
                altInput: true, // بيعرض تاريخ أنيق زي الصورة
                altFormat: "F j, Y", // الصيغة اللي بتظهر للمستخدم (October 5, 2025)
                dateFormat: "Y-m-d", // الصيغة اللي تتخزن في قاعدة البيانات
                maxDate: "today",
                allowInput: true, // يسمح بالكتابة اليدوية كاختيار إضافي
                disableMobile: true, // يخلي الـ picker يظهر حتى على الموبايل
                locale: {
                    firstDayOfWeek: 0, // يبدأ الأسبوع من الأحد
                }
            });
        });
    </script>

    <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script>
@endsection
