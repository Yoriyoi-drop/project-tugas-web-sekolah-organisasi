@extends('admin.layouts.app')

@section('title', 'Detail Value - ' . $value->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-eye me-2"></i>Detail Value
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.values.edit', $value) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                        <a href="{{ route('admin.values.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Judul:</strong></div>
                        <div class="col-sm-9">{{ $value->title }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Deskripsi:</strong></div>
                        <div class="col-sm-9">{{ $value->description }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Icon:</strong></div>
                        <div class="col-sm-9">
                            @if($value->icon)
                                <i class="{{ $value->icon }}" style="font-size: 1.5rem; color: {{ $value->color ?? '#6c757d' }};"></i>
                                <code class="ms-2">{{ $value->icon }}</code>
                            @else
                                <span class="text-muted">Tidak ada icon</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Warna:</strong></div>
                        <div class="col-sm-9">
                            @if($value->color)
                                <div class="d-flex align-items-center">
                                    <div style="width: 30px; height: 30px; background-color: {{ $value->color }}; border-radius: 5px; margin-right: 10px; border: 1px solid #dee2e6;"></div>
                                    <code>{{ $value->color }}</code>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Urutan:</strong></div>
                        <div class="col-sm-9">{{ $value->order }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-3"><strong>Status:</strong></div>
                        <div class="col-sm-9">
                            @if($value->is_active)
                                <span class="badge bg-success fs-6">Aktif</span>
                            @else
                                <span class="badge bg-danger fs-6">Tidak Aktif</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5><i class="bi bi-calendar me-2"></i>Informasi Tambahan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Dibuat:</small><br>
                                    <span>{{ $value->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Terakhir Diupdate:</small><br>
                                    <span>{{ $value->updated_at->format('d M Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
