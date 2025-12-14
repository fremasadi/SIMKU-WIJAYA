@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold">Dashboard</h4>

    <p>Selamat datang, {{ auth()->user()->name }}!</p>
</div>
@endsection
