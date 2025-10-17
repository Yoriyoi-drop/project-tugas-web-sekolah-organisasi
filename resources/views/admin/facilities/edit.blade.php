@extends('admin.layouts.app')

@section('title', 'Edit Fasilitas')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>Edit Fasilitas: {{ $facility->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.facilities.update', $facility) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Fasilitas *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $facility->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Kategori *</label>
                                    <select class="form-select @error('category') is-invalid @enderror" 
                                            id="category" name="category" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="Ruang Kelas" {{ old('category', $facility->category) == 'Ruang Kelas' ? 'selected' : '' }}>Ruang Kelas</option>
                                        <option value="Laboratorium" {{ old('category', $facility->category) == 'Laboratorium' ? 'selected' : '' }}>Laboratorium</option>
                                        <option value="Perpustakaan" {{ old('category', $facility->category) == 'Perpustakaan' ? 'selected' : '' }}>Perpustakaan</option>
                                        <option value="Masjid" {{ old('category', $facility->category) == 'Masjid' ? 'selected' : '' }}>Masjid</option>
                                        <option value="Olahraga" {{ old('category', $facility->category) == 'Olahraga' ? 'selected' : '' }}>Olahraga</option>
                                        <option value="Kantin" {{ old('category', $facility->category) == 'Kantin' ? 'selected' : '' }}>Kantin</option>
                                        <option value="Asrama" {{ old('category', $facility->category) == 'Asrama' ? 'selected' : '' }}>Asrama</option>
                                        <option value="Lainnya" {{ old('category', $facility->category) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', $facility->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="capacity" class="form-label">Kapasitas</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" value="{{ old('capacity', $facility->capacity) }}" min="1">
                                    <div class="form-text">Jumlah orang yang dapat ditampung</div>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $facility->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="maintenance" {{ old('status', $facility->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="inactive" {{ old('status', $facility->status) == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location', $facility->location) }}" 
                                           placeholder="Lantai 1, Gedung A">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_person" class="form-label">Penanggung Jawab</label>
                                    <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                           id="contact_person" name="contact_person" value="{{ old('contact_person', $facility->contact_person) }}" 
                                           placeholder="Nama penanggung jawab">
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="operating_hours" class="form-label">Jam Operasional</label>
                                    <input type="text" class="form-control @error('operating_hours') is-invalid @enderror" 
                                           id="operating_hours" name="operating_hours" value="{{ old('operating_hours', $facility->operating_hours) }}" 
                                           placeholder="07:00 - 17:00">
                                    @error('operating_hours')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="features" class="form-label">Fitur/Fasilitas</label>
                            <input type="text" class="form-control @error('features') is-invalid @enderror" 
                                   id="features" name="features" 
                                   value="{{ old('features', $facility->features ? implode(', ', $facility->features) : '') }}" 
                                   placeholder="AC, Proyektor, WiFi, Papan Tulis (pisahkan dengan koma)">
                            <div class="form-text">Pisahkan setiap fitur dengan koma</div>
                            @error('features')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Gambar Fasilitas</label>
                            @if($facility->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($facility->image) }}" alt="{{ $facility->name }}" 
                                         class="img-thumbnail" style="max-width: 200px;">
                                    <div class="form-text">Gambar saat ini</div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Fasilitas
                            </button>
                            <a href="{{ route('admin.facilities.show', $facility) }}" class="btn btn-info">
                                <i class="bi bi-eye me-2"></i>Lihat Detail
                            </a>
                            <a href="{{ route('admin.facilities.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection