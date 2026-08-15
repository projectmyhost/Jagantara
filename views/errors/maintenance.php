<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemeliharaan Sistem — <?= e(setting('site_name', APP_NAME)) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #F8FAFC;
            color: #1E293B;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
            position: relative;
        }

        .bg-pattern {
            position: fixed;
            inset: 0;
            background-image: radial-gradient(#E2E8F0 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.6;
            pointer-events: none;
            z-index: 0;
        }

        .container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 480px;
        }

        .card {
            background: #FFFFFF;
            border: 1px solid #E2E8F0;
            border-radius: 20px;
            padding: 36px 28px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
            text-align: center;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: #1D4533;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFFFFF;
        }

        .brand-name {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.3px;
            color: #1D4533;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: #FEF3C7;
            border: 1px solid #FDE68A;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 700;
            color: #92400E;
            margin-bottom: 18px;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #D97706;
            box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7);
            animation: pulse-glow 2s infinite cubic-bezier(0.66, 0, 0, 1);
        }

        @keyframes pulse-glow {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(217, 119, 6, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(217, 119, 6, 0); }
        }

        h1 {
            font-size: 22px;
            font-weight: 800;
            color: #0F172A;
            line-height: 1.3;
            margin-bottom: 10px;
        }

        p.description {
            font-size: 13.5px;
            color: #64748B;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .info-box {
            background: #F8FAFC;
            border: 1px solid #EDF2F7;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 24px;
            text-align: left;
        }

        .info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13px;
            padding: 7px 0;
        }

        .info-row:not(:last-child) {
            border-bottom: 1px solid #E2E8F0;
        }

        .info-label {
            color: #64748B;
            font-weight: 500;
        }

        .info-value {
            color: #0F172A;
            font-weight: 600;
        }

        .info-value a {
            color: #1D4533;
            text-decoration: none;
            font-weight: 600;
        }

        .info-value a:hover {
            text-decoration: underline;
        }

        .btn-refresh {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 12px 20px;
            background: #1D4533;
            color: #FFFFFF;
            border: none;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s ease, transform 0.1s ease;
            box-shadow: 0 4px 10px rgba(29, 69, 51, 0.2);
        }

        .btn-refresh:hover {
            background: #14301F;
            transform: translateY(-1px);
        }

        .btn-refresh:active {
            transform: translateY(0);
        }

        .footer-note {
            margin-top: 20px;
            font-size: 11px;
            color: #94A3B8;
        }

        @media (max-width: 480px) {
            .card {
                padding: 28px 20px;
                border-radius: 16px;
            }
            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>

    <main class="container">
        <div class="card">

            <div class="brand">
                <div class="brand-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="brand-name"><?= e(setting('site_name', APP_NAME)) ?></span>
            </div>

            <div>
                <div class="status-badge">
                    <span class="pulse-dot"></span>
                    <span>Mode Pemeliharaan Sistem</span>
                </div>
            </div>

            <h1>Platform Sedang Ditingkatkan</h1>
            <p class="description">
                Kami sedang melakukan pemeliharaan server berkala dan peningkatan performa. Layanan pelaporan dan aksi lingkungan akan segera kembali aktif.
            </p>

            <div class="info-box">
                <div class="info-row">
                    <span class="info-label">Status Akses</span>
                    <span class="info-value" style="color: #D97706;">Ditutup Sementara</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Bantuan / Email</span>
                    <span class="info-value">
                        <a href="mailto:<?= e(setting('site_contact_email', 'info@jagantara.id')) ?>">
                            <?= e(setting('site_contact_email', 'info@jagantara.id')) ?>
                        </a>
                    </span>
                </div>
                <?php if (!empty(setting('site_contact_phone'))): ?>
                    <div class="info-row">
                        <span class="info-label">Kontak Layanan</span>
                        <span class="info-value"><?= e(setting('site_contact_phone')) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <button type="button" class="btn-refresh" onclick="window.location.reload()">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Muat Ulang Halaman
            </button>

            <div class="footer-note">
                &copy; <?= date('Y') ?> <?= e(setting('site_name', APP_NAME)) ?> &bull; Platform Aksi Lingkungan
            </div>

        </div>
    </main>
</body>
</html>
