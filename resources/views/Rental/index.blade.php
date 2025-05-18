@extends('layout')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h3 class="mb-0">Daftar Rental</h3>
        <a href="{{ route('rental.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Tambah Rental
        </a>
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Nav Tabs --}}
    <ul class="nav nav-tabs mb-3" id="rentalTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="aktif-tab" data-bs-toggle="tab" data-bs-target="#aktif" type="button" role="tab">Aktif</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="off-tab" data-bs-toggle="tab" data-bs-target="#off" type="button" role="tab">Nonaktif</button>
        </li>
    </ul>

    {{-- Tab Contents --}}
    <div class="tab-content" id="rentalTabsContent">
        {{-- Aktif --}}
        <div class="tab-pane fade show active" id="aktif" role="tabpanel">
            @include('Rental.partial', ['rentals' => $rentalsAktif])
        </div>

        {{-- Nonaktif --}}
        <div class="tab-pane fade" id="off" role="tabpanel">
            @include('Rental.partial', ['rentals' => $rentalsOff])
        </div>
    </div>
</div>
@endsection
