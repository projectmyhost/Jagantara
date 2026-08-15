<div class="min-h-[calc(100vh-260px)] flex items-center justify-center py-16 px-4">
    <div class="max-w-md w-full text-center bg-white p-8 sm:p-10 rounded-2xl shadow-sm border border-gray-200">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-100 text-red-600 mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-extrabold text-gray-900 mb-2"><?= e($code ?? 500) ?></h1>
        <p class="text-gray-600 mb-6"><?= e($message ?? 'Terjadi kesalahan sistem.') ?></p>
        <div class="flex items-center justify-center gap-3">
            <a href="<?= url('') ?>" class="btn btn-primary">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
