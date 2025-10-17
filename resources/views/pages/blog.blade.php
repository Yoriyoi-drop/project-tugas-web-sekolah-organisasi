@extends('layouts.app')

@section('title', 'Blog - MA NU Nusantara')

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <img src="/images/blog/default.svg" alt="Blog" style="width: 64px; height: 64px; object-fit: contain;">
                    </div>
                    <h1 class="display-3 fw-bold mb-4">Blog {{ site_name() }}</h1>
                    @if($category || $search)
                        <div class="mb-3">
                            @if($category)
                                <span class="badge bg-primary fs-6 px-3 py-2 me-2">Kategori: {{ $category }}</span>
                            @endif
                            @if($search)
                                <span class="badge bg-success fs-6 px-3 py-2 me-2">Pencarian: "{{ $search }}"</span>
                            @endif
                            <a href="{{ route('blog') }}" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-x-circle me-1"></i>Hapus Semua Filter
                            </a>
                        </div>
                    @endif
                    <p class="lead mb-0">Berita, Artikel, dan Informasi Terkini</p>
                    <p class="mb-0">Seputar Dunia Pendidikan Islam dan Kegiatan Sekolah</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Featured Post -->
                    @if($featuredPost)
                    <x-card class="mb-5 border-0 shadow-lg">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center justify-content-center h-100 bg-{{ $featuredPost->color }} text-white rounded-start" style="min-height: 200px;">
                                    @if($featuredPost->image ?? false)
                                        <img src="{{ $featuredPost->image }}" alt="{{ $featuredPost->title }}" style="width: 64px; height: 64px; object-fit: contain; filter: brightness(0) invert(1);">
                                    @else
                                        <img src="/images/blog/default.svg" alt="Blog" style="width: 64px; height: 64px; object-fit: contain; filter: brightness(0) invert(1);">
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <div class="d-flex gap-3 mb-3">
                                        <span class="badge bg-warning text-dark">📌 Unggulan</span>
                                        <small class="text-muted">📅 {{ $featuredPost->published_at ? $featuredPost->published_at->format('d F Y') : $featuredPost->created_at->format('d F Y') }}</small>
                                        <small class="text-muted">👤 {{ $featuredPost->author }}</small>
                                    </div>
                                    <h3 class="card-title">{{ $featuredPost->title }}</h3>
                                    <p class="card-text">{{ $featuredPost->excerpt }}</p>
                                    <x-button variant="primary">
                                        <i class="bi bi-arrow-right me-2"></i>Baca Selengkapnya
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </x-card>
                    @endif

                    <!-- Blog Posts Grid -->
                    <div class="row g-4 mb-5">
                        @forelse($posts as $post)
                        <div class="col-md-6">
                            <x-card class="h-100 border-0 shadow-sm">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0">
                                        @if($post->image ?? false)
                                            <img src="{{ $post->image }}" alt="{{ $post->title }}" style="width: 32px; height: 32px; object-fit: contain;">
                                        @else
                                            <img src="/images/blog/default.svg" alt="Blog" style="width: 32px; height: 32px; object-fit: contain;">
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex gap-2 mb-2">
                                            <a href="{{ route('blog', ['category' => $post->category]) }}" class="badge bg-{{ $post->color }} text-decoration-none">{{ $post->category }}</a>
                                            <small class="text-muted">📅 {{ $post->published_at ? $post->published_at->format('d F Y') : $post->created_at->format('d F Y') }}</small>
                                        </div>
                                        <h5 class="card-title">{{ $post->title }}</h5>
                                        <p class="card-text text-muted">{{ $post->excerpt }}</p>
                                        <x-button variant="outline-{{ $post->color }}" size="sm">
                                            Baca Selengkapnya
                                        </x-button>
                                    </div>
                                </div>
                            </x-card>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="bi bi-journal-x text-muted" style="font-size: 4rem;"></i>
                                <h4 class="text-muted mt-3">Tidak ada artikel</h4>
                                <p class="text-muted">
                                    @if(($search ?? false) && $category)
                                        Tidak ada artikel yang cocok dengan pencarian "{{ $search }}" dalam kategori "{{ $category }}"
                                    @elseif($search ?? false)
                                        Tidak ada artikel yang cocok dengan pencarian "{{ $search }}"
                                    @elseif($category)
                                        Tidak ada artikel dalam kategori "{{ $category }}"
                                    @else
                                        Belum ada artikel yang dipublikasikan
                                    @endif
                                </p>
                                @if($category || ($search ?? false))
                                    <a href="{{ route('blog') }}" class="btn btn-primary">Lihat Semua Artikel</a>
                                @endif
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    {{ $posts->links() }}
                </div>

                <!-- Sidebar -->
                <aside class="col-lg-4">
                    <!-- Search -->
                    <x-card class="mb-4">
                        <h5 class="mb-3">
                            <i class="bi bi-search me-2"></i>Cari Artikel
                        </h5>
                        <form method="GET" action="{{ route('blog') }}">
                            @if($category)
                                <input type="hidden" name="category" value="{{ $category }}">
                            @endif
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari artikel..." value="{{ $search ?? '' }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                        @if($search ?? false)
                            <div class="mt-2">
                                <small class="text-muted">Pencarian: "{{ $search }}"</small>
                                <a href="{{ route('blog', $category ? ['category' => $category] : []) }}" class="btn btn-sm btn-outline-secondary ms-2">
                                    <i class="bi bi-x"></i> Hapus
                                </a>
                            </div>
                        @endif
                    </x-card>

                    <!-- Categories -->
                    <x-card class="mb-4">
                        <h5 class="mb-3">
                            <i class="bi bi-folder me-2"></i>Kategori
                        </h5>
                        <div class="list-group list-group-flush">
                            <a href="{{ route('blog') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ !$category ? 'active' : '' }}">
                                Semua Artikel <span class="badge {{ !$category ? 'bg-white text-primary' : 'bg-primary' }} rounded-pill">{{ $totalPosts }}</span>
                            </a>
                            @foreach($categories as $cat)
                            <a href="{{ route('blog', ['category' => $cat->category]) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ $category == $cat->category ? 'active' : '' }}">
                                {{ $cat->category }} <span class="badge {{ $category == $cat->category ? 'bg-white text-primary' : 'bg-primary' }} rounded-pill">{{ $cat->count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </x-card>

                    <!-- Recent Posts -->
                    <x-card>
                        <h5 class="mb-3">
                            <i class="bi bi-clock me-2"></i>Artikel Terbaru
                        </h5>
                        @foreach($recentPosts as $recent)
                        <div class="d-flex align-items-start gap-3 mb-3">
                            @if($recent->image ?? false)
                                <img src="{{ $recent->image }}" alt="{{ $recent->title }}" style="width: 24px; height: 24px; object-fit: contain;">
                            @else
                                <img src="/images/blog/default.svg" alt="Blog" style="width: 24px; height: 24px; object-fit: contain;">
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $recent->title }}</h6>
                                <small class="text-muted">{{ $recent->published_at ? $recent->published_at->format('d F Y') : $recent->created_at->format('d F Y') }}</small>
                            </div>
                        </div>
                        @if(!$loop->last)<hr>@endif
                        @endforeach
                    </x-card>
                </aside>
            </div>
        </div>
    </section>
@endsection
