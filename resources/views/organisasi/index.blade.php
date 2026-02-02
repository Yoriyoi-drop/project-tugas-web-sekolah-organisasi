@extends('layouts.app')

@section('title', 'Daftar Organisasi')

@section('content')
<section class="page-header">
  <div class="container">
    <h1 class="display-5 fw-bold">Organisasi</h1>
  </div>
</section>
<section class="py-5">
  <div class="container">
    <div class="row g-4">
      @forelse($organizations as $org)
        <div class="col-md-6 col-lg-4">
          <div class="card h-100 border-0 shadow-sm">
            <div class="card-body">
              <div class="d-flex align-items-center mb-2">
                @if($org->icon)
                  <i class="bi {{ $org->icon }} text-{{ $org->color ?? 'primary' }} me-2" style="font-size:1.5rem"></i>
                @endif
                <h5 class="mb-0">{{ $org->name }}</h5>
              </div>
              @if($org->tagline)
                <div class="text-muted mb-2">{{ $org->tagline }}</div>
              @endif
              <p class="mb-3">{{ Str::limit($org->description, 100) }}</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-primary rounded-pill">{{ $org->member_count ?? 0 }} Anggota</span>
                <a href="{{ route('organisasi.show', $org->slug) }}" class="btn btn-outline-primary btn-sm">Selengkapnya</a>
              </div>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center text-muted">Belum ada organisasi.</div>
      @endforelse
    </div>
  </div>
</section>
@endsection
