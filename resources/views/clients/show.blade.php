@extends('layouts.master')

@section('title', 'Client Details')

@section('content')
    <div class="container mt-5 mb-5">
        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient text-white py-4 px-4 d-flex justify-content-between align-items-center"
                style="background: linear-gradient(90deg, #007bff, #6610f2);">
                <div class="d-flex align-items-center">
                    <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center me-3 shadow"
                        style="width:70px; height:70px; font-size:28px; font-weight:700;">
                        {{ strtoupper(substr($client->name, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="mb-0 fw-bold">{{ $client->name }}</h4>
                        <small class="text-light fst-italic">{{ ucfirst($client->type ?? '—') }}</small>
                    </div>
                </div>
                <div>
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm me-2">
                        <i class="ri-edit-2-line me-1"></i> Edit
                    </a>
                    <a href="{{ route('clients.index') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-go-back-line me-1"></i> Back
                    </a>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body p-4">
                <div class="row gy-4 gx-5">
                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <h6 class="fw-bold text-secondary mb-1">
                                <i class="ri-smartphone-line text-primary me-1"></i> Phone
                            </h6>
                            <p class="mb-0 text-dark">{{ $client->phone ?? '—' }}</p>
                        </div>
                        <div class="info-item mb-3">
                            <h6 class="fw-bold text-secondary mb-1">
                                <i class="ri-map-pin-line text-danger me-1"></i> Address
                            </h6>
                            <p class="mb-0 text-dark">{{ $client->address ?? '—' }}</p>
                        </div>
                        <div class="info-item">
                            <h6 class="fw-bold text-secondary mb-1">
                                <i class="ri-calendar-event-line text-info me-1"></i> Created At
                            </h6>
                            <p class="mb-0 text-dark">{{ $client->created_at->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="info-item mb-3">
                            <h6 class="fw-bold text-secondary mb-1">
                                <i class="ri-sticky-note-line text-warning me-1"></i> Notes
                            </h6>
                            <div class="bg-light p-3 rounded border small text-dark">
                                {{ $client->notes ?? '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="card-footer bg-light text-center py-3">
                <span class="text-muted small">
                    <i class="ri-time-line me-1"></i> Last updated:
                    {{ $client->updated_at->diffForHumans() }}
                </span>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .card {
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
