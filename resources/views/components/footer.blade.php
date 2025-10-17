<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <h5 class="d-flex align-items-center">
                    <i class="bi bi-mortarboard-fill me-2"></i>
                    {{ site_name() }}
                </h5>
                <p class="mb-3">Membentuk generasi santri yang berakhlak mulia, cerdas, dan siap menghadapi tantangan zaman dengan tetap berpegang teguh pada nilai-nilai Islam Ahlussunnah Wal Jamaah.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none">
                        <i class="bi bi-facebook fs-5"></i>
                    </a>
                    <a href="#" class="text-decoration-none">
                        <i class="bi bi-instagram fs-5"></i>
                    </a>
                    <a href="#" class="text-decoration-none">
                        <i class="bi bi-youtube fs-5"></i>
                    </a>
                    <a href="#" class="text-decoration-none">
                        <i class="bi bi-envelope fs-5"></i>
                    </a>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-white mb-3">Menu Utama</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('beranda') }}" class="text-decoration-none">Beranda</a></li>
                    <li class="mb-2"><a href="{{ route('tentang') }}" class="text-decoration-none">Tentang Kami</a></li>
                    <li class="mb-2"><a href="{{ route('kegiatan') }}" class="text-decoration-none">Kegiatan</a></li>
                    <li class="mb-2"><a href="{{ route('organisasi') }}" class="text-decoration-none">Organisasi</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="text-white mb-3">Informasi</h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('blog') }}" class="text-decoration-none">Blog</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none">PPDB</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none">Galeri</a></li>
                    <li class="mb-2"><a href="{{ route('kontak') }}" class="text-decoration-none">Kontak</a></li>
                </ul>
            </div>
            
            <div class="col-lg-4 mb-4">
                <h6 class="text-white mb-3">Kontak Kami</h6>
                <div class="d-flex align-items-start mb-2">
                    <i class="bi bi-geo-alt-fill me-2 mt-1"></i>
                    <span>Jl. Pendidikan No. 123, Kota Nusantara, Indonesia 12345</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-telephone-fill me-2"></i>
                    <span>(021) 1234-5678</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-envelope-fill me-2"></i>
                    <span>info@manunusantara.sch.id</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-clock-fill me-2"></i>
                    <span>Senin - Jumat: 07:00 - 16:00</span>
                </div>
            </div>
        </div>
        
        <hr class="my-4" style="border-color: #374151;">
        
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0">&copy; {{ date('Y') }} Madrasah Aliyah NU Nusantara. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <small class="text-muted">Membentuk Generasi Santri Berakhlak Mulia</small>
            </div>
        </div>
    </div>
</footer>