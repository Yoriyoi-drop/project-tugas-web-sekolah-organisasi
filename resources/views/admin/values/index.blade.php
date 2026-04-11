@extends('admin.layouts.app')

@section('title', 'Kelola Values')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-star me-2"></i>Kelola Values
                    </h4>
                    <a href="{{ route('admin.values.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Tambah Value
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
                                    <th width="80">Icon</th>
                                    <th>Judul</th>
                                    <th>Deskripsi</th>
                                    <th width="100">Warna</th>
                                    <th width="80">Urutan</th>
                                    <th width="100">Status</th>
                                    <th width="200">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($values as $value)
                                <tr>
                                    <td class="text-center">
                                        @if($value->icon)
                                            <i class="{{ $value->icon }}" style="font-size: 1.5rem; color: {{ $value->color ?? '#6c757d' }};"></i>
                                        @else
                                            <i class="bi bi-star" style="font-size: 1.5rem;"></i>
                                        @endif
                                    </td>
                                    <td><strong>{{ $value->title }}</strong></td>
                                    <td>{{ Str::limit($value->description, 60) }}</td>
                                    <td>
                                        @if($value->color)
                                            <div class="d-flex align-items-center">
                                                <div style="width: 20px; height: 20px; background-color: {{ $value->color }}; border-radius: 3px; margin-right: 8px;"></div>
                                                <small>{{ $value->color }}</small>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $value->order }}</td>
                                    <td>
                                        @if($value->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.values.show', $value) }}"
                                               class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.values.edit', $value) }}"
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admin.values.destroy', $value) }}"
                                                  method="POST" class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus value ini?')">
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
                                        <i class="bi bi-star text-muted" style="font-size: 3rem;"></i>
                                        <p class="text-muted mt-2">Belum ada value yang ditambahkan.</p>
                                        <a href="{{ route('admin.values.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Tambah Value Pertama
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($values->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $values->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
