

(function() {
    
    const isLoggedIn = document.body.dataset.userId || document.querySelector('.navbar-user');
    
    if (!isLoggedIn) {
        return; 
    }

    const checkStatusUrl = document.body.dataset.checkStatusUrl || '/auth/check-status';
    const logoutUrl = document.body.dataset.logoutUrl || '/auth/logout';
    const loginUrl = document.body.dataset.loginUrl || '/auth/login';
    const contactEmail = document.body.dataset.contactEmail || 'info@jagantara.id';

    let checkInterval = null;
    let isModalShown = false;

    
    function showBannedModal(message) {
        if (isModalShown) return;
        isModalShown = true;

        
        if (checkInterval) {
            clearInterval(checkInterval);
            checkInterval = null;
        }

        
        try {
            document.querySelectorAll('.modal-overlay, .logout-modal-overlay, #guest-auth-modal, #logout-modal-overlay').forEach(el => {
                el.classList.remove('show', 'active');
                el.style.display = 'none';
            });
            const sidebar = document.getElementById('admin-sidebar');
            if (sidebar) sidebar.classList.remove('open');
            const adminOverlay = document.getElementById('admin-sidebar-overlay');
            if (adminOverlay) adminOverlay.classList.remove('visible');
        } catch (e) {
            
        }

        
        document.documentElement.classList.add('banned-locked');
        document.body.classList.add('banned-locked');
        document.body.style.overflow = 'hidden';
        document.body.style.pointerEvents = 'none';

        const displayMessage = message || 'Akun Anda telah di-banned, hubungi kontak admin untuk informasi selanjutnya.';

        
        const overlay = document.createElement('div');
        overlay.id = 'banned-user-overlay';
        overlay.className = 'banned-user-overlay active';
        overlay.setAttribute('role', 'alertdialog');
        overlay.setAttribute('aria-modal', 'true');
        overlay.setAttribute('aria-labelledby', 'banned-modal-title');
        overlay.setAttribute('aria-describedby', 'banned-modal-desc');

        overlay.innerHTML = `
            <div class="banned-user-modal" id="banned-modal-card" onclick="event.stopPropagation()">
                <div class="banned-user-icon" aria-hidden="true">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
                    </svg>
                </div>
                <h2 class="banned-user-title" id="banned-modal-title">Akun Anda Telah Di-banned</h2>
                <p class="banned-user-message" id="banned-modal-desc">${displayMessage}</p>
                <div class="banned-contact-box">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Hubungi kontak admin: <strong>${contactEmail}</strong></span>
                </div>
                <button type="button" class="banned-user-btn" id="banned-user-logout-btn" onclick="handleBannedLogout()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Kembali ke Halaman Login</span>
                </button>
            </div>
        `;

        document.body.appendChild(overlay);
        overlay.style.pointerEvents = 'auto';

        
        setTimeout(() => {
            const btn = document.getElementById('banned-user-logout-btn');
            if (btn) {
                btn.focus();
            }
        }, 100);

        
        window.addEventListener('keydown', trapBannedKeys, true);
        window.addEventListener('keyup', trapBannedKeys, true);
        window.addEventListener('keypress', trapBannedKeys, true);

        
        window.addEventListener('contextmenu', function(e) {
            if (isModalShown) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);
    }

    
    function trapBannedKeys(e) {
        if (!isModalShown) return;

        const btn = document.getElementById('banned-user-logout-btn');
        if (e.target !== btn) {
            e.preventDefault();
            e.stopPropagation();
            if (btn) btn.focus();
            return false;
        }

        
        if (e.key === 'Tab' || e.key === 'Escape') {
            e.preventDefault();
            e.stopPropagation();
            if (btn) btn.focus();
            return false;
        }
    }

    
    window.handleBannedLogout = function() {
        const btn = document.getElementById('banned-user-logout-btn') || document.querySelector('.banned-user-btn');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = `
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;">
                    <line x1="12" y1="2" x2="12" y2="6"></line>
                    <line x1="12" y1="18" x2="12" y2="22"></line>
                    <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                    <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                    <line x1="2" y1="12" x2="6" y2="12"></line>
                    <line x1="18" y1="12" x2="22" y2="12"></line>
                    <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                    <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                </svg>
                <span>Mengarahkan ke Halaman Login...</span>
            `;
        }

        
        const dest = logoutUrl + (logoutUrl.includes('?') ? '&' : '?') + 'to=login&banned=1';
        
        
        setTimeout(() => {
            window.location.replace(dest);
        }, 350);
    };

    
    function checkUserStatus() {
        if (isModalShown) return;

        fetch(checkStatusUrl, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            cache: 'no-store'
        })
        .then(response => {
            if (response.status === 401 || response.status === 403) {
                return response.json().catch(() => ({ status: 'banned' }));
            }
            return response.json();
        })
        .then(data => {
            if (data && (data.status === 'banned' || data.banned === true)) {
                const message = data.message || 'Akun Anda telah di-banned, hubungi kontak admin untuk informasi selanjutnya.';
                showBannedModal(message);
            }
        })
        .catch(error => {
            
        });
    }

    
    function startStatusChecking() {
        
        setTimeout(checkUserStatus, 1500);
        
        
        checkInterval = setInterval(checkUserStatus, 6000);
    }

    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startStatusChecking);
    } else {
        startStatusChecking();
    }

    
    window.addEventListener('focus', function() {
        if (!isModalShown) {
            checkUserStatus();
        }
    });

    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible' && !isModalShown) {
            checkUserStatus();
        }
    });
})();

(function () {
    const ICONS = {
        success: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        error:   '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
        warning: '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        info:    '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
    };
    const LABELS = { success: 'Berhasil', error: 'Kesalahan', warning: 'Perhatian', info: 'Informasi' };

    function getContainer() {
        let c = document.getElementById('toast-container');
        if (!c) {
            c = document.createElement('div');
            c.id = 'toast-container';
            document.body.appendChild(c);
        }
        return c;
    }

    function dismissToast(el) {
        if (el.dataset.dismissed) return;
        el.dataset.dismissed = '1';
        el.classList.add('toast-closing');
        el.addEventListener('animationend', () => el.remove(), { once: true });
    }

    window.showToast = function (type, message, duration) {
        if (!type || !message) return;
        duration = duration || (type === 'error' ? 6000 : 4500);
        const safeType = ['success', 'error', 'warning', 'info'].includes(type) ? type : 'info';

        const toast = document.createElement('div');
        toast.className = `toast toast-${safeType}`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'polite');
        toast.innerHTML = `
            <span class="toast-icon" aria-hidden="true">${ICONS[safeType]}</span>
            <div class="toast-body">
                <div class="toast-title">${LABELS[safeType]}</div>
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" aria-label="Tutup notifikasi" title="Tutup">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="toast-progress" style="animation-duration:${duration}ms"></div>
        `;

        toast.querySelector('.toast-close').addEventListener('click', () => dismissToast(toast));

        const container = getContainer();
        container.appendChild(toast);

        setTimeout(() => dismissToast(toast), duration);
    };
}());

document.addEventListener('DOMContentLoaded', () => {
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const guestModal = document.getElementById('guest-auth-modal');
            if (guestModal) guestModal.classList.remove('show');

            const lightbox = document.getElementById('lightbox');
            if (lightbox) lightbox.classList.remove('show');

            const rejectModal = document.getElementById('reject-modal');
            if (rejectModal) rejectModal.classList.remove('show');
        }
    });

    
    
    document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
        const onsubmitStr = form.getAttribute('onsubmit') || '';
        const match = onsubmitStr.match(/confirm\(\s*['"](.*?)['"]\s*\)/s);
        const msg = match ? match[1].replace(/\\n/g, '\n').replace(/\\'/g, "'").replace(/\\"/g, '"') : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
        form.setAttribute('data-confirm', msg);
        if (/hapus|delete|batal|leave|reset|tolak|reject/i.test(msg) || /delete|reject|leave|reset/i.test(form.action)) {
            form.setAttribute('data-confirm-danger', 'true');
            form.setAttribute('data-confirm-title', 'Konfirmasi Hapus');
            form.setAttribute('data-confirm-yes', 'Ya, Hapus');
        }
        form.removeAttribute('onsubmit');
        form.onsubmit = null;
    });

    
    document.addEventListener('submit', async function(e) {
        const form = e.target;
        if (!form || !form.hasAttribute('data-confirm')) return;

        if (form.dataset.confirmed === 'true') {
            form.removeAttribute('data-confirmed');
            return; 
        }

        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        const message = form.getAttribute('data-confirm') || 'Apakah Anda yakin?';
        const isDanger = form.hasAttribute('data-confirm-danger') || /hapus|delete|batal|reset/i.test(message) || /delete|reject/i.test(form.action);
        const title = form.getAttribute('data-confirm-title') || (isDanger ? 'Konfirmasi Tindakan' : 'Konfirmasi');
        const confirmText = form.getAttribute('data-confirm-yes') || (isDanger ? 'Ya, Hapus' : 'Ya');
        const cancelText = form.getAttribute('data-confirm-no') || 'Batal';

        const confirmed = isDanger
            ? await confirmDanger(message, { title, confirmText, cancelText })
            : await customConfirm(message, { title, confirmText, cancelText });

        if (confirmed) {
            form.dataset.confirmed = 'true';
            form.submit();
        }
    }, true);

    
    document.addEventListener('click', async function(e) {
        
        const submitBtn = e.target.closest('button[type="submit"], input[type="submit"]');
        if (submitBtn) return;

        const trigger = e.target.closest('[data-confirm]');
        if (!trigger) return;

        if (trigger.dataset.confirmed === 'true') {
            trigger.removeAttribute('data-confirmed');
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        const message = trigger.getAttribute('data-confirm');
        const isDanger = trigger.hasAttribute('data-confirm-danger') || /hapus|delete|batal|reset/i.test(message);
        const title = trigger.getAttribute('data-confirm-title') || (isDanger ? 'Konfirmasi Tindakan' : 'Konfirmasi');
        const confirmText = trigger.getAttribute('data-confirm-yes') || (isDanger ? 'Ya, Lanjutkan' : 'Ya');
        const cancelText = trigger.getAttribute('data-confirm-no') || 'Batal';

        const confirmed = isDanger
            ? await confirmDanger(message, { title, confirmText, cancelText })
            : await customConfirm(message, { title, confirmText, cancelText });

        if (confirmed) {
            trigger.dataset.confirmed = 'true';
            if (trigger.tagName === 'A' && trigger.href) {
                window.location.href = trigger.href;
            } else if (trigger.form) {
                trigger.form.submit();
            } else if (typeof trigger.onclick === 'function') {
                trigger.onclick();
            }
        }
    }, true);
});

function openGuestAuthModal(action) {
    const modal = document.getElementById('guest-auth-modal');
    if (modal) {
        modal.classList.add('show');
    } else {
        const loginUrl = document.body.dataset.loginUrl || '/auth/login';
        window.location.href = loginUrl;
    }
}

function closeGuestAuthModal() {
    const modal = document.getElementById('guest-auth-modal');
    if (modal) {
        modal.classList.remove('show');
    }
}

function closeGuestAuthModalOnBackdrop(e) {
    if (e.target.id === 'guest-auth-modal') {
        closeGuestAuthModal();
    }
}

function openLogoutModal() {
    const modal = document.getElementById('logout-modal-overlay');
    if (modal) {
        modal.classList.add('active');
        
        document.body.style.overflow = 'hidden';
        
        
        const modalContent = modal.querySelector('.logout-modal');
        if (modalContent) {
            modalContent.style.animation = 'modalSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) both';
        }
        
        
        const cancelBtn = modal.querySelector('.logout-btn-cancel');
        if (cancelBtn) {
            setTimeout(() => cancelBtn.focus(), 100);
        }
    }
}

function closeLogoutModal() {
    const modal = document.getElementById('logout-modal-overlay');
    if (modal) {
        modal.classList.remove('active');
        
        document.body.style.overflow = '';
    }
}

function confirmLogout() {
    
    const confirmBtn = document.querySelector('.logout-btn-confirm');
    if (confirmBtn) {
        confirmBtn.innerHTML = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="2" x2="12" y2="6"></line>
                <line x1="12" y1="18" x2="12" y2="22"></line>
                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                <line x1="2" y1="12" x2="6" y2="12"></line>
                <line x1="18" y1="12" x2="22" y2="12"></line>
                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
            </svg>
            Keluar...
        `;
        confirmBtn.disabled = true;
        confirmBtn.style.opacity = '0.7';
    }
    
    
    const modal = document.getElementById('logout-modal-overlay');
    if (modal) {
        const modalContent = modal.querySelector('.logout-modal');
        if (modalContent) {
            modalContent.style.animation = 'modalSlideOut 0.25s ease-out forwards';
        }
    }
    
    const logoutUrl = document.body.dataset.logoutUrl || '/auth/logout';
    
    setTimeout(() => {
        window.location.href = logoutUrl;
    }, 300);
}

function closeLogoutModalOnBackdrop(e) {
    if (e.target.id === 'logout-modal-overlay') {
        closeLogoutModal();
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const logoutModal = document.getElementById('logout-modal-overlay');
        if (logoutModal && logoutModal.classList.contains('active')) {
            closeLogoutModal();
        }
    }
});

const style = document.createElement('style');
style.textContent = `
    @keyframes modalSlideOut {
        from {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        to {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
    }
`;
document.head.appendChild(style);

function customAlert(message, type = 'info', title = '') {
    return new Promise((resolve) => {
        
        const existingAlert = document.getElementById('custom-alert-overlay');
        if (existingAlert) existingAlert.remove();

        
        const titles = {
            info: 'Informasi',
            success: 'Berhasil',
            warning: 'Peringatan',
            error: 'Kesalahan'
        };

        
        const icons = {
            info: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            success: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            warning: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            error: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        };

        const finalTitle = title || titles[type] || titles.info;
        const icon = icons[type] || icons.info;

        
        const overlay = document.createElement('div');
        overlay.id = 'custom-alert-overlay';
        overlay.className = 'custom-alert-overlay';
        overlay.innerHTML = `
            <div class="custom-alert-modal" data-type="${type}">
                <div class="custom-alert-header">
                    <div class="custom-alert-icon">
                        ${icon}
                    </div>
                    <h3 class="custom-alert-title">${finalTitle}</h3>
                    <p class="custom-alert-message">${message}</p>
                </div>
                <div class="custom-alert-footer single-button">
                    <button type="button" class="custom-alert-btn custom-alert-btn-primary" data-action="ok">
                        OK
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';

        
        setTimeout(() => overlay.classList.add('active'), 10);

        
        const modalContent = overlay.querySelector('.custom-alert-modal');
        modalContent.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        
        const okBtn = overlay.querySelector('[data-action="ok"]');
        setTimeout(() => okBtn.focus(), 150);

        
        const closeAlert = () => {
            const modal = overlay.querySelector('.custom-alert-modal');
            modal.classList.add('closing');
            
            setTimeout(() => {
                overlay.classList.remove('active');
                setTimeout(() => {
                    overlay.remove();
                    document.body.style.overflow = '';
                    resolve(true);
                }, 200);
            }, 250);
        };

        
        okBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            closeAlert();
        });
        
        
        const escHandler = (e) => {
            if (e.key === 'Escape') {
                closeAlert();
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);

        
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeAlert();
            }
        });
    });
}

function customConfirm(message, options = {}) {
    return new Promise((resolve) => {
        
        const existingConfirm = document.getElementById('custom-alert-overlay');
        if (existingConfirm) existingConfirm.remove();

        
        const {
            title = 'Konfirmasi',
            confirmText = 'Ya',
            cancelText = 'Batal',
            type = 'confirm',
            danger = false
        } = options;

        
        const icon = danger 
            ? '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
            : '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

        const modalType = danger ? 'error' : type;

        
        const overlay = document.createElement('div');
        overlay.id = 'custom-alert-overlay';
        overlay.className = 'custom-alert-overlay';
        overlay.innerHTML = `
            <div class="custom-alert-modal" data-type="${modalType}">
                <div class="custom-alert-header">
                    <div class="custom-alert-icon">
                        ${icon}
                    </div>
                    <h3 class="custom-alert-title">${title}</h3>
                    <p class="custom-alert-message">${message}</p>
                </div>
                <div class="custom-alert-footer">
                    <button type="button" class="custom-alert-btn custom-alert-btn-secondary" data-action="cancel">
                        ${cancelText}
                    </button>
                    <button type="button" class="custom-alert-btn ${danger ? 'custom-alert-btn-danger' : 'custom-alert-btn-primary'}" data-action="confirm">
                        ${confirmText}
                    </button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);
        document.body.style.overflow = 'hidden';

        
        setTimeout(() => overlay.classList.add('active'), 10);

        
        const modalContent = overlay.querySelector('.custom-alert-modal');
        modalContent.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        
        const cancelBtn = overlay.querySelector('[data-action="cancel"]');
        setTimeout(() => cancelBtn.focus(), 150);

        
        const closeConfirm = (result) => {
            const modal = overlay.querySelector('.custom-alert-modal');
            modal.classList.add('closing');
            
            setTimeout(() => {
                overlay.classList.remove('active');
                setTimeout(() => {
                    overlay.remove();
                    document.body.style.overflow = '';
                    resolve(result);
                }, 200);
            }, 250);
        };

        
        overlay.querySelector('[data-action="confirm"]').addEventListener('click', (e) => {
            e.stopPropagation();
            closeConfirm(true);
        });
        overlay.querySelector('[data-action="cancel"]').addEventListener('click', (e) => {
            e.stopPropagation();
            closeConfirm(false);
        });

        
        const escHandler = (e) => {
            if (e.key === 'Escape') {
                closeConfirm(false);
                document.removeEventListener('keydown', escHandler);
            }
        };
        document.addEventListener('keydown', escHandler);

        
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                closeConfirm(false);
            }
        });
    });
}

window.alert = function(message) {
    customAlert(String(message), 'info');
};

function confirmDanger(message, options = {}) {
    return customConfirm(message, { ...options, danger: true });
}

window.customConfirm = customConfirm;
window.confirmDanger = confirmDanger;

window.alertSuccess = function(message, title) {
    return customAlert(message, 'success', title);
};

window.alertError = function(message, title) {
    return customAlert(message, 'error', title);
};

window.alertWarning = function(message, title) {
    return customAlert(message, 'warning', title);
};

let _mobileSidebarScrollY = 0;

function _lockMobileBodyScroll() {
    _mobileSidebarScrollY = window.scrollY || document.documentElement.scrollTop;
    document.body.style.top = '-' + _mobileSidebarScrollY + 'px';
    document.body.classList.add('mobile-sidebar-open');
}

function _unlockMobileBodyScroll() {
    document.body.classList.remove('mobile-sidebar-open');
    document.body.style.top = '';
    window.scrollTo({ top: _mobileSidebarScrollY, behavior: 'instant' });
}

function toggleMobileSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    if (!sidebar) return;
    
    if (sidebar.classList.contains('active')) {
        closeMobileSidebar();
    } else {
        openMobileSidebar();
    }
}

function openMobileSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        _lockMobileBodyScroll();
    }
}

function closeMobileSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-sidebar-overlay');
    
    if (sidebar && overlay) {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        _unlockMobileBodyScroll();
    }
}

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const sidebar = document.getElementById('mobile-sidebar');
        if (sidebar && sidebar.classList.contains('active')) {
            closeMobileSidebar();
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const overlay = document.getElementById('mobile-sidebar-overlay');
    if (overlay) {
        overlay.addEventListener('touchmove', (e) => {
            e.preventDefault();
        }, { passive: false });
    }
});

window.addEventListener('resize', () => {
    if (window.innerWidth >= 769) {
        closeMobileSidebar();
    }
});

(function initHeroScrollAndParallax() {
    const navbar = document.querySelector('.navbar');
    const heroSection = document.querySelector('.hero-section');
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function handleScrollDynamics() {
        const scrollY = window.scrollY || window.pageYOffset;

        
        if (navbar) {
            const isHomepage = window.location.pathname === '/' || window.location.pathname === '' || window.location.pathname.endsWith('/index.php');
            if (isHomepage && heroSection) {
                const heroBottom = heroSection.getBoundingClientRect().bottom;
                if (scrollY < 40) {
                    navbar.classList.add('navbar-hero-mode');
                    navbar.classList.remove('navbar-solid');
                } else if (heroBottom > 68) {
                    navbar.classList.remove('navbar-hero-mode');
                    navbar.classList.add('navbar-solid');
                } else {
                    navbar.classList.remove('navbar-hero-mode');
                    navbar.classList.add('navbar-solid');
                }
            } else {
                navbar.classList.remove('navbar-hero-mode');
                navbar.classList.add('navbar-solid');
            }
        }

        
        if (!prefersReducedMotion && window.innerWidth >= 769 && heroSection) {
            const activeBg = heroSection.querySelector('.hero-card.active .hero-bg-img');
            if (activeBg) {
                const rect = heroSection.getBoundingClientRect();
                if (rect.bottom > 0 && rect.top <= window.innerHeight) {
                    const depthFactor = 0.18;
                    const translateY = (scrollY * depthFactor).toFixed(2);
                    activeBg.style.transform = `translate3d(0, ${translateY}px, 0) scale(1.04)`;
                }
            }
        }
    }

    
    handleScrollDynamics();

    
    window.addEventListener('resize', function () {
        requestAnimationFrame(handleScrollDynamics);
    }, { passive: true });

    
    let ticking = false;
    window.addEventListener('scroll', function () {
        if (!ticking) {
            requestAnimationFrame(function () {
                handleScrollDynamics();
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
})();

(function() {
    function initSearchableSelects() {
        const selects = document.querySelectorAll('.searchable-select');
        
        selects.forEach(select => {
            const trigger = select.querySelector('.searchable-select-trigger');
            const valueDisplay = select.querySelector('.searchable-select-value');
            const hiddenInput = select.closest('.searchable-select-wrapper').querySelector('input[type="hidden"]');
            const searchInput = select.querySelector('.searchable-select-input');
            const optionsList = select.querySelector('.searchable-select-options');
            const options = Array.from(select.querySelectorAll('.searchable-select-option'));
            
            
            const selectedOption = options.find(opt => opt.classList.contains('selected'));
            if (selectedOption) {
                valueDisplay.textContent = selectedOption.textContent;
            }
            
            
            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const wasOpen = select.classList.contains('open');
                
                
                document.querySelectorAll('.searchable-select.open').forEach(s => {
                    if (s !== select) {
                        s.classList.remove('open');
                    }
                });
                
                if (!wasOpen) {
                    select.classList.add('open');
                    searchInput.value = '';
                    searchInput.focus();
                    filterOptions('');
                } else {
                    select.classList.remove('open');
                }
            });
            
            
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase().trim();
                filterOptions(query);
            });
            
            
            searchInput.addEventListener('click', (e) => {
                e.stopPropagation();
            });
            
            
            function filterOptions(query) {
                let hasVisibleOption = false;
                
                options.forEach(option => {
                    const searchText = option.dataset.search || option.textContent.toLowerCase();
                    const matches = searchText.includes(query);
                    
                    if (matches) {
                        option.classList.remove('hidden');
                        hasVisibleOption = true;
                    } else {
                        option.classList.add('hidden');
                    }
                });
                
                
                let noResultsMsg = optionsList.querySelector('.no-results');
                if (!hasVisibleOption && query) {
                    if (!noResultsMsg) {
                        noResultsMsg = document.createElement('li');
                        noResultsMsg.className = 'searchable-select-option no-results';
                        noResultsMsg.textContent = 'Tidak ada hasil ditemukan';
                        optionsList.appendChild(noResultsMsg);
                    } else {
                        noResultsMsg.classList.remove('hidden');
                    }
                } else if (noResultsMsg) {
                    noResultsMsg.classList.add('hidden');
                }
            }
            
            
            options.forEach(option => {
                option.addEventListener('click', (e) => {
                    e.stopPropagation();
                    
                    
                    options.forEach(opt => opt.classList.remove('selected'));
                    option.classList.add('selected');
                    
                    
                    valueDisplay.textContent = option.textContent;
                    hiddenInput.value = option.dataset.value;
                    
                    
                    select.classList.remove('open');
                    
                    
                    const event = new Event('change', { bubbles: true });
                    hiddenInput.dispatchEvent(event);
                });
            });
            
            
            document.addEventListener('click', (e) => {
                if (!select.contains(e.target)) {
                    select.classList.remove('open');
                }
            });
            
            
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && select.classList.contains('open')) {
                    select.classList.remove('open');
                    trigger.focus();
                }
            });
            
            
            searchInput.addEventListener('keydown', (e) => {
                const visibleOptions = options.filter(opt => !opt.classList.contains('hidden'));
                const currentIndex = visibleOptions.findIndex(opt => opt === document.activeElement);
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (currentIndex < visibleOptions.length - 1) {
                        visibleOptions[currentIndex + 1].focus();
                    } else if (visibleOptions.length > 0) {
                        visibleOptions[0].focus();
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (currentIndex > 0) {
                        visibleOptions[currentIndex - 1].focus();
                    } else if (visibleOptions.length > 0) {
                        visibleOptions[visibleOptions.length - 1].focus();
                    }
                } else if (e.key === 'Enter' && visibleOptions.length > 0) {
                    e.preventDefault();
                    visibleOptions[0].click();
                }
            });
            
            
            options.forEach(option => {
                option.setAttribute('tabindex', '0');
                option.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        option.click();
                    }
                });
            });
        });
    }
    
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSearchableSelects);
    } else {
        initSearchableSelects();
    }
})();

function toggleCustomCategoryInput(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const slug = selectedOption.dataset.slug || '';
    const customWrapper = document.getElementById('custom-category-wrapper');
    const customInput = document.getElementById('custom_category');
    
    if (slug === 'lainnya') {
        customWrapper.style.display = 'block';
        customInput.focus();
    } else {
        customWrapper.style.display = 'none';
        customInput.value = ''; 
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    if (categorySelect) {
        toggleCustomCategoryInput(categorySelect);
    }
});
