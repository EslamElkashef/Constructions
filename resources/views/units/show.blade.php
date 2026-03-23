@extends('layouts.master')

@section('title', $unit->name ?? 'تفاصيل الوحدة')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="container my-4" wire:ignore.self>

        {{-- أزرار التحكم --}}
        <div wire:ignore class="d-flex justify-content-end mb-3 no-print">
            <button type="button" onclick="window.print()" class="btn btn-outline-secondary me-2">
                <i class="ri-printer-line"></i> طباعة
            </button>

            <button id="downloadPDF" type="button" class="btn btn-primary">
                <i class="ri-file-download-line"></i> تحميل PDF
            </button>
        </div>

        {{-- الورقة --}}
        <div id="unit-report" wire:ignore.self class="a4-sheet bg-white shadow-sm p-4 mx-auto">

            {{-- الهيدر --}}
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                <div>
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" width="100" height="100">
                </div>
                <div class="text-center flex-grow-1">
                    <h2 class="fw-bold text-primary mb-0">SkyLine Real Estate</h2>
                    <small class="text-muted">Real Estate & Investment</small>
                </div>
                <div style="width:100px;"></div>
            </div>

            {{-- المعلومات الأساسية --}}
            <h4 class="fw-bold text-secondary border-bottom pb-2 mb-3">المعلومات الأساسية</h4>
            <div class="row g-3 mb-4">
                <div class="col-md-6"><strong>الاسم:</strong> {{ $unit->name ?? '—' }}</div>
                <div class="col-md-6"><strong>العنوان:</strong> {{ $unit->address ?? '—' }}</div>
                <div class="col-md-6"><strong>الهاتف:</strong> {{ $unit->phone ?? '—' }}</div>
                <div class="col-md-6"><strong>النوع:</strong> {{ $unit->type->name_ar ?? ($unit->type->name ?? '—') }}</div>
                <div class="col-md-6"><strong>الحالة:</strong>
                    @switch($unit->status)
                        @case('available')
                            🟢 متاح
                        @break

                        @case('reserved')
                            🟠 محجوز
                        @break

                        @case('sold')
                            🔴 تم البيع
                        @break

                        @default
                            —
                    @endswitch
                </div>
                <div class="col-md-6"><strong>تاريخ الإضافة:</strong> {{ $unit->created_at?->format('Y-m-d') ?? '—' }}</div>
            </div>

            {{-- تفاصيل الوحدة --}}
            @if ($unit->details && $unit->details->count())
                <h4 class="fw-bold text-secondary border-bottom pb-2 mb-3">تفاصيل الوحدة</h4>
                <div class="row g-3 align-items-stretch mb-4">
                    @foreach ($unit->details as $detail)
                        @php
                            $field = strtolower($detail->field ?? '');
                            $icon = match (true) {
                                str_contains($field, 'مساحة') || str_contains($field, 'area') => 'ri-home-2-line',
                                str_contains($field, 'دور') || str_contains($field, 'floor') => 'ri-building-line',
                                str_contains($field, 'غرفة') || str_contains($field, 'room') => 'ri-door-open-line',
                                str_contains($field, 'حمام') || str_contains($field, 'bath') => 'ri-shower-line',
                                str_contains($field, 'سعر') || str_contains($field, 'price')
                                    => 'ri-money-dollar-circle-line',
                                str_contains($field, 'عنوان') || str_contains($field, 'address') => 'ri-map-pin-line',
                                default => 'ri-file-text-line',
                            };
                        @endphp
                        <div class="col-md-6 col-lg-4 d-flex">
                            <div class="detail-card border rounded-3 shadow-sm p-3 w-100 d-flex align-items-center gap-3">
                                <i class="{{ $icon }} text-primary fs-3"></i>
                                <div class="flex-grow-1">
                                    <h6 class="text-secondary fw-bold mb-1">
                                        {{ Str::of($detail->field)->replace('_', ' ')->title() }}
                                    </h6>
                                    <p class="mb-0 text-dark small">{{ $detail->value ?? '—' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">لا توجد تفاصيل لهذه الوحدة.</p>
            @endif

            {{-- الموظف والتوقيع --}}
            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-1">الموظف:</h6>
                        <span>{{ $unit->employee->name ?? '—' }}</span>
                    </div>
                    <div class="text-center">
                        <h6 class="fw-bold mb-1">التوقيع:</h6>
                        <div class="signature-box border border-secondary rounded" style="height:80px; width:250px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- وسائط --}}
        <div class="media-section mt-5" wire:ignore>
            <h4 class="fw-bold mb-3 text-secondary">الوسائط</h4>

            @if ($unit->media->where('type', 'image')->isNotEmpty())
                <h6 class="fw-semibold">الصور</h6>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @foreach ($unit->media->where('type', 'image') as $img)
                        <img src="{{ asset('storage/' . $img->path) }}" class="rounded shadow-sm"
                            style="width:150px; height:150px; object-fit:cover;">
                    @endforeach
                </div>
            @endif

            @if ($unit->media->where('type', 'video')->isNotEmpty())
                <h6 class="fw-semibold">الفيديوهات</h6>
                <div class="d-flex flex-wrap gap-3">
                    @foreach ($unit->media->where('type', 'video') as $v)
                        <video width="300" controls class="rounded shadow-sm">
                            <source src="{{ asset('storage/' . $v->path) }}" type="video/mp4">
                            متصفحك لا يدعم الفيديو.
                        </video>
                    @endforeach
                </div>
            @endif

            @if ($unit->media->isEmpty())
                <p class="text-muted">لا توجد وسائط متاحة</p>
            @endif
        </div>
    </div>
@endsection

@section('css')
    <style>
        .a4-sheet {
            width: 210mm;
            min-height: 297mm;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .detail-card {
            background-color: #fff;
            transition: 0.25s;
            display: flex;
            align-items: center;
        }

        .detail-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        @media print {

            .no-print,
            .btn {
                display: none !important;
            }
        }
    </style>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        document.addEventListener('livewire:navigating', e => e.preventDefault());

        document.addEventListener('DOMContentLoaded', () => {
            const pdfBtn = document.getElementById("downloadPDF");
            if (!pdfBtn) return;

            pdfBtn.addEventListener("click", async (event) => {
                event.preventDefault(); // يمنع أي Reload
                event.stopPropagation();

                pdfBtn.disabled = true;
                pdfBtn.innerHTML = '<i class="ri-loader-4-line spinner me-1"></i> جاري التحميل...';

                const {
                    jsPDF
                } = window.jspdf;
                const element = document.getElementById("unit-report");

                const canvas = await html2canvas(element, {
                    scale: 2
                });
                const imgData = canvas.toDataURL("image/png");
                const pdf = new jsPDF("p", "mm", "a4");
                const pageWidth = pdf.internal.pageSize.getWidth();
                const imgHeight = (canvas.height * pageWidth) / canvas.width;
                pdf.addImage(imgData, "PNG", 0, 0, pageWidth, imgHeight);
                pdf.save("unit-report.pdf");

                pdfBtn.disabled = false;
                pdfBtn.innerHTML = '<i class="ri-file-download-line me-1"></i> تحميل PDF';
            });
        });
    </script>
@endsection
