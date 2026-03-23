{{-- resources/views/units/reports/index.blade.php --}}
@extends('layouts.master')

@section('content')
    <div class="container py-4">

        {{-- KPIs Section --}}
        @include('units.reports.kpis')

        <div class="mt-4">
            {{-- Real Estate --}}
            @include('units.reports.real_estate')

            {{-- Sales --}}
            @include('units.reports.sales')

            {{-- Construction --}}
            @include('units.reports.construction')

            {{-- Finance --}}
            @include('units.reports.finance')
        </div>

    </div>
@endsection
