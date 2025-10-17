@extends('layouts.app')

@section('title', 'Kegiatan - MA NU Nusantara')

@section('content')
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <i class="bi bi-calendar-event" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="display-3 fw-bold mb-4">Kegiatan Sekolah</h1>
                    <p class="lead mb-0">Berbagai Aktivitas dan Event Organisasi NU</p>
                    <p class="mb-0">Mengembangkan potensi siswa melalui kegiatan yang beragam</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <!-- Filter Tabs -->
            <div class="row mb-5">
                <div class="col-12">
                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <button class="btn btn-primary active" onclick="filterActivities('semua', this)">Semua Kegiatan</button>
                        <button class="btn btn-outline-primary" onclick="filterActivities('kajian', this)">Kajian & Dakwah</button>
                        <button class="btn btn-outline-primary" onclick="filterActivities('kompetisi', this)">Kompetisi</button>
                        <button class="btn btn-outline-primary" onclick="filterActivities('sosial', this)">Bakti Sosial</button>
                        <button class="btn btn-outline-primary" onclick="filterActivities('peringatan', this)">Peringatan</button>
                        <button class="btn btn-outline-primary" onclick="filterActivities('olahraga', this)">Olahraga</button>
                    </div>
                </div>
            </div>

            <!-- Activities Grid -->
            <div class="row g-4 mb-5" id="activitiesGrid">
                @foreach(\App\Models\Activity::latest()->get() as $activity)
                <div class="col-lg-4 col-md-6 activity-card" data-category="{{ $activity->type }}">
                    <x-card class="h-100 border-0 shadow-sm">
                        <div class="position-relative mb-3">
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded" style="height: 120px;">
                                <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>
                            </div>
                            <span class="position-absolute top-0 end-0 badge bg-warning text-dark m-2">{{ $activity->date->format('d M') }}</span>
                        </div>

                        <div class="mb-3">
                            <span class="badge bg-primary mb-2">{{ ucfirst($activity->type) }}</span>
                            <h5 class="card-title">{{ $activity->title }}</h5>
                            <p class="text-primary fw-semibold mb-2">{{ $activity->organizer }}</p>
                            <p class="card-text text-muted">{{ $activity->description }}</p>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-start gap-2 mb-1">
                                <i class="bi bi-calendar-fill text-primary mt-1" style="font-size: 0.8rem;"></i>
                                <small class="text-muted">{{ $activity->date->format('d F Y') }}</small>
                            </div>
                            <div class="d-flex align-items-start gap-2 mb-1">
                                <i class="bi bi-geo-alt-fill text-primary mt-1" style="font-size: 0.8rem;"></i>
                                <small class="text-muted">{{ $activity->location }}</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-auto">
                            <x-button variant="primary" size="sm" class="flex-grow-1">
                                <i class="bi bi-person-plus me-1"></i>Daftar
                            </x-button>
                            <x-button variant="outline-primary" size="sm">
                                <i class="bi bi-info-circle me-1"></i>Detail
                            </x-button>
                        </div>
                    </x-card>
                </div>
                @endforeach
            </div>

            <!-- Upcoming Events -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <x-card class="border-0 shadow-lg">
                        <x-slot name="header">
                            <h4 class="mb-0">
                                <i class="bi bi-clock me-2"></i>Kegiatan Mendatang
                            </h4>
                        </x-slot>

                        @foreach(\App\Models\Activity::where('date', '>', now())->orderBy('date')->take(3)->get() as $event)
                        <div class="d-flex align-items-center gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="bg-primary text-white rounded text-center p-2" style="min-width: 60px;">
                                <div class="fw-bold">{{ $event->date->format('d') }}</div>
                                <small>{{ $event->date->format('M') }}</small>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <small class="text-muted">{{ $event->organizer }} • {{ $event->date->format('H:i') }} WIB • {{ $event->location }}</small>
                            </div>
                            <x-button variant="outline-primary" size="sm">
                                <i class="bi bi-arrow-right"></i>
                            </x-button>
                        </div>
                        @endforeach
                    </x-card>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
function filterActivities(category, button) {
    // Update active button
    document.querySelectorAll('.btn').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-outline-primary');
        btn.classList.remove('active');
    });
    button.classList.remove('btn-outline-primary');
    button.classList.add('btn-primary', 'active');

    // Filter cards
    const cards = document.querySelectorAll('.activity-card');
    cards.forEach(card => {
        if (category === 'semua' || card.dataset.category === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
@endpush
