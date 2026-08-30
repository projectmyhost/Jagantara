<?php
$categoryIcons = [
    'sampah'            => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>',
    'sampah-menumpuk'   => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>',
    'lingkungan-kotor'  => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
    'pencemaran'        => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>',
    'kerja-bakti'       => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
    'drainase'          => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>',
    'pohon-dan-tanaman' => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
    'fasilitas-umum'    => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
    'air-dan-sungai'    => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
];
?>

<section class="hero-section" id="hero-section">
    <div class="hero-slider-wrap">

    <?php if (!empty($banners)): ?>
        <?php foreach ($banners as $index => $banner): ?>
            <div class="hero-card <?= $index === 0 ? 'active' : '' ?>" data-slide="<?= $index ?>">

                <div class="hero-media-backdrop">
                    <?php if (!empty($banner['image_path']) && file_exists(UPLOAD_PATH . '/' . $banner['image_path'])): ?>
                        <img src="<?= uploadUrl($banner['image_path']) ?>"
                             alt="<?= e($banner['title']) ?>"
                             class="hero-bg-img"
                             width="1920"
                             height="1080"
                             decoding="async"
                             <?= $index === 0 ? 'loading="eager" fetchpriority="high"' : 'loading="lazy"' ?>>
                    <?php else: ?>
                        <div class="hero-fallback-bg"></div>
                    <?php endif; ?>
                    <div class="hero-text-scrim" aria-hidden="true"></div>
                </div>

                <div class="hero-content">
                    <div class="container">
                        <div class="hero-grid">

                            <div class="hero-content-inner">
                                <div class="hero-tagline">
                                    <span class="hero-tagline-beacon" aria-hidden="true">
                                        <span class="beacon-pulse"></span>
                                        <span class="beacon-dot"></span>
                                    </span>
                                    <span>Platform Aksi Lingkungan &bull; JABODETABEK</span>
                                </div>

                                <h1 class="hero-title"><?= e($banner['title']) ?></h1>

                                <p class="hero-subtitle">
                                    <?= !empty($banner['subtitle']) ? e($banner['subtitle']) : 'Bersama kita jaga lingkungan JABODETABEK. Laporkan masalah di sekitar Anda sekarang.' ?>
                                </p>

                                <div class="hero-cta-section">
                                    <?php if (isLoggedIn()): ?>
                                        <a href="<?= url('reports/create') ?>" class="cta-btn-primary">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            <span>Laporkan Masalah Lingkungan</span>
                                        </a>
                                    <?php else: ?>
                                        <button type="button" onclick="openGuestAuthModal('lapor')" class="cta-btn-primary">
                                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            <span>Laporkan Masalah Lingkungan</span>
                                        </button>
                                    <?php endif; ?>

                                    <?php if (!empty($banner['link_url'])): ?>
                                        <a href="<?= e($banner['link_url']) ?>" class="cta-btn-secondary">
                                            <span>Jelajahi Laporan</span>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= url('reports') ?>" class="cta-btn-secondary">
                                            <span>Jelajahi Laporan</span>
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <div class="hero-trust-row">
                                    <div class="trust-item">
                                        <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Terbuka & Terverifikasi</span>
                                    </div>
                                    <div class="trust-divider">&bull;</div>
                                    <div class="trust-item">
                                        <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span>5 Wilayah JABODETABEK</span>
                                    </div>
                                </div>
                            </div>

                            <div class="hero-pulse-card-wrap">
                                <div class="hero-pulse-card">
                                    <div class="pulse-card-header">
                                        <div class="pulse-status-badge">
                                            <span class="status-indicator"></span>
                                            <span>Gerakan Aktif</span>
                                        </div>
                                        <span class="pulse-time-tag">Real-time Data</span>
                                    </div>

                                    <div class="pulse-card-body">
                                        <div class="pulse-stat-box">
                                            <div class="pulse-stat-number"><?= number_format($stats['resolved_reports'] ?? 0) ?></div>
                                            <div class="pulse-stat-label">Masalah Lingkungan Tertangani</div>
                                        </div>
                                        <p class="pulse-desc">Warga dan komunitas organizer bergerak bersama mewujudkan lingkungan yang lebih sehat dan asri.</p>
                                    </div>

                                    <div class="pulse-card-footer">
                                        <a href="<?= url('map') ?>" class="pulse-quick-link">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                </svg>
                                                <span>Pantau Peta Titik Masalah</span>
                                            </div>
                                            <svg class="w-4 h-4 pulse-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        <?php endforeach; ?>

        <?php if (count($banners) > 1): ?>
            <div class="hero-controls-modern">
                <button type="button" class="hero-nav-arrow-modern prev" onclick="prevSlide()" aria-label="Slide sebelumnya">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <div class="hero-dots-modern">
                    <?php foreach ($banners as $i => $b): ?>
                        <button type="button"
                                class="hero-dot-modern <?= $i === 0 ? 'active' : '' ?>"
                                onclick="goToSlide(<?= $i ?>)"
                                aria-label="Pindah ke slide <?= $i + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="hero-nav-arrow-modern next" onclick="nextSlide()" aria-label="Slide selanjutnya">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        <?php endif; ?>

    <?php else: ?>

        <div class="hero-card active">
            <div class="hero-media-backdrop">
                <div class="hero-fallback-bg"></div>
                <div class="hero-text-scrim" aria-hidden="true"></div>
            </div>

            <div class="hero-content">
                <div class="container">
                    <div class="hero-grid">
                        <div class="hero-content-inner">
                            <div class="hero-tagline">
                                <span class="hero-tagline-beacon" aria-hidden="true">
                                    <span class="beacon-pulse"></span>
                                    <span class="beacon-dot"></span>
                                </span>
                                <span>Platform Aksi Lingkungan &bull; JABODETABEK</span>
                            </div>
                            <h1 class="hero-title">Laporkan Masalah Lingkungan</h1>
                            <p class="hero-subtitle">Bersama kita jaga lingkungan JABODETABEK. Laporkan masalah di sekitar Anda sekarang.</p>

                            <div class="hero-cta-section">
                                <?php if (isLoggedIn()): ?>
                                    <a href="<?= url('reports/create') ?>" class="cta-btn-primary">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>Laporkan Masalah Lingkungan</span>
                                    </a>
                                <?php else: ?>
                                    <button type="button" onclick="openGuestAuthModal('lapor')" class="cta-btn-primary">
                                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        <span>Laporkan Masalah Lingkungan</span>
                                    </button>
                                <?php endif; ?>

                                <a href="<?= url('reports') ?>" class="cta-btn-secondary">
                                    <span>Jelajahi Laporan</span>
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>

                            <div class="hero-trust-row">
                                <div class="trust-item">
                                    <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Terbuka & Terverifikasi</span>
                                </div>
                                <div class="trust-divider">&bull;</div>
                                <div class="trust-item">
                                    <svg class="trust-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    <span>5 Wilayah JABODETABEK</span>
                                </div>
                            </div>
                        </div>

                        <div class="hero-pulse-card-wrap">
                            <div class="hero-pulse-card">
                                <div class="pulse-card-header">
                                    <div class="pulse-status-badge">
                                        <span class="status-indicator"></span>
                                        <span>Gerakan Aktif</span>
                                    </div>
                                    <span class="pulse-time-tag">Real-time Data</span>
                                </div>
                                <div class="pulse-card-body">
                                    <div class="pulse-stat-box">
                                        <div class="pulse-stat-number"><?= number_format($stats['resolved_reports'] ?? 0) ?></div>
                                        <div class="pulse-stat-label">Masalah Lingkungan Tertangani</div>
                                    </div>
                                    <p class="pulse-desc">Warga dan komunitas organizer bergerak bersama mewujudkan lingkungan yang lebih sehat dan asri.</p>
                                </div>
                                <div class="pulse-card-footer">
                                    <a href="<?= url('map') ?>" class="pulse-quick-link">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-[#F9D2BA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                            </svg>
                                            <span>Pantau Peta Titik Masalah</span>
                                        </div>
                                        <svg class="w-4 h-4 pulse-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    </div>
</section>

<div class="container relative z-20 -mt-10 sm:-mt-14 mb-8 sm:mb-12">
    <div class="bg-white rounded-2xl sm:rounded-3xl border border-gray-200/80 shadow-xl shadow-black/5 p-5 sm:p-7">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">

            <div class="pt-3 sm:pt-0 sm:pr-4 flex items-center justify-between">
                <div class="min-w-0 pr-2">
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-gray-900 leading-none">
                        <?= number_format($stats['total_reports'] ?? 0) ?>
                    </div>
                    <div class="text-xs sm:text-sm font-bold text-gray-800 mt-1.5 truncate">Laporan Masuk</div>
                    <div class="text-[11px] text-gray-400 font-medium hidden sm:block mt-0.5">Permasalahan warga</div>
                </div>
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-100/70 text-emerald-700 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>

            <div class="pt-4 sm:pt-0 sm:px-4 flex items-center justify-between">
                <div class="min-w-0 pr-2">
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-gray-900 leading-none">
                        <?= number_format($stats['resolved_reports'] ?? 0) ?>
                    </div>
                    <div class="text-xs sm:text-sm font-bold text-gray-800 mt-1.5 truncate">Masalah Ditangani</div>
                    <div class="text-[11px] text-gray-400 font-medium hidden sm:block mt-0.5">Selesai diverifikasi</div>
                </div>
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-amber-100/70 text-amber-700 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>

            <div class="pt-4 sm:pt-0 sm:px-4 flex items-center justify-between">
                <div class="min-w-0 pr-2">
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-gray-900 leading-none">
                        <?= number_format($stats['total_activities'] ?? 0) ?>
                    </div>
                    <div class="text-xs sm:text-sm font-bold text-gray-800 mt-1.5 truncate">Kegiatan Aksi</div>
                    <div class="text-[11px] text-gray-400 font-medium hidden sm:block mt-0.5">Aksi gotong royong</div>
                </div>
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-blue-100/70 text-blue-700 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <div class="pt-4 sm:pt-0 sm:pl-4 flex items-center justify-between">
                <div class="min-w-0 pr-2">
                    <div class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight text-gray-900 leading-none">
                        <?= number_format($stats['total_users'] ?? 0) ?>
                    </div>
                    <div class="text-xs sm:text-sm font-bold text-gray-800 mt-1.5 truncate">Warga Terdaftar</div>
                    <div class="text-[11px] text-gray-400 font-medium hidden sm:block mt-0.5">Komunitas peduli</div>
                </div>
                <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-purple-100/70 text-purple-700 flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>

        </div>
    </div>
</div>

<section class="py-6 sm:py-8 bg-white border-y border-gray-200/80 mb-8 sm:mb-12">
    <div class="container">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4 sm:mb-5">
            <div class="flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full" style="background: var(--color-primary);" aria-hidden="true"></span>
                <h2 class="text-xs sm:text-sm font-extrabold uppercase tracking-wider text-gray-900">Kategori Laporan Lingkungan</h2>
                <span class="hidden sm:inline text-xs text-gray-400 font-medium">&bull; Pilih topik untuk melihat laporan</span>
            </div>
            <a href="<?= url('reports') ?>" class="inline-flex items-center gap-1.5 text-xs font-bold transition group self-start sm:self-auto hover:opacity-80" style="color: var(--color-primary);">
                <span>Lihat Semua Kategori (<?= count($categories) ?>)</span>
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <?php
        $featuredCategories = array_slice($categories, 0, 6);
        ?>
        <div class="category-clean-wrap">
            <a href="<?= url('reports') ?>" class="category-chip active">
                <span class="chip-icon">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                </span>
                <span>Semua Kategori</span>
            </a>

            <?php foreach ($featuredCategories as $cat): ?>
                <?php
                $catSlug = $cat['slug'] ?? '';
                $catIcon = $categoryIcons[$catSlug] ?? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>';
                ?>
                <a href="<?= url('reports?category=' . urlencode($cat['id'])) ?>" class="category-chip">
                    <span class="chip-icon">
                        <?= $catIcon ?>
                    </span>
                    <span><?= e($cat['name']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Laporan Terbaru Warga</h2>
                <p class="section-subtitle">Pantau permasalahan lingkungan yang dilaporkan warga secara real-time.</p>
            </div>
            <a href="<?= url('reports') ?>" class="btn btn-outline btn-sm">
                Lihat Semua
            </a>
        </div>

        <?php
        $reportsList = $recentReports ?? $latestReports ?? [];
        ?>
        <?php if (!empty($reportsList)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <?php foreach ($reportsList as $report): ?>
                    <?php
                    $photo = $report['primary_photo'] ?? $report['first_photo'] ?? $report['image_path'] ?? null;
                    ?>
                    <a href="<?= url('reports/' . $report['id']) ?>" class="card card-hover group flex flex-col">
                        <div class="card-img-wrap">
                            <?php if (!empty($photo) && file_exists(UPLOAD_PATH . '/' . $photo)): ?>
                                <img src="<?= uploadUrl($photo) ?>"
                                     alt="<?= e($report['title']) ?>"
                                     class="card-img"
                                     width="400"
                                     height="300"
                                     loading="lazy"
                                     decoding="async">
                            <?php else: ?>
                                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>

                            <div class="absolute top-2.5 left-2.5">
                                <span class="badge badge-<?= e($report['status']) ?>">
                                    <?= reportStatusLabel($report['status']) ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="flex items-center gap-2 mb-1.5 text-xs text-gray-600 font-medium">
                                <span class="font-semibold" style="color: var(--color-primary);"><?= e($report['category_name'] ?? 'Umum') ?></span>
                                <span>&bull;</span>
                                <span><?= e($report['region_name'] ?? 'Jabodetabek') ?></span>
                            </div>

                            <h3 class="card-title text-gray-900 group-hover:opacity-80 transition line-clamp-2">
                                <?= e($report['title']) ?>
                            </h3>

                            <div class="card-meta mt-2 pt-2 border-t border-gray-100 text-gray-600">
                                <span><?= formatDate($report['created_at'], 'relative') ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state bg-white rounded-2xl border border-gray-200 p-12">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-base font-bold text-gray-800 mb-1">Belum Ada Laporan</h3>
                <p class="text-xs text-gray-600 max-w-sm mx-auto mb-4">Jadilah yang pertama melaporkan permasalahan lingkungan di sekitar Anda.</p>
                <?php if (isLoggedIn()): ?>
                    <a href="<?= url('reports/create') ?>" class="btn btn-primary btn-sm">Buat Laporan</a>
                <?php else: ?>
                    <button type="button" onclick="openGuestAuthModal('lapor')" class="btn btn-primary btn-sm">Buat Laporan</button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="section bg-[#FAFAF8] border-t border-b border-gray-200">
    <div class="container">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-widest" style="color: var(--color-secondary);">Alur Kerja Sistem</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 mt-1">Dari Laporan Warga Menjadi Aksi Nyata</h2>
            <p class="text-sm text-gray-600 mt-2">Menghubungkan masyarakat dengan organizer dan relawan untuk solusi pembersihan yang tuntas dan terkoordinasi.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center relative shadow-sm">
                <div class="w-12 h-12 rounded-xl font-bold text-lg flex items-center justify-center mx-auto mb-4" style="background: var(--color-accent1); color: var(--color-primary);">
                    1
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Buat Laporan</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Warga mengunggah bukti foto permasalahan lingkungan, lokasi alamat, dan deskripsi detail.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center relative shadow-sm">
                <div class="w-12 h-12 rounded-xl font-bold text-lg flex items-center justify-center mx-auto mb-4" style="background: var(--color-accent1); color: var(--color-primary);">
                    2
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Verifikasi Admin</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Admin memverifikasi keabsahan laporan untuk memastikan data valid dan bebas spam.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center relative shadow-sm">
                <div class="w-12 h-12 rounded-xl font-bold text-lg flex items-center justify-center mx-auto mb-4" style="background: var(--color-accent1); color: var(--color-primary);">
                    3
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Organizer Menindaklanjuti</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Komunitas organizer merencanakan aksi, jadwal, dan kebutuhan kegiatan kerja bakti.
                </p>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-gray-200 text-center relative shadow-sm">
                <div class="w-12 h-12 rounded-xl font-bold text-lg flex items-center justify-center mx-auto mb-4" style="background: var(--color-accent1); color: var(--color-primary);">
                    4
                </div>
                <h3 class="font-bold text-gray-900 mb-2">Aksi & Masalah Tuntas</h3>
                <p class="text-xs text-gray-600 leading-relaxed">
                    Relawan dan anggota berpartisipasi dalam pembersihan hingga laporan berstatus selesai.
                </p>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($upcomingActivities)): ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <div>
                <h2 class="section-title">Kegiatan & Kerja Bakti Mendatang</h2>
                <p class="section-subtitle">Bergabunglah dalam kegiatan pembersihan dan aksi lingkungan bersama organizer terdekat.</p>
            </div>
            <a href="<?= url('activities') ?>" class="btn btn-outline btn-sm">
                Lihat Semua
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($upcomingActivities as $act): ?>
                <div class="card p-5 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                            <span class="px-2 py-0.5 rounded font-semibold text-xs" style="background: var(--color-accent1); color: var(--color-primary);">
                                <?= e($act['organizer_name']) ?>
                            </span>
                            <span><?= e($act['region_name']) ?></span>
                        </div>
                        <h3 class="font-bold text-gray-900 text-base mb-2">
                            <a href="<?= url('activities/' . $act['id']) ?>" class="hover:opacity-80" style="color: var(--color-text);">
                                <?= e($act['title']) ?>
                            </a>
                        </h3>
                        <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                            <?= e(truncate($act['description'], 120)) ?>
                        </p>
                    </div>

                    <div class="pt-3 border-t border-gray-100 flex items-center justify-between">
                        <div class="text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?= formatDate($act['scheduled_at'] ?? date('Y-m-d'), 'd M Y H:i') ?>
                        </div>
                        <a href="<?= url('activities/' . $act['id']) ?>" class="btn btn-primary btn-sm text-xs">
                            Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="text-white py-14" style="background: var(--color-primary);">
    <div class="container text-center max-w-2xl mx-auto">
        <h2 class="text-2xl sm:text-3xl font-extrabold mb-3">Siap Menjaga Kebersihan Lingkungan?</h2>
        <p class="text-sm text-gray-200 mb-6 leading-relaxed">
            Bergabunglah dengan ribuan warga JABODETABEK lainnya. Setiap laporan yang Anda buat adalah langkah awal bagi lingkungan yang lebih sehat.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3">
            <?php if (isLoggedIn()): ?>
                <a href="<?= url('reports/create') ?>" class="btn font-bold shadow-md" style="background: var(--color-accent2); color: var(--color-primary-dark);">
                    Buat Laporan Baru
                </a>
            <?php else: ?>
                <a href="<?= url('auth/register') ?>" class="btn font-bold shadow-md" style="background: var(--color-accent2); color: var(--color-primary-dark);">
                    Daftar Sekarang
                </a>
                <a href="<?= url('auth/login') ?>" class="btn btn-outline-white">
                    Masuk ke Akun
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<script>
let currentSlideIndex = 0;
let slideInterval = null;
let slides = [];
let dots = [];

const BANNER_AUTOPLAY_DELAY = <?= max(1000, (int)setting('banner_autoplay_delay', '5000')) ?>;

function showSlide(index) {
    if (!slides.length) return;
    
    currentSlideIndex = ((index % slides.length) + slides.length) % slides.length;

    for (let i = 0; i < slides.length; i++) {
        if (i === currentSlideIndex) {
            slides[i].classList.add('active');
        } else {
            slides[i].classList.remove('active');
        }
    }

    for (let i = 0; i < dots.length; i++) {
        if (i === currentSlideIndex) {
            dots[i].classList.add('active');
        } else {
            dots[i].classList.remove('active');
        }
    }
}

function startAutoplay() {
    if (slideInterval) clearInterval(slideInterval);
    if (slides.length > 1) {
        slideInterval = setInterval(() => {
            showSlide(currentSlideIndex + 1);
        }, BANNER_AUTOPLAY_DELAY);
    }
}

function nextSlide() {
    showSlide(currentSlideIndex + 1);
    startAutoplay();
}

function prevSlide() {
    showSlide(currentSlideIndex - 1);
    startAutoplay();
}

function goToSlide(index) {
    showSlide(index);
    startAutoplay();
}

function initHeroSlider() {
    slides = Array.from(document.querySelectorAll('.hero-card'));
    dots   = Array.from(document.querySelectorAll('.hero-dot-modern, .hero-dot'));

    if (!slides.length) return;

    showSlide(0);

    if (slides.length > 1) {
        startAutoplay();

        const sliderWrap = document.querySelector('.hero-slider-wrap');
        if (sliderWrap) {
            let touchStartX = 0;
            let touchEndX   = 0;
            sliderWrap.addEventListener('touchstart', e => {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            sliderWrap.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchStartX - touchEndX;
                if (Math.abs(diff) > 45) {
                    diff > 0 ? nextSlide() : prevSlide();
                }
            }, { passive: true });
        }
    }
}

document.addEventListener('DOMContentLoaded', initHeroSlider);

document.addEventListener('visibilitychange', () => {
    if (!document.hidden && slides.length > 1) {
        startAutoplay();
    }
});

if (document.readyState === 'complete' || document.readyState === 'interactive') {
    initHeroSlider();
}
</script>
