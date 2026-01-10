@extends('layouts.app')

@section('title', $post->title . ' - Blog MA NU Nusantara')

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('blog') }}">Blog</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
                        </ol>
                    </nav>

                    <article class="blog-post">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="img-fluid rounded mb-4 w-100" style="max-height: 400px; object-fit: cover;">
                        @endif

                        <div class="d-flex gap-3 mb-3 text-muted">
                            <span class="badge bg-{{ $post->color }}">{{ $post->category }}</span>
                            <small><i class="bi bi-calendar me-1"></i>{{ $post->published_at ? $post->published_at->format('d F Y') : $post->created_at->format('d F Y') }}</small>
                            <small><i class="bi bi-person me-1"></i>{{ $post->author }}</small>
                        </div>

                        <h1 class="display-4 fw-bold mb-4">{{ $post->title }}</h1>

                        <div class="content fs-5 lh-lg mb-5">
                            {!! $post->content !!}
                        </div>

                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <div class="d-flex gap-2">
                                <span class="fw-bold me-2">Bagikan:</span>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" class="text-primary"><i class="bi bi-facebook fs-5"></i></a>
                                <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $post->title }}" target="_blank" class="text-info"><i class="bi bi-twitter fs-5"></i></a>
                                <a href="https://wa.me/?text={{ $post->title }}%20{{ url()->current() }}" target="_blank" class="text-success"><i class="bi bi-whatsapp fs-5"></i></a>
                            </div>
                            <a href="{{ route('blog') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Blog
                            </a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
@endsection
