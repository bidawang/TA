@extends('layout')

@section('content')
<div class="container">
    <h3 class="mb-4 text-center">Profil Pengguna</h3>

    <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center">
            <!-- Avatar -->
            <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle me-3" width="120" height="120" referrerpolicy="no-referrer">

            <!-- Info Section -->
            <div class="flex-grow-1">
                <h4 class="mb-1">{{ $user->name }}</h4>
                <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="mb-1"><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                <p class="mb-1"><strong>Status:</strong> {{ ucfirst($user->status) }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $user->phone ?? 'No phone number' }}</p>
                <p class="mb-1"><strong>Address:</strong> {{ $user->address ?? 'No address provided' }}</p>
            </div>
        </div>
    </div>

    <div class="mt-3 text-center">
        <a href="{{ route('user.edit', $user->google_id) }}" class="btn btn-warning">Edit Profil</a>
    </div>
</div>
@endsection
