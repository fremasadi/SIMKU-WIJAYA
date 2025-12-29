@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold mb-3">Tambah Produk</h4>

    <div class="card p-4">
        <form action="{{ route('produk.store') }}" method="POST">
            @csrf
            @include('produk.form', ['button' => 'Simpan'])
        </form>
    </div>

</div>
@endsection