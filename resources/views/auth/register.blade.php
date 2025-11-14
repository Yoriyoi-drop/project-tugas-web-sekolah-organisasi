<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - {{ site_name() }}</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons-npm/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #20b2aa 0%, #17a2b8 100%); min-height: 100vh; }
        .card-reg { backdrop-filter: blur(10px); background: rgba(255,255,255,0.95); }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                <div class="card card-reg shadow-lg border-0 my-5">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
                            <h3 class="mt-3 fw-bold">Daftar Akun</h3>
                            <p class="text-muted">Buat akun untuk mengakses panel</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div><small>{{ $error }}</small></div>
                                @endforeach
                            </div>
                        @endif

                        @if (!$hasNik || !$hasNis)
                            <div class="alert alert-warning d-flex">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <div>
                                    <strong>Perhatian:</strong> Kolom NIK dan/atau NIS belum tersedia pada panel. Silakan hubungi admin untuk menambahkan kolom tersebut terlebih dahulu sebelum pendaftaran bisa dilakukan.
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                </div>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email (Gmail)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="nama@gmail.com" required>
                                </div>
                                <small class="text-muted">Hanya email Gmail yang diterima.</small>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    </div>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIK</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                        <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" value="{{ old('nik') }}" required>
                                    </div>
                                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIS</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-card-list"></i></span>
                                        <input type="text" name="nis" class="form-control @error('nis') is-invalid @enderror" value="{{ old('nis') }}" required>
                                    </div>
                                    @error('nis')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2" @if(!$hasNik || !$hasNis) disabled @endif>
                                <i class="bi bi-person-check me-2"></i>Buat Akun
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none">Sudah punya akun? Masuk</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
