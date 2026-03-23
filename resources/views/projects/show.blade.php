@extends('layouts.master')

@section('title')
    @lang('translation.show-project')
@endsection

@section('content')
    <ul class="nav nav-pills nav-custom-light" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#project-overview" role="tab">
                Overview
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#project-documents" role="tab">
                Documents
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#project-activities" role="tab">
                Activities
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#project-team" role="tab">
                Team
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#project-expenses" role="tab">
                Expenses
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#project-tasks" role="tab">
                Tasks
            </a>
        </li>
    </ul>

    <div class="tab-content text-muted mt-3">
        {{-- Overview --}}
        <div class="tab-pane fade show active" id="project-overview" role="tabpanel">
            @include('projects.partials.overview', ['project' => $project])
        </div>

        {{-- Documents --}}
        <div class="tab-pane fade" id="project-documents" role="tabpanel">
            @include('projects.partials.documents', ['project' => $project])
        </div>

        {{-- activities --}}
        <div class="tab-pane fade" id="project-activities" role="tabpanel">
            @include('projects.partials.activities', ['project' => $project])
        </div>

        {{-- Team --}}
        <div class="tab-pane fade" id="project-team" role="tabpanel">
            @include('projects.partials.team', ['project' => $project, 'users' => $users])
        </div>
        {{-- Expenses --}}
        <div class="tab-pane fade" id="project-expenses" role="tabpanel">
            @include('projects.partials.expenses', ['project' => $project])
        </div>
        {{-- Tasks --}}
        <div class="tab-pane fade" id="project-tasks" role="tabpanel">
            @include('projects.partials.tasks', ['project' => $project, 'users' => $users])
        </div>
    </div>
@endsection
