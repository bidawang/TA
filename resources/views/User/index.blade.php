@extends('layout')

@section('content')
<div class="container">
    <h3 class="mb-4 text-center">Daftar Pengguna</h3>

    @foreach ($users as $user)
        <div class="card mb-3 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <!-- Avatar -->
                <img src="{{ $user->avatar }}" alt="Avatar" class="rounded-circle me-3" width="60" height="60" referrerpolicy="no-referrer">

                <!-- Info Section -->
                <div class="flex-grow-1">
                    <h6 class="mb-1">{{ $user->name }}</h6>

                    <!-- Role Text -->
                    <p class="mb-1">Role: {{ ucfirst($user->role) }}</p>

                    <!-- Status Text-->
                    <p class="mb-1">Status: {{ ucfirst($user->status) }}</p>

                    <!-- Action Buttons -->
                    <div class="mt-2">
                        <a href="{{ route('user.show', $user->google_id) }}" class="btn btn-sm btn-info">Detail</a>
                        <a href="{{ route('user.edit', $user->google_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('user.destroy', $user->google_id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
