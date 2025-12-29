@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold mb-3">Edit Bahan Baku</h4>

    <div class="card p-4">
        <form action="{{ route('bahan-baku.update', $bahanBaku->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('bahan_baku.form', ['button' => 'Update'])
        </form>
    </div>

</div>
@endsection