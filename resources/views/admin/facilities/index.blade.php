@extends('admin.layouts.app')

@section('title', 'Kelola Fasilitas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-building me-2"></i>Kelola Fasilitas
                    </h4>
                    <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Fasilitas
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Fasilitas</th>
                                    <th>Kategori</th>
                                    <th>Kapasitas</th>
                                    <th>Status</th>
                                    <th>Lokasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($facilities as $facility)
                                <tr>
                                    <td>
                                        @if($facility->image)
                                            <img src="{{ Storage::url($facility->image) }}" 
                                                 alt="{{ $facility->name }}" 
                                                 class="img-thumbnail" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $facility->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit($facility->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $facility->category }}</span>
                                    </td>
                                    <td>
                                        @if($facility->capacity)
                                            <i class="bi bi-people me-1"></i>{{ $facility->capacity }} orang
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'active' => 'success',
                                                'maintenance' => 'warning',
                                                'inactive' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'active' => 'Aktif',
                                                'maintenance' => 'Maintenance',
                                                'inactive' => 'Tidak Aktif'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$facility->status] }}">
                                            {{ $statusLabels[$facility->status] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($facility->location)
                                            <i class="bi bi-geo-alt me-1"></i>{{ $facility->location }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.facilities.show', $facility) }}" 
                                               class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.facilities.edit', $facility) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.facilities.destroy', $facility) }}" 
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">Belum ada fasilitas yang ditambahkan.</p>
                                        <a href="{{ route('admin.facilities.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Tambah Fasilitas Pertama
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($facilities->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $facilities->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection