@extends('layouts.app')

@section('content')
@php
    $isOwner = auth()->user()->role == 'owner';
@endphp

<div class="container-xxl flex-grow-1 container-p-y">

    <!-- Header -->
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Master Data /</span> Presensi
    </h4>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter -->
    <div class="card mb-3 p-3">
        <form action="{{ route('presensi.index') }}" method="GET" class="row g-2">
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" name="date" class="form-control" value="{{ request('date', $date) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Karyawan</label>
                <select name="karyawan_id" class="form-control">
                    <option value="">Semua Karyawan</option>
                    @foreach($karyawans as $k)
                        <option value="{{ $k->id }}" {{ request('karyawan_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('presensi.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Presensi Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Presensi Tanggal: {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}</h5>
        </div>

        <div class="table-responsive text-nowrap p-3">
            @if(!$isOwner)
            <form action="{{ route('presensi.updateStatus') }}" method="POST">
                @csrf
            @endif
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Karyawan</th>
                            <th>Status Hadir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($presensis as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->karyawan->nama }}</td>
                            <td class="text-center">
                                @if($isOwner)
                                    @if($p->status_hadir == 'Hadir')
                                        <span class="badge bg-label-success">Hadir</span>
                                    @else
                                        <span class="badge bg-label-danger">Tidak Hadir</span>
                                    @endif
                                @else
                                <input type="hidden" name="presensi_ids[]" value="{{ $p->id }}">
                                <input type="checkbox" name="status_hadir[{{ $p->id }}]" value="Hadir"
                                    {{ $p->status_hadir == 'Hadir' ? 'checked' : '' }}>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-3">Tidak ada data presensi</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @if(!$isOwner && $presensis->count())
                <button type="submit" class="btn btn-success mt-2">Simpan Presensi</button>
                @endif
            @if(!$isOwner)
            </form>
            @endif
        </div>
    </div>

</div>
@endsection
