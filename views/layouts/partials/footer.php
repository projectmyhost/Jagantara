
<footer class="footer footer-desktop">
    <div class="container">
        <div class="footer-grid">

            <div>
                <div class="footer-brand flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-white text-lg font-bold"><?= APP_NAME ?></span>
                </div>
                <p class="text-sm text-gray-200 max-w-sm mb-4 leading-relaxed">
                    Platform pelaporan dan aksi lingkungan berbasis masyarakat untuk wilayah JABODETABEK. Menghubungkan kepedulian warga dengan aksi nyata pembersihan.
                </p>
                <div class="text-xs text-emerald-200 font-medium">
                    Fokus Wilayah: Jakarta, Bogor, Depok, Tangerang, Bekasi
                </div>
            </div>

            <div>
                <h3 class="text-white text-sm font-bold uppercase tracking-wider mb-3">Menu Utama</h3>
                <a href="<?= url('') ?>">Beranda</a>
                <a href="<?= url('reports') ?>">Daftar Laporan</a>
                <a href="<?= url('map') ?>">Peta Sebaran</a>
                <a href="<?= url('activities') ?>">Kegiatan Lingkungan</a>
                <a href="<?= url('organizers') ?>">Organizer Komunitas</a>
                <a href="<?= url('forum') ?>">Forum Diskusi</a>
            </div>

            <div>
                <h3 class="text-white text-sm font-bold uppercase tracking-wider mb-3">Partisipasi</h3>
                <?php if (isLoggedIn()): ?>
                    <a href="<?= url('reports/create') ?>">Buat Laporan Baru</a>
                    <a href="<?= url('reports/status') ?>">Status Laporan Saya</a>
                    <a href="<?= url('profile') ?>">Pengaturan Profil</a>
                <?php else: ?>
                    <a href="<?= url('auth/login') ?>">Masuk Akun</a>
                    <a href="<?= url('auth/register') ?>">Daftar Anggota</a>
                <?php endif; ?>
                <div class="mt-4 pt-4 border-t border-white/10 text-xs text-gray-300">
                    <div>Kontak: <?= e(setting('site_contact_email', 'info@jagantara.id')) ?></div>
                </div>
            </div>

        </div>

        <hr class="footer-divider">

        <div class="footer-bottom flex flex-col sm:flex-row items-center justify-between gap-2 text-gray-200">
            <div>
                &copy; <?= date('Y') ?> <?= APP_NAME ?>. Hak cipta dilindungi undang-undang.
            </div>
            <div class="text-xs text-gray-300">
                Platform Pelaporan & Aksi Lingkungan Berbasis Masyarakat
            </div>
        </div>
    </div>
</footer>

<footer class="footer footer-mobile">
    <div class="footer-mobile-container">

        <div class="footer-mobile-brand">
            <div class="flex items-center justify-center gap-2 mb-2">
                <div class="w-6 h-6 rounded-lg bg-white/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-white text-base font-bold"><?= APP_NAME ?></span>
            </div>
            <p class="text-xs text-gray-200 text-center leading-snug">
                Platform Pelaporan & Aksi Lingkungan
            </p>
        </div>

        <div class="footer-mobile-links">
            <a href="<?= url('') ?>">Beranda</a>
            <span class="footer-mobile-separator" aria-hidden="true">•</span>
            <a href="<?= url('map') ?>">Peta</a>
            <span class="footer-mobile-separator" aria-hidden="true">•</span>
            <a href="<?= url('organizers') ?>">Gabung</a>
        </div>

        <div class="footer-mobile-contact text-gray-300">
            Kontak: <?= e(setting('site_contact_email', 'info@jagantara.id')) ?>
        </div>

        <div class="footer-mobile-copyright text-gray-300">
            &copy; <?= date('Y') ?> <?= APP_NAME ?>
        </div>

    </div>
</footer>
