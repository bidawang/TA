@extends('layout')

@section('content')
<div class="container">
    <h1>Tambah TV</h1>
    <form action="{{ route('tv.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @include('tv.form')

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
