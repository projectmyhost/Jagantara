<?php

?>

<div class="max-w-5xl space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-bold text-gray-900">Kustomisasi Tema Website</h2>
            <p class="text-xs text-gray-500 mt-0.5">Atur palet warna seluruh website. Perubahan aktif secara langsung setelah disimpan.</p>
        </div>
        <form action="<?= url('admin/theme/reset') ?>" method="POST"
              data-confirm="Reset semua warna ke palet standar Jagantara? (Forest Green, Earthy Brown, Soft Cream, Peach Natural)"
              data-confirm-title="Reset Tema ke Default?"
              data-confirm-yes="Ya, Reset"
              data-confirm-no="Batal">
            <?= csrfField() ?>
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all shadow-sm">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset ke Default Jagantara
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-1 h-5 rounded-full bg-[#1D4533]"></div>
            <h3 class="text-sm font-bold text-gray-900">Identitas Brand Jagantara — Palet Standar</h3>
        </div>
        <p class="text-xs text-gray-500 mb-4">Palet harmonis ini adalah warna default. Gunakan sebagai referensi saat kustomisasi agar tampilan tetap konsisten.</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="rounded-lg overflow-hidden border border-gray-100 shadow-sm">
                <div class="h-16 bg-[#1D4533]"></div>
                <div class="p-2.5 bg-white">
                    <div class="text-xs font-bold text-gray-800">Forest Green</div>
                    <div class="text-[11px] font-mono text-gray-500">#1D4533</div>
                    <div class="text-[10px] text-gray-400 mt-0.5">Primary</div>
                </div>
            </div>
            <div class="rounded-lg overflow-hidden border border-gray-100 shadow-sm">
                <div class="h-16 bg-[#5E3122]"></div>
                <div class="p-2.5 bg-white">
                    <div class="text-xs font-bold text-gray-800">Earthy Brown</div>
                    <div class="text-[11px] font-mono text-gray-500">#5E3122</div>
                    <div class="text-[10px] text-gray-400 mt-0.5">Secondary</div>
                </div>
            </div>
            <div class="rounded-lg overflow-hidden border border-gray-100 shadow-sm">
                <div class="h-16 bg-[#F7EAE0]"></div>
                <div class="p-2.5 bg-white">
                    <div class="text-xs font-bold text-gray-800">Soft Cream</div>
                    <div class="text-[11px] font-mono text-gray-500">#F7EAE0</div>
                    <div class="text-[10px] text-gray-400 mt-0.5">Accent 1</div>
                </div>
            </div>
            <div class="rounded-lg overflow-hidden border border-gray-100 shadow-sm">
                <div class="h-16 bg-[#F9D2BA]"></div>
                <div class="p-2.5 bg-white">
                    <div class="text-xs font-bold text-gray-800">Peach Natural</div>
                    <div class="text-[11px] font-mono text-gray-500">#F9D2BA</div>
                    <div class="text-[10px] text-gray-400 mt-0.5">Accent 2</div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
        <div class="flex items-center gap-2 mb-4">
            <div class="w-1 h-5 rounded-full" id="preview-bar" style="background: <?= e($currentTheme['color_primary'] ?? '#1D4533') ?>"></div>
            <h3 class="text-sm font-bold text-gray-900">Live Preview</h3>
            <span class="text-xs text-gray-400">— berubah saat Anda memilih warna</span>
        </div>
        <div id="live-preview" class="rounded-lg border border-gray-100 overflow-hidden">

            <div id="preview-navbar" class="flex items-center justify-between px-4 py-3" style="background: <?= e($currentTheme['color_primary'] ?? '#1D4533') ?>">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded bg-white/20"></div>
                    <span class="text-white text-sm font-bold">Jagantara</span>
                </div>
                <div class="flex gap-2">
                    <div class="h-5 w-16 rounded" style="background: <?= e($currentTheme['color_accent2'] ?? '#F9D2BA') ?>; opacity: 0.8;"></div>
                    <div id="preview-btn" class="h-5 w-16 rounded text-white text-xs flex items-center justify-center font-semibold" style="background: <?= e($currentTheme['color_secondary'] ?? '#5E3122') ?>">Masuk</div>
                </div>
            </div>

            <div id="preview-body" class="p-4" style="background: <?= e($currentTheme['color_accent1'] ?? '#F7EAE0') ?>">
                <div class="flex gap-3 flex-wrap">
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-white text-xs font-semibold" id="preview-tag-primary" style="background: <?= e($currentTheme['color_primary'] ?? '#1D4533') ?>">Kategori Lingkungan</div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold" id="preview-tag-accent" style="background: <?= e($currentTheme['color_accent2'] ?? '#F9D2BA') ?>; color: <?= e($currentTheme['color_secondary'] ?? '#5E3122') ?>">Peach Tag</div>
                    <div class="px-3 py-1.5 rounded-full text-white text-xs font-semibold" style="background: <?= e($currentTheme['color_success'] ?? '#16A34A') ?>">Aktif</div>
                    <div class="px-3 py-1.5 rounded-full text-white text-xs font-semibold" style="background: <?= e($currentTheme['color_danger'] ?? '#DC2626') ?>">Ditolak</div>
                    <div class="px-3 py-1.5 rounded-full text-white text-xs font-semibold" style="background: <?= e($currentTheme['color_warning'] ?? '#D97706') ?>">Pending</div>
                </div>
                <div class="mt-3 h-2 rounded-full" id="preview-divider" style="background: <?= e($currentTheme['color_primary'] ?? '#1D4533') ?>; opacity: 0.15;"></div>
                <div class="mt-2 text-xs" style="color: <?= e($currentTheme['color_text'] ?? '#1A1A1A') ?>">Ini adalah preview teks utama website dengan warna yang dipilih.</div>
                <div class="mt-1 text-xs" style="color: <?= e($currentTheme['color_muted'] ?? '#6B7280') ?>">Dan ini teks sekunder / muted yang lebih redup.</div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <form action="<?= url('admin/theme/update') ?>" method="POST" id="theme-form">
            <?= csrfField() ?>

            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Brand Utama</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5 border-b border-gray-100">
                <?php
                $brandFields = [
                    'color_primary'   => ['label' => 'Primary Color', 'desc' => 'Sidebar, tombol utama, navbar'],
                    'color_secondary' => ['label' => 'Secondary Color', 'desc' => 'Tombol sekunder, aksen coklat'],
                    'color_accent1'   => ['label' => 'Accent 1 (Cream)', 'desc' => 'Background section, card background'],
                    'color_accent2'   => ['label' => 'Accent 2 (Peach)', 'desc' => 'Tag, badge, highlight ringan'],
                ];
                foreach ($brandFields as $key => $info):
                    $val = e($currentTheme[$key] ?? $defaultTheme[$key]);
                ?>
                <div class="color-field" data-field="<?= $key ?>">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5"><?= $info['label'] ?></label>
                    <div class="text-xs text-gray-400 mb-2"><?= $info['desc'] ?></div>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="color" id="<?= $key ?>" name="<?= $key ?>" value="<?= $val ?>"
                                   class="w-12 h-10 rounded-lg cursor-pointer border border-gray-200 p-0.5 shadow-sm"
                                   oninput="syncColorField(this, '<?= $key ?>')">
                        </div>
                        <input type="text" id="<?= $key ?>_text" value="<?= $val ?>"
                               class="flex-1 px-3 py-2 text-xs font-mono rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#1D4533]/30 focus:border-[#1D4533]"
                               maxlength="7" placeholder="#1D4533"
                               oninput="syncTextToColor(this, '<?= $key ?>')">
                        <button type="button" title="Reset ke default Jagantara"
                                class="text-gray-400 hover:text-gray-700 transition-colors"
                                onclick="resetSingleColor('<?= $key ?>', '<?= $defaultTheme[$key] ?>')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Shade Turunan</h3>
                <p class="text-xs text-gray-400 mt-0.5">Digunakan untuk hover state, sidebar dark, dll.</p>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-5 border-b border-gray-100">
                <?php
                $derivedFields = [
                    'color_primary_dark'    => ['label' => 'Primary Dark', 'desc' => 'Sidebar background'],
                    'color_primary_light'   => ['label' => 'Primary Light', 'desc' => 'Hover primary'],
                    'color_secondary_light' => ['label' => 'Secondary Light', 'desc' => 'Hover secondary'],
                ];
                foreach ($derivedFields as $key => $info):
                    $val = e($currentTheme[$key] ?? $defaultTheme[$key]);
                ?>
                <div class="color-field" data-field="<?= $key ?>">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5"><?= $info['label'] ?></label>
                    <div class="text-xs text-gray-400 mb-2"><?= $info['desc'] ?></div>
                    <div class="flex items-center gap-3">
                        <input type="color" id="<?= $key ?>" name="<?= $key ?>" value="<?= $val ?>"
                               class="w-12 h-10 rounded-lg cursor-pointer border border-gray-200 p-0.5 shadow-sm"
                               oninput="syncColorField(this, '<?= $key ?>')">
                        <input type="text" id="<?= $key ?>_text" value="<?= $val ?>"
                               class="flex-1 px-3 py-2 text-xs font-mono rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#1D4533]/30 focus:border-[#1D4533]"
                               maxlength="7" placeholder="#000000"
                               oninput="syncTextToColor(this, '<?= $key ?>')">
                        <button type="button" title="Reset ke default"
                                class="text-gray-400 hover:text-gray-700 transition-colors"
                                onclick="resetSingleColor('<?= $key ?>', '<?= $defaultTheme[$key] ?>')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Warna Semantik & Surface</h3>
            </div>
            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 border-b border-gray-100">
                <?php
                $semanticFields = [
                    'color_surface'    => ['label' => 'Surface', 'desc' => 'Card & panel backgrounds'],
                    'color_background' => ['label' => 'Background', 'desc' => 'Page background color'],
                    'color_border'     => ['label' => 'Border', 'desc' => 'Garis pembatas / dividers'],
                    'color_text'       => ['label' => 'Text Color', 'desc' => 'Warna teks utama'],
                    'color_muted'      => ['label' => 'Muted Text', 'desc' => 'Teks sekunder / abu-abu'],
                    'color_success'    => ['label' => 'Success', 'desc' => 'Status berhasil / aktif'],
                    'color_warning'    => ['label' => 'Warning', 'desc' => 'Status peringatan / pending'],
                    'color_danger'     => ['label' => 'Danger', 'desc' => 'Status error / ditolak'],
                    'color_info'       => ['label' => 'Info', 'desc' => 'Status informasi / biru'],
                ];
                foreach ($semanticFields as $key => $info):
                    $val = e($currentTheme[$key] ?? $defaultTheme[$key]);
                ?>
                <div class="color-field" data-field="<?= $key ?>">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5"><?= $info['label'] ?></label>
                    <div class="text-xs text-gray-400 mb-2"><?= $info['desc'] ?></div>
                    <div class="flex items-center gap-3">
                        <input type="color" id="<?= $key ?>" name="<?= $key ?>" value="<?= $val ?>"
                               class="w-12 h-10 rounded-lg cursor-pointer border border-gray-200 p-0.5 shadow-sm"
                               oninput="syncColorField(this, '<?= $key ?>')">
                        <input type="text" id="<?= $key ?>_text" value="<?= $val ?>"
                               class="flex-1 px-3 py-2 text-xs font-mono rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-[#1D4533]/30 focus:border-[#1D4533]"
                               maxlength="7" placeholder="#000000"
                               oninput="syncTextToColor(this, '<?= $key ?>')">
                        <button type="button" title="Reset ke default"
                                class="text-gray-400 hover:text-gray-700 transition-colors"
                                onclick="resetSingleColor('<?= $key ?>', '<?= $defaultTheme[$key] ?>')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex items-center justify-between gap-3">
                <p class="text-xs text-gray-400">Perubahan berlaku setelah halaman di-refresh.</p>
                <button type="submit" id="save-btn"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-sm font-bold text-white shadow-md transition-all hover:opacity-90 active:scale-95"
                        style="background: <?= e($currentTheme['color_primary'] ?? '#1D4533') ?>">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan Tema
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function adjustBrightness(hex, percent) {
    hex = hex.replace('#', '');
    if (hex.length === 3) hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    if (hex.length !== 6) return '#' + hex;

    let num = parseInt(hex, 16);
    let r = (num >> 16);
    let g = ((num >> 8) & 0x00FF);
    let b = (num & 0x0000FF);

    if (percent > 0) {
        r = Math.min(255, Math.round(r + (255 - r) * percent));
        g = Math.min(255, Math.round(g + (255 - g) * percent));
        b = Math.min(255, Math.round(b + (255 - b) * percent));
    } else {
        let f = 1 + percent;
        r = Math.max(0, Math.round(r * f));
        g = Math.max(0, Math.round(g * f));
        b = Math.max(0, Math.round(b * f));
    }

    return '#' + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase();
}

function syncColorField(colorInput, key) {
    const textInput = document.getElementById(key + '_text');
    if (textInput) textInput.value = colorInput.value;
    
    
    if (key === 'color_primary') {
        const darkVal = adjustBrightness(colorInput.value, -0.32);
        const lightVal = adjustBrightness(colorInput.value, 0.22);
        
        const darkInput = document.getElementById('color_primary_dark');
        const darkText = document.getElementById('color_primary_dark_text');
        if (darkInput) darkInput.value = darkVal;
        if (darkText) darkText.value = darkVal;
        
        const lightInput = document.getElementById('color_primary_light');
        const lightText = document.getElementById('color_primary_light_text');
        if (lightInput) lightInput.value = lightVal;
        if (lightText) lightText.value = lightVal;
    } else if (key === 'color_secondary') {
        const secLightVal = adjustBrightness(colorInput.value, 0.22);
        const secLightInput = document.getElementById('color_secondary_light');
        const secLightText = document.getElementById('color_secondary_light_text');
        if (secLightInput) secLightInput.value = secLightVal;
        if (secLightText) secLightText.value = secLightVal;
    }
    
    updatePreview(key, colorInput.value);
}

function syncTextToColor(textInput, key) {
    const val = textInput.value.trim();
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        const colorInput = document.getElementById(key);
        if (colorInput) colorInput.value = val;
        
        if (key === 'color_primary') {
            const darkVal = adjustBrightness(val, -0.32);
            const lightVal = adjustBrightness(val, 0.22);
            
            const darkInput = document.getElementById('color_primary_dark');
            const darkText = document.getElementById('color_primary_dark_text');
            if (darkInput) darkInput.value = darkVal;
            if (darkText) darkText.value = darkVal;
            
            const lightInput = document.getElementById('color_primary_light');
            const lightText = document.getElementById('color_primary_light_text');
            if (lightInput) lightInput.value = lightVal;
            if (lightText) lightText.value = lightVal;
        } else if (key === 'color_secondary') {
            const secLightVal = adjustBrightness(val, 0.22);
            const secLightInput = document.getElementById('color_secondary_light');
            const secLightText = document.getElementById('color_secondary_light_text');
            if (secLightInput) secLightInput.value = secLightVal;
            if (secLightText) secLightText.value = secLightVal;
        }
        
        updatePreview(key, val);
    }
}

function resetSingleColor(key, defaultVal) {
    const colorInput = document.getElementById(key);
    const textInput  = document.getElementById(key + '_text');
    if (colorInput) colorInput.value = defaultVal;
    if (textInput)  textInput.value  = defaultVal;
    updatePreview(key, defaultVal);
}

function updatePreview(key, val) {
    const navbar      = document.getElementById('preview-navbar');
    const body        = document.getElementById('preview-body');
    const previewBar  = document.getElementById('preview-bar');
    const saveBtn     = document.getElementById('save-btn');

    if (key === 'color_primary') {
        const darkVal = adjustBrightness(val, -0.32);
        if (navbar)     navbar.style.background = darkVal;
        if (previewBar) previewBar.style.background = val;
        if (saveBtn)    saveBtn.style.background = val;
        document.querySelectorAll('#preview-tag-primary').forEach(el => el.style.background = val);
    }
    if (key === 'color_secondary') {
        const btn = document.getElementById('preview-btn');
        if (btn) btn.style.background = val;
    }
    if (key === 'color_accent1') {
        if (body) body.style.background = val;
    }
    if (key === 'color_accent2') {
        document.querySelectorAll('#preview-tag-accent').forEach(el => el.style.background = val);
    }
    if (key === 'color_success') {
        document.querySelectorAll('#live-preview .rounded-full').forEach(el => {
            if (el.textContent.trim() === 'Aktif') el.style.background = val;
        });
    }
    if (key === 'color_danger') {
        document.querySelectorAll('#live-preview .rounded-full').forEach(el => {
            if (el.textContent.trim() === 'Ditolak') el.style.background = val;
        });
    }
    if (key === 'color_warning') {
        document.querySelectorAll('#live-preview .rounded-full').forEach(el => {
            if (el.textContent.trim() === 'Pending') el.style.background = val;
        });
    }
}
</script>
