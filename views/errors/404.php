<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= url('public/css/app.css') ?>">
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full text-center bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-gray-200">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 text-amber-700 mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">404</h1>
        <p class="text-lg font-medium text-gray-800 mb-2">Halaman Tidak Ditemukan</p>
        <p class="text-sm text-gray-500 mb-6">Halaman yang Anda tuju mungkin telah dipindahkan, dihapus, atau tautan yang dimasukkan salah.</p>
        <a href="<?= url('') ?>" class="btn btn-primary inline-flex">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
