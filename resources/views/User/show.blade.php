@extends('layout')

@section('content')
<div class="container">
    <div class="card mb-4">
        <div class="card-body d-flex align-items-center">
            <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle me-3" width="80" height="80">
            <div>
                <h4>{{ $user->name }}</h4>
                <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                <p class="mb-1"><strong>Phone:</strong> {{ $user->phone ?? '-' }}</p>
                <p class="mb-1"><strong>Address:</strong> {{ $user->address ?? '-' }}</p>
                <p class="mb-1"><strong>Role:</strong> {{ $user->role }}</p>
                <p class="mb-1"><strong>Status:</strong> {{ $user->status }}</p>
                <a href="{{ route('user.edit', $user->google_id) }}" class="btn btn-warning mt-2">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection
