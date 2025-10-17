# 🚀 Setup GitHub Repository

## Langkah-langkah Upload ke GitHub:

### 1. **Buat Repository Baru**
- Buka [github.com](https://github.com)
- Klik "New repository"
- Nama: `madrasah-aliyah-nusantara`
- Description: `Modern responsive website for Madrasah Aliyah Nusantara`
- Public/Private: **Public**
- Jangan centang "Initialize with README"

### 2. **Upload dari Command Line**
```bash
# Di folder project (ukk)
git init
git add .
git commit -m "🎓 Initial commit - Madrasah Aliyah Nusantara website"
git branch -M main
git remote add origin https://github.com/USERNAME/madrasah-aliyah-nusantara.git
git push -u origin main
```

### 3. **Upload via GitHub Desktop**
- Download GitHub Desktop
- File → Add Local Repository
- Pilih folder `ukk`
- Publish repository

### 4. **Upload via Web (Drag & Drop)**
- Zip folder `ukk` (exclude `vendor`, `node_modules`)
- Drag zip ke GitHub repository page
- Commit changes

## 🌐 Deploy ke Vercel (Opsional)

1. **Connect GitHub**
   - Login ke [vercel.com](https://vercel.com)
   - Import Git Repository
   - Pilih repository yang baru dibuat

2. **Auto Deploy**
   - Setiap push ke GitHub = auto deploy
   - URL: `https://madrasah-aliyah-nusantara.vercel.app`

## 📋 Checklist Sebelum Upload

- [ ] File `.env` sudah di-gitignore
- [ ] Database credentials dihapus
- [ ] README.md sudah lengkap
- [ ] Screenshots ditambahkan
- [ ] License file (opsional)

## 🔗 Share ke Developer

**Repository**: `https://github.com/USERNAME/madrasah-aliyah-nusantara`
**Live Demo**: `https://madrasah-aliyah-nusantara.vercel.app`

Copy link ini dan share ke developer lain!