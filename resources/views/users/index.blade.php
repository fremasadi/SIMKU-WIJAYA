@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    
    <!-- Header -->
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Dashboard /</span> Manajemen Pengguna
    </h4>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Card Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pengguna</h5>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah User
            </a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @forelse($users as $u)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $u->name }}</strong></td>
                        <td>{{ $u->email }}</td>
                        <td>
                            <span class="badge bg-label-info me-1">{{ strtoupper($u->role) }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('users.edit', $u->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                            <i class="bx bx-trash me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <p class="text-muted mb-0">Tidak ada data pengguna</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if(method_exists($users, 'links'))
    <div class="mt-3">
        {{ $users->links() }}
    </div>
    @endif

</div>
@endsection