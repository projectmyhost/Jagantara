<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Alert & Confirm Demo - Jagantara</title>
    <link rel="stylesheet" href="<?= url('public/css/app.css') ?>">
    <style>
        body {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            font-family: 'Inter', sans-serif;
        }
        .demo-section {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .demo-section h2 {
            margin-top: 0;
            color: 
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        .demo-section p {
            color: 
            margin-bottom: 1.5rem;
        }
        .demo-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        .demo-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            font-size: 0.875rem;
        }
        .demo-btn:hover {
            transform: translateY(-2px);
        }
        .demo-btn-info {
            background: 
            color: white;
        }
        .demo-btn-success {
            background: 
            color: white;
        }
        .demo-btn-warning {
            background: 
            color: white;
        }
        .demo-btn-error {
            background: 
            color: white;
        }
        .demo-btn-confirm {
            background: 
            color: white;
        }
        .demo-btn-danger {
            background: 
            color: white;
        }
        .demo-result {
            margin-top: 1rem;
            padding: 1rem;
            background: 
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.875rem;
            min-height: 60px;
            color: 
        }
        .page-title {
            text-align: center;
            color: 
            margin-bottom: 2rem;
        }
        .page-subtitle {
            text-align: center;
            color: 
            margin-bottom: 3rem;
        }
        code {
            background: 
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.875rem;
            color: 
        }
    </style>
</head>
<body>
    <h1 class="page-title">Custom Alert & Confirm Modal Demo</h1>
    <p class="page-subtitle">Test semua tipe alert dan confirm modal yang estetik</p>

    <div class="demo-section">
        <h2>Custom Alert</h2>
        <p>Menampilkan berbagai tipe alert dengan icon dan warna berbeda</p>
        <div class="demo-buttons">
            <button class="demo-btn demo-btn-info" onclick="testAlertInfo()">Info Alert</button>
            <button class="demo-btn demo-btn-success" onclick="testAlertSuccess()">Success Alert</button>
            <button class="demo-btn demo-btn-warning" onclick="testAlertWarning()">Warning Alert</button>
            <button class="demo-btn demo-btn-error" onclick="testAlertError()">Error Alert</button>
        </div>
        <div id="alert-result" class="demo-result">Klik salah satu button di atas untuk test alert...</div>
    </div>

    <div class="demo-section">
        <h2>Helper Functions</h2>
        <p>Menggunakan helper functions: <code>alertSuccess()</code>, <code>alertError()</code>, <code>alertWarning()</code></p>
        <div class="demo-buttons">
            <button class="demo-btn demo-btn-success" onclick="testHelperSuccess()">alertSuccess()</button>
            <button class="demo-btn demo-btn-error" onclick="testHelperError()">alertError()</button>
            <button class="demo-btn demo-btn-warning" onclick="testHelperWarning()">alertWarning()</button>
        </div>
        <div id="helper-result" class="demo-result">Klik salah satu button di atas untuk test helper...</div>
    </div>

    <div class="demo-section">
        <h2>Custom Confirm</h2>
        <p>Konfirmasi dengan 2 button: Ya dan Batal (return boolean)</p>
        <div class="demo-buttons">
            <button class="demo-btn demo-btn-confirm" onclick="testConfirmBasic()">Basic Confirm</button>
            <button class="demo-btn demo-btn-confirm" onclick="testConfirmCustom()">Custom Text Confirm</button>
            <button class="demo-btn demo-btn-danger" onclick="testConfirmDanger()">Danger Confirm</button>
        </div>
        <div id="confirm-result" class="demo-result">Klik salah satu button di atas untuk test confirm...</div>
    </div>

    <div class="demo-section">
        <h2>Browser Override</h2>
        <p>Browser default <code>alert()</code> dan <code>confirm()</code> sudah di-override otomatis</p>
        <div class="demo-buttons">
            <button class="demo-btn demo-btn-info" onclick="testBrowserAlert()">Browser alert()</button>
            <button class="demo-btn demo-btn-confirm" onclick="testBrowserConfirm()">Browser confirm()</button>
        </div>
        <div id="browser-result" class="demo-result">Klik salah satu button di atas untuk test browser override...</div>
    </div>

    <div class="demo-section">
        <h2>Real World Examples</h2>
        <p>Contoh use case nyata dari aplikasi</p>
        <div class="demo-buttons">
            <button class="demo-btn demo-btn-warning" onclick="testUploadLimit()">Upload Limit</button>
            <button class="demo-btn demo-btn-danger" onclick="testDeleteConfirm()">Delete Item</button>
            <button class="demo-btn demo-btn-error" onclick="testFormValidation()">Form Error</button>
            <button class="demo-btn demo-btn-success" onclick="testSuccessAction()">Success Action</button>
        </div>
        <div id="realworld-result" class="demo-result">Klik salah satu button di atas untuk test real world example...</div>
    </div>

    <div class="demo-section">
        <h2>Data Attribute (data-confirm)</h2>
        <p>Menggunakan attribute <code>data-confirm</code> pada element (auto-handled)</p>
        <div class="demo-buttons">
            <a href="#" class="demo-btn demo-btn-confirm" data-confirm="Lanjutkan ke halaman lain?">
                Link dengan Confirm
            </a>
            <button class="demo-btn demo-btn-danger"
                    data-confirm="Data yang dihapus tidak dapat dikembalikan!"
                    data-confirm-danger
                    data-confirm-title="Hapus Permanent?"
                    data-confirm-yes="Ya, Hapus"
                    data-confirm-no="Batal"
                    onclick="console.log('Deleted!')">
                Delete dengan Danger
            </button>
        </div>
        <div class="demo-result">Element dengan <code>data-confirm</code> akan otomatis menampilkan confirm modal saat diklik</div>
    </div>

    <script src="<?= url('public/js/app.js') ?>"></script>
    <script>

        function testAlertInfo() {
            customAlert('Ini adalah pesan informasi biasa.', 'info', 'Informasi');
            document.getElementById('alert-result').textContent = 'customAlert(message, "info", "Informasi")';
        }

        function testAlertSuccess() {
            customAlert('Data berhasil disimpan ke database!', 'success', 'Berhasil!');
            document.getElementById('alert-result').textContent = 'customAlert(message, "success", "Berhasil!")';
        }

        function testAlertWarning() {
            customAlert('Maksimal 10 foto per laporan. File ke-11 dan seterusnya ditolak.', 'warning', 'Peringatan');
            document.getElementById('alert-result').textContent = 'customAlert(message, "warning", "Peringatan")';
        }

        function testAlertError() {
            customAlert('Terjadi kesalahan saat menghubungi server. Coba lagi nanti.', 'error', 'Error!');
            document.getElementById('alert-result').textContent = 'customAlert(message, "error", "Error!")';
        }

        function testHelperSuccess() {
            alertSuccess('Laporan berhasil dibuat!', 'Sukses');
            document.getElementById('helper-result').textContent = 'alertSuccess("Laporan berhasil dibuat!", "Sukses")';
        }

        function testHelperError() {
            alertError('Email atau password salah!', 'Login Gagal');
            document.getElementById('helper-result').textContent = 'alertError("Email atau password salah!", "Login Gagal")';
        }

        function testHelperWarning() {
            alertWarning('Sesi Anda akan berakhir dalam 5 menit.', 'Peringatan Sesi');
            document.getElementById('helper-result').textContent = 'alertWarning("Sesi Anda akan berakhir...", "Peringatan Sesi")';
        }

        async function testConfirmBasic() {
            const result = await customConfirm('Apakah Anda yakin ingin melanjutkan?');
            document.getElementById('confirm-result').textContent = `Result: ${result} - User ${result ? 'confirmed' : 'cancelled'}`;
        }

        async function testConfirmCustom() {
            const result = await customConfirm('Publikasikan laporan ini sekarang?', {
                title: 'Publikasi Laporan',
                confirmText: 'Publikasikan',
                cancelText: 'Tidak'
            });
            document.getElementById('confirm-result').textContent = `Result: ${result} - Custom text buttons`;
        }

        async function testConfirmDanger() {
            const result = await confirmDanger('Data yang dihapus tidak dapat dikembalikan!', {
                title: 'Hapus Permanent?',
                confirmText: 'Ya, Hapus',
                cancelText: 'Batal'
            });
            document.getElementById('confirm-result').textContent = `Result: ${result} - Danger mode (red button)`;
        }

        function testBrowserAlert() {
            alert('Ini menggunakan browser alert() tapi sudah di-override!');
            document.getElementById('browser-result').textContent = 'alert("message") - Auto override ke custom modal';
        }

        async function testBrowserConfirm() {
            const result = await confirm('Ini menggunakan browser confirm() tapi sudah di-override!');
            document.getElementById('browser-result').textContent = `confirm("message") = ${result} - Auto override`;
        }

        async function testUploadLimit() {
            alertWarning(
                'Maksimal 10 foto per laporan. File ke-11 dan seterusnya ditolak.',
                'Batas Upload Tercapai'
            );
            document.getElementById('realworld-result').textContent = 'Use case: Upload file limit warning';
        }

        async function testDeleteConfirm() {
            const confirmed = await confirmDanger(
                'Data yang dihapus tidak dapat dikembalikan!',
                {
                    title: 'Hapus Laporan?',
                    confirmText: 'Ya, Hapus',
                    cancelText: 'Batal'
                }
            );

            if (confirmed) {
                setTimeout(() => {
                    alertSuccess('Laporan berhasil dihapus!');
                }, 500);
            }
            document.getElementById('realworld-result').textContent = `Use case: Delete confirmation - Result: ${confirmed}`;
        }

        async function testFormValidation() {
            alertError('Format email tidak valid!', 'Validasi Gagal');
            document.getElementById('realworld-result').textContent = 'Use case: Form validation error';
        }

        async function testSuccessAction() {
            alertSuccess('Laporan berhasil dibuat dan sedang menunggu verifikasi admin.', 'Berhasil!');
            document.getElementById('realworld-result').textContent = 'Use case: Success action feedback';
        }
    </script>
</body>
</html>
