@extends('admin.layouts.app')

@section('title', 'Manajemen PPDB')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manajemen PPDB</h1>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Pendaftar PPDB</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered border-0" width="100%" cellspacing="0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Asal Sekolah</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th>Tanggal Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ppdb as $item)
                        <tr>
                            <td class="font-weight-bold">{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->previous_school }}</td>
                            <td><span class="badge bg-info text-dark">{{ $item->desired_major }}</span></td>
                            <td>
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-warning text-dark',
                                        'approved' => 'bg-success',
                                        'rejected' => 'bg-danger',
                                    ][$item->status ?? 'pending'] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $statusClass }}">{{ ucfirst($item->status ?? 'pending') }}</span>
                            </td>
                            <td>{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.ppdb.show', $item) }}" class="btn btn-info btn-sm" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.ppdb.edit', $item) }}" class="btn btn-warning btn-sm" title="Ubah Status">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.ppdb.destroy', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada pendaftar PPDB.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $ppdb->links() }}
        </div>
    </div>
</div>
@endsection
