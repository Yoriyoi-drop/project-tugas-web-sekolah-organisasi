@extends('admin.layouts.app')

@section('title', 'Detail Fasilitas - ' . $facility->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-eye me-2"></i>Detail Fasilitas
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('admin.facilities.edit', $facility) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-2"></i>Edit
                        </a>
                        <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($facility->image)
                        <div class="col-md-4 mb-4">
                            <img src="{{ Storage::url($facility->image) }}" 
                                 alt="{{ $facility->name }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                        <div class="col-md-8">
                        @else
                        <div class="col-12">
                        @endif
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Nama Fasilitas:</strong></div>
                                <div class="col-sm-9">{{ $facility->name }}</div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Kategori:</strong></div>
                                <div class="col-sm-9">
                                    <span class="badge bg-info fs-6">{{ $facility->category }}</span>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Status:</strong></div>
                                <div class="col-sm-9">
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
                                    <span class="badge bg-{{ $statusColors[$facility->status] }} fs-6">
                                        {{ $statusLabels[$facility->status] }}
                                    </span>
                                </div>
                            </div>
                            
                            @if($facility->capacity)
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Kapasitas:</strong></div>
                                <div class="col-sm-9">
                                    <i class="bi bi-people me-1"></i>{{ $facility->capacity }} orang
                                </div>
                            </div>
                            @endif
                            
                            @if($facility->location)
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Lokasi:</strong></div>
                                <div class="col-sm-9">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $facility->location }}
                                </div>
                            </div>
                            @endif
                            
                            @if($facility->contact_person)
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Penanggung Jawab:</strong></div>
                                <div class="col-sm-9">
                                    <i class="bi bi-person me-1"></i>{{ $facility->contact_person }}
                                </div>
                            </div>
                            @endif
                            
                            @if($facility->operating_hours)
                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Jam Operasional:</strong></div>
                                <div class="col-sm-9">
                                    <i class="bi bi-clock me-1"></i>{{ $facility->operating_hours }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5><i class="bi bi-info-circle me-2"></i>Deskripsi</h5>
                            <div class="bg-light p-3 rounded">
                                {{ $facility->description }}
                            </div>
                        </div>
                    </div>
                    
                    @if($facility->features && count($facility->features) > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5><i class="bi bi-list-check me-2"></i>Fitur & Fasilitas</h5>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($facility->features as $feature)
                                    <span class="badge bg-secondary fs-6">
                                        <i class="bi bi-check-circle me-1"></i>{{ $feature }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5><i class="bi bi-calendar me-2"></i>Informasi Tambahan</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Dibuat:</small><br>
                                    <span>{{ $facility->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Terakhir Diupdate:</small><br>
                                    <span>{{ $facility->updated_at->format('d M Y, H:i') }}</span>
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