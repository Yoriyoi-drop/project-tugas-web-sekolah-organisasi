<footer class="footer pt-5 pb-4 mt-auto" style="background: linear-gradient(180deg, var(--ma-navy) 0%, #020617 100%);">
    <div class="container overflow-hidden">
        <div class="row g-4">
            <div class="col-lg-4 mb-4" data-aos="fade-right">
                <h5 class="d-flex align-items-center fw-bold text-white mb-4">
                    <i class="bi bi-mortarboard-fill me-2 text-warning"></i>
                    {{ site_name() }}
                </h5>
                <p class="mb-4 opacity-75">Membentuk generasi santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman dengan tetap berpegang teguh pada nilai-nilai Islam Ahlussunnah Wal Jamaah.</p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2" style="width: 38px; height: 38px;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2" style="width: 38px; height: 38px;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2" style="width: 38px; height: 38px;">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle p-2" style="width: 38px; height: 38px;">
                        <i class="bi bi-tiktok"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <h6 class="text-white fw-bold mb-4">Menu Utama</h6>
                <ul class="list-unstyled">
                    <li class="mb-3"><a href="{{ route('beranda') }}" class="text-decoration-none opacity-75 hover-opacity-100">Beranda</a></li>
                    <li class="mb-3"><a href="{{ route('tentang') }}" class="text-decoration-none opacity-75 hover-opacity-100">Tentang Kami</a></li>
                    <li class="mb-3"><a href="{{ route('kegiatan') }}" class="text-decoration-none opacity-75 hover-opacity-100">Kegiatan</a></li>
                    <li class="mb-3"><a href="{{ route('organisasi') }}" class="text-decoration-none opacity-75 hover-opacity-100">Organisasi</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <h6 class="text-white fw-bold mb-4">Informasi</h6>
                <ul class="list-unstyled">
                    <li class="mb-3"><a href="{{ route('blog') }}" class="text-decoration-none opacity-75 hover-opacity-100">Warta Akademik</a></li>
                    <li class="mb-3"><a href="{{ route('ppdb.index') }}" class="text-decoration-none opacity-75 hover-opacity-100">PPDB 2024/2025</a></li>
                    <li class="mb-3"><a href="#" class="text-decoration-none opacity-75 hover-opacity-100">Galeri Foto</a></li>
                    <li class="mb-3"><a href="{{ route('kontak') }}" class="text-decoration-none opacity-75 hover-opacity-100">Kontak Person</a></li>
                </ul>
            </div>
            
            <div class="col-lg-4 mb-4" data-aos="fade-left">
                <h6 class="text-white fw-bold mb-4">Hubungi Kami</h6>
                <div class="d-flex align-items-start mb-3 opacity-75">
                    <i class="bi bi-geo-alt-fill me-3 text-warning"></i>
                    <span>Jl. Pendidikan No. 123, Kelurahan Nusantara, Jakarta Selatan 12345</span>
                </div>
                <div class="d-flex align-items-center mb-3 opacity-75">
                    <i class="bi bi-telephone-fill me-3 text-warning"></i>
                    <span>(021) 1234-5678</span>
                </div>
                <div class="d-flex align-items-center mb-3 opacity-75">
                    <i class="bi bi-envelope-fill me-3 text-warning"></i>
                    <span>info@manusantara.sch.id</span>
                </div>
                <div class="d-flex align-items-center opacity-75">
                    <i class="bi bi-clock-fill me-3 text-warning"></i>
                    <span>Senin - Jumat: 07:00 - 16:00</span>
                </div>
            </div>
        </div>
        
        <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
        
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0 opacity-50 small">&copy; {{ date('Y') }} {{ site_name() }}. Seluruh hak cipta dilindungi.</p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <small class="opacity-50 text-uppercase fw-bold ls-1">Membentuk Generasi Santri Berakhlak Mulia</small>
            </div>
        </div>
    </div>
</footer>

<style>
.hover-opacity-100:hover { opacity: 1 !important; color: var(--ma-green-light) !important; }
.ls-1 { letter-spacing: 1px; }
</style>