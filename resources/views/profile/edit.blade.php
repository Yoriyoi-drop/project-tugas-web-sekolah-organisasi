@extends('layouts.app')

@section('title', 'Edit Profile - MA NU Nusantara')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Edit Profile</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="bi bi-person me-2"></i>Informasi Dasar</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Lengkap *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date?->format('Y-m-d')) }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Jenis Kelamin</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="department" class="form-label">Departemen</label>
                                <input type="text" class="form-control @error('department') is-invalid @enderror" 
                                       id="department" name="department" value="{{ old('department', $user->department) }}">
                                @error('department')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">Posisi/Jabatan</label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                       id="position" name="position" value="{{ old('position', $user->position) }}">
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Alamat</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label for="bio" class="form-label">Bio/Deskripsi Diri</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" name="bio" rows="4" maxlength="1000" 
                                          placeholder="Ceritakan tentang diri Anda...">{{ old('bio', $user->bio) }}</textarea>
                                <div class="form-text">Maksimal 1000 karakter</div>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Skills -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="bi bi-star me-2"></i>Keahlian</h5>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="skills" class="form-label">Keahlian (pisahkan dengan koma)</label>
                                <input type="text" class="form-control" id="skills" name="skills" 
                                       value="{{ old('skills', $user->skills ? implode(', ', $user->skills) : '') }}"
                                       placeholder="Contoh: PHP, Laravel, JavaScript, MySQL">
                                <div class="form-text">Pisahkan setiap keahlian dengan koma</div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="bi bi-share me-2"></i>Media Sosial</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="facebook" class="form-label">Facebook</label>
                                <input type="url" class="form-control" id="facebook" name="facebook" 
                                       value="{{ old('facebook', $user->social_links['facebook'] ?? '') }}"
                                       placeholder="https://facebook.com/username">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="twitter" class="form-label">Twitter</label>
                                <input type="url" class="form-control" id="twitter" name="twitter" 
                                       value="{{ old('twitter', $user->social_links['twitter'] ?? '') }}"
                                       placeholder="https://twitter.com/username">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="instagram" class="form-label">Instagram</label>
                                <input type="url" class="form-control" id="instagram" name="instagram" 
                                       value="{{ old('instagram', $user->social_links['instagram'] ?? '') }}"
                                       placeholder="https://instagram.com/username">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <input type="url" class="form-control" id="linkedin" name="linkedin" 
                                       value="{{ old('linkedin', $user->social_links['linkedin'] ?? '') }}"
                                       placeholder="https://linkedin.com/in/username">
                            </div>
                        </div>

                        <!-- Password Change Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3"><i class="bi bi-shield-lock me-2"></i>Keamanan Akun</h5>
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Untuk mengubah password, Anda akan menerima kode verifikasi melalui email.
                                </div>
                                <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                    <i class="bi bi-key me-2"></i>Ubah Password
                                </a>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title"><i class="bi bi-shield-lock me-2"></i>Ubah Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.request-password-change') }}">
                @csrf
                <div class="modal-body">
                    @if($errors->has('current_password') || $errors->has('password') || $errors->has('rate_limit'))
                        <div class="alert alert-danger">
                            @error('current_password'){{ $message }}@enderror
                            @error('password'){{ $message }}@enderror
                            @error('rate_limit'){{ $message }}@enderror
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="modal_current_password" class="form-label">Password Saat Ini *</label>
                        <input type="password" class="form-control" id="modal_current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal_password" class="form-label">Password Baru *</label>
                        <input type="password" class="form-control" id="modal_password" name="password" minlength="8" required>
                        <div class="form-text">
                            <strong>Persyaratan Password:</strong><br>
                            • Minimal 8 karakter<br>
                            • Mengandung huruf besar dan kecil<br>
                            • Mengandung angka<br>
                            • Mengandung simbol (@$!%*?&)
                        </div>
                        <div class="password-strength mt-2">
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar" id="password-strength-bar" style="width: 0%;"></div>
                            </div>
                            <small id="password-strength-text" class="text-muted">Kekuatan password</small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="modal_password_confirmation" class="form-label">Konfirmasi Password Baru *</label>
                        <input type="password" class="form-control" id="modal_password_confirmation" name="password_confirmation" required>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Kode verifikasi akan dikirim ke email Anda untuk konfirmasi perubahan password.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-envelope me-2"></i>Kirim Kode Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->has('current_password') || $errors->has('password') || $errors->has('rate_limit'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
        modal.show();
    });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('modal_password');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let feedback = [];
            
            if (password.length >= 8) strength += 20;
            else feedback.push('Minimal 8 karakter');
            
            if (/[a-z]/.test(password)) strength += 20;
            else feedback.push('Huruf kecil');
            
            if (/[A-Z]/.test(password)) strength += 20;
            else feedback.push('Huruf besar');
            
            if (/\d/.test(password)) strength += 20;
            else feedback.push('Angka');
            
            if (/[@$!%*?&]/.test(password)) strength += 20;
            else feedback.push('Simbol');
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 40) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Lemah - Perlu: ' + feedback.join(', ');
                strengthText.className = 'text-danger';
            } else if (strength < 80) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Sedang - Perlu: ' + feedback.join(', ');
                strengthText.className = 'text-warning';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Kuat - Password aman';
                strengthText.className = 'text-success';
            }
        });
    }
});
</script>

@endsection