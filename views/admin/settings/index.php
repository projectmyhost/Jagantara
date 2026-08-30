<div class="space-y-6 max-w-5xl pb-12">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-gray-200 shadow-sm">
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm" style="background: var(--color-accent1); color: var(--color-primary);">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h1 class="text-base sm:text-lg font-extrabold text-gray-900 tracking-tight">Pengaturan Sistem & Parameter Global</h1>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">Konfigurasi izin registrasi akun, pendaftaran organisasi, batasan unggahan, keamanan rate limit, dan parameter peta.</p>
            </div>
        </div>

        <div class="flex-shrink-0">
            <button type="submit" form="settings-form" class="btn btn-primary py-2.5 px-5 text-xs font-bold shadow-md inline-flex items-center gap-2 whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Simpan Seluruh Perubahan
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

        <?php
            $isReg = ($settingsMap['registration_enabled']['value'] ?? '1') === '1';
            $isOrgReg = ($settingsMap['organizer_registration_enabled']['value'] ?? '1') === '1';
            $isMaint = ($settingsMap['maintenance_mode']['value'] ?? '0') === '1';
        ?>
        <div class="bg-white p-4 rounded-2xl border <?= $isReg ? 'border-emerald-200' : 'border-amber-200' ?> shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center <?= $isReg ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase text-gray-400 tracking-wider leading-none mb-0.5">Pendaftaran Akun (User)</div>
                    <div class="text-sm font-bold truncate <?= $isReg ? 'text-emerald-700' : 'text-amber-700' ?>">
                        <?= $isReg ? 'Terbuka / Aktif' : 'Ditutup / Nonaktif' ?>
                    </div>
                </div>
            </div>
            <span class="flex-shrink-0 inline-flex w-6 h-6 items-center justify-center text-[11px] font-bold rounded-full <?= $isReg ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                <?= $isReg ? '1' : '0' ?>
            </span>
        </div>

        <div class="bg-white p-4 rounded-2xl border <?= $isOrgReg ? 'border-emerald-200' : 'border-amber-200' ?> shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center <?= $isOrgReg ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' ?>">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase text-gray-400 tracking-wider leading-none mb-0.5">Gabung Organisasi</div>
                    <div class="text-sm font-bold truncate <?= $isOrgReg ? 'text-emerald-700' : 'text-amber-700' ?>">
                        <?= $isOrgReg ? 'Dibuka / Aktif' : 'Ditutup / Nonaktif' ?>
                    </div>
                </div>
            </div>
            <span class="flex-shrink-0 inline-flex w-6 h-6 items-center justify-center text-[11px] font-bold rounded-full <?= $isOrgReg ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' ?>">
                <?= $isOrgReg ? '1' : '0' ?>
            </span>
        </div>

        <div class="bg-white p-4 rounded-2xl border <?= !$isMaint ? 'border-gray-200' : 'border-rose-300' ?> shadow-sm flex items-center justify-between gap-3">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center <?= !$isMaint ? 'bg-gray-100 text-gray-600' : 'bg-rose-100 text-rose-700' ?>">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="text-[10px] font-semibold uppercase text-gray-400 tracking-wider leading-none mb-0.5">Mode Maintenance</div>
                    <div class="text-sm font-bold truncate <?= !$isMaint ? 'text-gray-800' : 'text-rose-700' ?>">
                        <?= !$isMaint ? 'Nonaktif (Normal)' : 'Aktif (Maintenance)' ?>
                    </div>
                </div>
            </div>
            <span class="flex-shrink-0 inline-flex w-6 h-6 items-center justify-center text-[11px] font-bold rounded-full <?= !$isMaint ? 'bg-gray-100 text-gray-600' : 'bg-rose-100 text-rose-800' ?>">
                <?= $isMaint ? '1' : '0' ?>
            </span>
        </div>
    </div>

    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none" style="-ms-overflow-style: none; scrollbar-width: none;">
        <button type="button" onclick="filterCategory('all')"
                class="category-tab-btn flex-shrink-0 flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap"
                data-target="all">
            <span>Semua Kategori</span>
        </button>
        <?php foreach ($definitions as $catKey => $catData): ?>
            <button type="button" onclick="filterCategory('<?= $catKey ?>')"
                    class="category-tab-btn flex-shrink-0 flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold transition-all whitespace-nowrap"
                    data-target="<?= $catKey ?>">
                <span class="w-4 h-4 flex-shrink-0"><?= $catData['icon'] ?></span>
                <span><?= e($catData['name']) ?></span>
                <span class="category-tab-count px-1.5 py-0.5 rounded-full text-[10px] font-bold"><?= count($catData['items']) ?></span>
            </button>
        <?php endforeach; ?>
    </div>

    <form id="settings-form" action="<?= url('admin/settings/update') ?>" method="POST">
        <?= csrfField() ?>
        <input type="hidden" name="_target_category" id="target_category" value="<?= e($activeTab) ?>">

        <div class="space-y-5">

            <?php foreach ($definitions as $groupKey => $groupData): ?>
                <div class="category-block bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" id="category_section_<?= $groupKey ?>">

                    <div class="px-5 sm:px-6 py-4 border-b border-gray-100 flex items-center gap-3" style="background: linear-gradient(to right, #f9fafb, #ffffff);">
                        <div class="w-9 h-9 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm flex-shrink-0" style="color: var(--color-primary);">
                            <?= $groupData['icon'] ?>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-sm font-extrabold text-gray-900 flex items-center gap-2 flex-wrap">
                                <?= e($groupData['name']) ?>
                                <span class="text-[10px] font-mono font-normal text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">[<?= e($groupKey) ?>]</span>
                            </h2>
                            <p class="text-xs text-gray-500 mt-0.5 leading-relaxed"><?= e($groupData['description']) ?></p>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <?php foreach ($groupData['items'] as $itemKey => $itemDef): ?>
                            <?php
                                $currentValue = $settingsMap[$itemKey]['value'] ?? ($itemDef['default'] ?? '');
                            ?>
                            <div class="px-5 sm:px-6 py-4 flex flex-col md:flex-row md:items-start gap-4">

                                <div class="md:w-5/12">
                                    <label for="setting_<?= e($itemKey) ?>" class="block text-sm font-semibold text-gray-800">
                                        <?= e($itemDef['label']) ?>
                                    </label>
                                    <div class="mt-1 mb-1.5">
                                        <span class="inline-block text-[10px] font-mono text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-200">
                                            key: <?= e($itemKey) ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($itemDef['description'])): ?>
                                        <p class="text-xs text-gray-500 leading-relaxed">
                                            <?= e($itemDef['description']) ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="md:w-7/12">
                                    <?php if ($itemDef['type'] === 'toggle'): ?>

                                        <div class="bg-gray-50 px-4 py-3 rounded-xl border border-gray-200 flex items-center justify-between gap-4">
                                            <div class="flex flex-col min-w-0">
                                                <span class="text-xs font-bold text-gray-800 leading-snug" id="label_<?= e($itemKey) ?>">
                                                    <?= $currentValue === '1' ? ($itemDef['activeLabel'] ?? 'Status: Aktif') : ($itemDef['disabledLabel'] ?? 'Status: Nonaktif') ?>
                                                </span>
                                                <span class="text-[11px] text-gray-400 mt-0.5" id="desc_<?= e($itemKey) ?>">
                                                    <?= $currentValue === '1' ? 'Fitur ini sedang dibuka dan beroperasi.' : 'Fitur ini sedang ditutup/dinonaktifkan.' ?>
                                                </span>
                                            </div>

                                            <select id="setting_<?= e($itemKey) ?>" name="<?= e($itemKey) ?>"
                                                    class="form-control text-xs font-bold py-1.5 px-2.5 rounded-lg flex-shrink-0 w-auto min-w-[120px]"
                                                    style="<?= $currentValue === '1' ? 'background:#f0fdf4; color:#166534; border-color:#a7f3d0;' : 'background:#f3f4f6; color:#374151; border-color:#d1d5db;' ?>"
                                                    onchange="handleToggleChange(this, '<?= e($itemKey) ?>', '<?= e($itemDef['activeLabel'] ?? 'Aktif') ?>', '<?= e($itemDef['disabledLabel'] ?? 'Nonaktif') ?>')">
                                                <option value="1" <?= $currentValue === '1' ? 'selected' : '' ?>>Aktif (Buka / 1)</option>
                                                <option value="0" <?= $currentValue === '0' ? 'selected' : '' ?>>Nonaktif (Tutup / 0)</option>
                                            </select>
                                        </div>

                                    <?php elseif ($itemDef['type'] === 'textarea'): ?>
                                        <textarea id="setting_<?= e($itemKey) ?>"
                                                  name="<?= e($itemKey) ?>"
                                                  rows="3"
                                                  class="form-control text-xs leading-relaxed w-full"
                                                  placeholder="<?= e($itemDef['placeholder'] ?? '') ?>"><?= e($currentValue) ?></textarea>

                                    <?php elseif ($itemDef['type'] === 'number'): ?>
                                        <div class="relative w-full">
                                            <input type="number"
                                                   id="setting_<?= e($itemKey) ?>"
                                                   name="<?= e($itemKey) ?>"
                                                   value="<?= e($currentValue) ?>"
                                                   min="<?= $itemDef['min'] ?? '0' ?>"
                                                   max="<?= $itemDef['max'] ?? '999999' ?>"
                                                   step="<?= $itemDef['step'] ?? '1' ?>"
                                                   class="form-control text-xs font-semibold w-full <?= !empty($itemDef['unit']) ? 'pr-20' : '' ?>"
                                                   placeholder="<?= e($itemDef['placeholder'] ?? '') ?>">
                                            <?php if (!empty($itemDef['unit'])): ?>
                                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-xs font-bold text-gray-400 bg-gray-50 border-l border-gray-200 px-2 rounded-r-lg">
                                                    <?= e($itemDef['unit']) ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                    <?php elseif ($itemDef['type'] === 'email'): ?>
                                        <div class="relative w-full">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                                </svg>
                                            </div>
                                            <input type="email"
                                                   id="setting_<?= e($itemKey) ?>"
                                                   name="<?= e($itemKey) ?>"
                                                   value="<?= e($currentValue) ?>"
                                                   class="form-control text-xs pl-9 w-full"
                                                   placeholder="<?= e($itemDef['placeholder'] ?? 'nama@email.com') ?>">
                                        </div>

                                    <?php else: ?>
                                        <input type="text"
                                               id="setting_<?= e($itemKey) ?>"
                                               name="<?= e($itemKey) ?>"
                                               value="<?= e($currentValue) ?>"
                                               class="form-control text-xs w-full"
                                               placeholder="<?= e($itemDef['placeholder'] ?? '') ?>">
                                    <?php endif; ?>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
            <?php endforeach; ?>

            <div class="sticky bottom-4 z-20 bg-white/95 backdrop-blur-md rounded-2xl border border-gray-200 p-4 shadow-xl flex items-center justify-between gap-4">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="leading-tight">Perubahan akan langsung aktif secara realtime di seluruh sistem Jagantara.</span>
                </div>

                <button type="submit" class="btn btn-primary py-2.5 px-6 font-bold text-xs shadow-md inline-flex items-center gap-2 flex-shrink-0 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Seluruh Pengaturan
                </button>
            </div>

        </div>
    </form>

</div>

<style>
.category-tab-btn {
    background: #ffffff;
    color: #4b5563;
    border: 1px solid #e5e7eb;
}
.category-tab-btn:hover {
    background: #f9fafb;
    border-color: #d1d5db;
    color: #1f2937;
}
.category-tab-btn.active {
    background: var(--color-primary) !important;
    color: #ffffff !important;
    border-color: var(--color-primary) !important;
}
.category-tab-btn.active span,
.category-tab-btn.active svg {
    color: #ffffff !important;
}
.category-tab-btn.active .category-tab-count {
    background: rgba(255,255,255,0.25) !important;
    color: #ffffff !important;
}
.category-tab-btn:not(.active) .category-tab-count {
    background: #f3f4f6;
    color: #6b7280;
}
</style>

<script>
function filterCategory(catKey) {
    const tabs = document.querySelectorAll('.category-tab-btn');
    const sections = document.querySelectorAll('.category-block');
    const targetInput = document.getElementById('target_category');

    if (targetInput) targetInput.value = catKey;

    tabs.forEach(tab => {
        const isSelected = tab.getAttribute('data-target') === catKey;
        
        if (isSelected) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });

    sections.forEach(sec => {
        if (catKey === 'all') {
            sec.style.display = '';
        } else {
            sec.style.display = (sec.id === 'category_section_' + catKey) ? '' : 'none';
        }
    });

    const url = new URL(window.location);
    if (catKey === 'all') {
        url.searchParams.delete('tab');
    } else {
        url.searchParams.set('tab', catKey);
    }
    window.history.replaceState({}, '', url);
}

function handleToggleChange(select, itemKey, activeLabel, disabledLabel) {
    const labelSpan = document.getElementById('label_' + itemKey);
    const descSpan  = document.getElementById('desc_' + itemKey);
    const isOne = select.value === '1';

    if (labelSpan) labelSpan.textContent = isOne ? activeLabel : disabledLabel;
    if (descSpan) descSpan.textContent = isOne ? 'Fitur ini sedang dibuka dan beroperasi.' : 'Fitur ini sedang ditutup/dinonaktifkan.';

    if (isOne) {
        select.style.background = '#f0fdf4';
        select.style.color = '#166534';
        select.style.borderColor = '#a7f3d0';
    } else {
        select.style.background = '#f3f4f6';
        select.style.color = '#374151';
        select.style.borderColor = '#d1d5db';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const activeTab = '<?= e($activeTab) ?>';
    const activeBtn = document.querySelector(`.category-tab-btn[data-target="${activeTab}"]`);
    if (activeBtn) activeBtn.classList.add('active');

    if (activeTab && activeTab !== 'all') {
        document.querySelectorAll('.category-block').forEach(sec => {
            sec.style.display = (sec.id === 'category_section_' + activeTab) ? '' : 'none';
        });
    } else {
        const allBtn = document.querySelector('.category-tab-btn[data-target="all"]');
        if (allBtn) allBtn.classList.add('active');
    }
});
</script>
