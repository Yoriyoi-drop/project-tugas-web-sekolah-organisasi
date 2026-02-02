@extends('layouts.app')

@section('title', $organization->name . ' - Organisasi')

@section('content')
<section class="page-header">
  <div class="container">
    <div class="row justify-content-center text-center">
      <div class="col-lg-8">
        <h1 class="display-4 fw-bold mb-2">
          @if($organization->image)
            <img src="{{ $organization->image }}" alt="{{ $organization->name }}" style="height:64px;width:64px;object-fit:contain" class="me-2 align-text-top">
          @else
            <i class="bi {{ $organization->icon }} me-2"></i>
          @endif
          {{ $organization->name }}
        </h1>
        @if($organization->tagline)
          <p class="lead mb-0">"{{ $organization->tagline }}"</p>
        @endif
      </div>
    </div>
  </div>
</section>

<section class="py-5">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('organisasi') }}">Organisasi</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $organization->name }}</li>
      </ol>
    </nav>

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i>Tentang Organisasi</h5>
            <p class="mb-0">{{ $organization->description }}</p>
          </div>
        </div>

        @if($organization->programs && is_array($organization->programs) && count($organization->programs) > 0)
        <div class="card border-0 shadow-sm mt-3">
          <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-target me-2"></i>Program Kerja</h5>
            <ul class="mb-0">
              @foreach($organization->programs as $program)
                <li>{{ $program }}</li>
              @endforeach
            </ul>
          </div>
        </div>
        @endif

        @if($organization->leadership && is_array($organization->leadership) && count($organization->leadership) > 0)
        <div class="card border-0 shadow-sm mt-3">
          <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-people me-2"></i>Struktur Kepengurusan</h5>
            <div class="row g-3">
              @foreach($organization->leadership as $leader)
              <div class="col-md-6">
                <div class="p-3 bg-light rounded h-100">
                  <div class="fw-semibold">{{ $leader['name'] ?? '-' }}</div>
                  <div class="text-muted">{{ $leader['position'] ?? '' }}</div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif

        <div class="card border-0 shadow-sm mt-3">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h5 class="mb-0"><i class="bi bi-people-fill me-2"></i>Anggota Aktif</h5>
              <div class="d-flex gap-2">
                <span class="badge bg-primary">{{ $organization->activeMembers->count() }} Aktif</span>
                @if($memberStats['inactive'] ?? 0)
                <span class="badge bg-secondary">{{ $memberStats['inactive'] }} Tidak Aktif</span>
                @endif
                @if($memberStats['alumni'] ?? 0)
                <span class="badge bg-success">{{ $memberStats['alumni'] }} Alumni</span>
                @endif
              </div>
            </div>
            
            @if($organization->activeMembers->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 g-2">
              @foreach($organization->activeMembers as $member)
              <div class="col">
                <div class="p-2 border rounded d-flex align-items-center gap-2">
                  <i class="bi bi-person-circle fs-4 text-secondary"></i>
                  <div class="flex-grow-1">
                    <div class="fw-semibold">{{ $member->full_name }}</div>
                    <small class="text-muted">
                      {{ $member->member_type === 'student' ? 'Kelas ' . ($member->student->class ?? '-') : 'Guru' }} 
                      • {{ $member->role_display_name }}
                    </small>
                  </div>
                  @if($member->role !== 'member')
                  <span class="badge bg-warning text-dark">{{ $member->role_display_name }}</span>
                  @endif
                </div>
              </div>
              @endforeach
            </div>
            @else
              <p class="text-muted mb-0">Belum ada anggota aktif terdaftar.</p>
            @endif
          </div>
        </div>

        @if($leadershipMembers->count() > 0)
        <div class="card border-0 shadow-sm mt-3">
          <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-award me-2"></i>Struktur Kepemimpinan</h5>
            <div class="row g-3">
              @foreach($leadershipMembers as $leader)
              <div class="col-md-6">
                <div class="p-3 bg-light rounded h-100">
                  <div class="fw-semibold">{{ $leader->full_name }}</div>
                  <div class="text-muted">{{ $leader->role_display_name }}</div>
                  @if($leader->position)
                  <div class="small text-primary">{{ $leader->position }}</div>
                  @endif
                </div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
        @endif

        <!-- Collaboration Features Section -->
        <div class="card border-0 shadow-sm mt-3">
          <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-chat-square-text me-2"></i>Aktivitas Kolaborasi</h5>
            
            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-3" id="collaborationTabs" role="tablist">
              <li class="nav-item" role="presentation">
                <button class="nav-link active" id="discussions-tab" data-bs-toggle="tab" data-bs-target="#discussions" type="button" role="tab">
                  <i class="bi bi-chat-dots me-1"></i>Diskusi
                  <span class="badge bg-primary ms-1">{{ $organization->activeDiscussions()->count() }}</span>
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="activities-tab" data-bs-toggle="tab" data-bs-target="#activities" type="button" role="tab">
                  <i class="bi bi-calendar-event me-1"></i>Kegiatan
                  <span class="badge bg-success ms-1">{{ $organization->upcomingActivities()->count() }}</span>
                </button>
              </li>
              <li class="nav-item" role="presentation">
                <button class="nav-link" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements" type="button" role="tab">
                  <i class="bi bi-megaphone me-1"></i>Pengumuman
                  <span class="badge bg-warning ms-1">{{ $organization->activeAnnouncements()->count() }}</span>
                </button>
              </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="collaborationTabsContent">
              <!-- Discussions Tab -->
              <div class="tab-pane fade show active" id="discussions" role="tabpanel">
                @php
                  $latestDiscussions = $organization->getLatestDiscussions(3);
                @endphp
                @if($latestDiscussions->count() > 0)
                  <div class="list-group list-group-flush">
                    @foreach($latestDiscussions as $discussion)
                    <div class="list-group-item list-group-item-action">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $discussion->title }}</h6>
                        <small class="text-muted">{{ $discussion->time_ago }}</small>
                      </div>
                      <p class="mb-1 text-truncate">{{ Str::limit(strip_tags($discussion->content), 80) }}</p>
                      <small class="text-muted">
                        <i class="bi bi-person me-1"></i>{{ $discussion->author->name }}
                        @if($discussion->reply_count > 0)
                        <span class="ms-2"><i class="bi bi-chat me-1"></i>{{ $discussion->reply_count }} balasan</span>
                        @endif
                        @if($discussion->is_pinned)
                        <span class="ms-2 badge bg-warning text-dark"><i class="bi bi-pin me-1"></i>Dipin</span>
                        @endif
                      </small>
                    </div>
                    @endforeach
                  </div>
                  <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-primary btn-sm">Lihat Semua Diskusi</a>
                  </div>
                @else
                  <div class="text-center text-muted py-3">
                    <i class="bi bi-chat-dots fs-1 mb-2 d-block"></i>
                    <p>Belum ada diskusi. Mulai diskusi pertama!</p>
                  </div>
                @endif
              </div>

              <!-- Activities Tab -->
              <div class="tab-pane fade" id="activities" role="tabpanel">
                @php
                  $upcomingActivities = $organization->getUpcomingEvents(3);
                @endphp
                @if($upcomingActivities->count() > 0)
                  <div class="list-group list-group-flush">
                    @foreach($upcomingActivities as $activity)
                    <div class="list-group-item list-group-item-action">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">{{ $activity->title }}</h6>
                        <small class="text-muted">{{ $activity->start_datetime->format('d M H:i') }}</small>
                      </div>
                      <p class="mb-1 text-truncate">{{ Str::limit($activity->description, 80) }}</p>
                      <small class="text-muted">
                        <span class="badge bg-{{ $activity->type === 'meeting' ? 'info' : 'success' }}">{{ $activity->formatted_type }}</span>
                        @if($activity->location)
                        <span class="ms-2"><i class="bi bi-geo-alt me-1"></i>{{ $activity->location }}</span>
                        @endif
                        @if($activity->max_participants)
                        <span class="ms-2"><i class="bi bi-people me-1"></i>{{ $activity->registered_count }}/{{ $activity->max_participants }}</span>
                        @endif
                      </small>
                    </div>
                    @endforeach
                  </div>
                  <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-success btn-sm">Lihat Semua Kegiatan</a>
                  </div>
                @else
                  <div class="text-center text-muted py-3">
                    <i class="bi bi-calendar-event fs-1 mb-2 d-block"></i>
                    <p>Belum ada kegiatan yang dijadwalkan.</p>
                  </div>
                @endif
              </div>

              <!-- Announcements Tab -->
              <div class="tab-pane fade" id="announcements" role="tabpanel">
                @php
                  $importantAnnouncements = $organization->getImportantAnnouncements(3);
                @endphp
                @if($importantAnnouncements->count() > 0)
                  <div class="list-group list-group-flush">
                    @foreach($importantAnnouncements as $announcement)
                    <div class="list-group-item list-group-item-action">
                      <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">
                          {{ $announcement->title }}
                          @if($announcement->priority === 'urgent')
                          <span class="badge bg-danger ms-1">PENTING</span>
                          @endif
                        </h6>
                        <small class="text-muted">{{ $announcement->time_ago }}</small>
                      </div>
                      <p class="mb-1 text-truncate">{{ Str::limit(strip_tags($announcement->content), 80) }}</p>
                      <small class="text-muted">
                        <i class="bi bi-person me-1"></i>{{ $announcement->author->name }}
                        <span class="ms-2 badge bg-{{ $announcement->priority === 'urgent' ? 'danger' : 'secondary' }}">{{ $announcement->formatted_priority }}</span>
                      </small>
                    </div>
                    @endforeach
                  </div>
                  <div class="text-center mt-3">
                    <a href="#" class="btn btn-outline-warning btn-sm">Lihat Semua Pengumuman</a>
                  </div>
                @else
                  <div class="text-center text-muted py-3">
                    <i class="bi bi-megaphone fs-1 mb-2 d-block"></i>
                    <p>Tidak ada pengumuman penting saat ini.</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="mb-3"><i class="bi bi-ui-checks-grid me-2"></i>Informasi</h5>
            <ul class="list-unstyled mb-3">
              <li class="mb-2"><span class="badge bg-{{ $organization->color ?? 'primary' }}">{{ $organization->type }}</span></li>
              @if($organization->email)
              <li class="mb-1"><i class="bi bi-envelope me-2"></i>{{ $organization->email }}</li>
              @endif
              @if($organization->phone)
              <li class="mb-1"><i class="bi bi-phone me-2"></i>{{ $organization->phone }}</li>
              @endif
              @if($organization->location)
              <li class="mb-1"><i class="bi bi-geo-alt me-2"></i>{{ $organization->location }}</li>
              @endif
              @if(!empty($organization->contact_person))
              <li class="mb-1"><i class="bi bi-person-lines-fill me-2"></i>{{ $organization->contact_person }}</li>
              @endif
              @if(!empty($organization->website))
              <li class="mb-1"><i class="bi bi-globe me-2"></i><a href="{{ $organization->website }}" target="_blank" rel="noopener">{{ $organization->website }}</a></li>
              @endif
            </ul>

            @if($organization->tags && is_array($organization->tags) && count($organization->tags) > 0)
            <div class="mb-3">
              @foreach($organization->tags as $tag)
                <span class="badge bg-light text-dark border me-1 mb-1">{{ $tag }}</span>
              @endforeach
            </div>
            @endif

            @if($organization->activePeriod)
            <div class="mb-3">
              <h6 class="text-muted mb-2">Periode Aktif</h6>
              <div class="small">
                <strong>{{ $organization->activePeriod->period_name }}</strong><br>
                <span class="text-muted">{{ $organization->activePeriod->duration }}</span>
              </div>
            </div>
            @endif

            @if($organization->periods->count() > 0)
            <div class="mb-3">
              <h6 class="text-muted mb-2">Periode Sebelumnya</h6>
              <div class="small">
                @foreach($organization->periods->take(2) as $period)
                @if(!$period->is_active)
                <div class="mb-1">{{ $period->period_name }}</div>
                @endif
                @endforeach
              </div>
            </div>
            @endif

            <div class="d-grid">
              <a class="btn btn-{{ $organization->color ?? 'primary' }}" href="{{ route('registration.show', $organization) }}">
                <i class="bi bi-person-plus me-2"></i>Bergabung
              </a>
              <a class="btn btn-outline-secondary mt-2" href="{{ route('organisasi') }}">
                <i class="bi bi-arrow-left me-2"></i>Kembali
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
