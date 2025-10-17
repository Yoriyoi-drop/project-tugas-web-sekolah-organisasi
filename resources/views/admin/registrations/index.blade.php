@extends('admin.layouts.app')

@section('title', 'Pendaftaran Organisasi')

@section('styles')
<style>
.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.2rem;
}
.btn-group .btn:not(:last-child) {
    margin-right: 2px;
}
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Pendaftaran Organisasi</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Daftar Pendaftaran</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">Nama</th>
                            <th width="15%">Organisasi</th>
                            <th width="10%">Kelas</th>
                            <th width="15%">Email</th>
                            <th width="10%">Status</th>
                            <th width="15%">Tanggal</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registrations as $reg)
                        <tr>
                            <td>{{ $reg->id }}</td>
                            <td>
                                <div class="font-weight-bold">{{ $reg->name }}</div>
                                <small class="text-muted">NIS: {{ $reg->nis }}</small>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ $reg->organization->icon }} text-{{ $reg->organization->color }} me-2"></i>
                                    {{ $reg->organization->name }}
                                </div>
                            </td>
                            <td>{{ $reg->class }}</td>
                            <td>{{ $reg->email }}</td>
                            <td>
                                @if($reg->status == 'pending')
                                    <span class="badge badge-warning">Menunggu</span>
                                @elseif($reg->status == 'approved')
                                    <span class="badge badge-success">Diterima</span>
                                @else
                                    <span class="badge badge-danger">Ditolak</span>
                                @endif
                            </td>
                            <td>{{ $reg->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.registrations.show', $reg) }}" class="btn btn-info" title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($reg->status == 'pending')
                                    <form action="{{ route('admin.registrations.update-status', $reg) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="approved">
                                        <button class="btn btn-success" title="Terima" onclick="return confirm('Terima pendaftaran ini?')">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.registrations.update-status', $reg) }}" method="POST" class="d-inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="btn btn-danger" title="Tolak" onclick="return confirm('Tolak pendaftaran ini?')">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.registrations.destroy', $reg) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-secondary" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <p>Belum ada pendaftaran</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($registrations->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $registrations->firstItem() }} to {{ $registrations->lastItem() }} of {{ $registrations->total() }} results
                    </div>
                    <div>
                        {{ $registrations->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection