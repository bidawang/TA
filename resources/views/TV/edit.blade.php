@extends('layout')

@section('content')
<div class="container">
    <h1>Edit TV</h1>
    <form action="{{ route('tv.update', $tv->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('tv.form')

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
