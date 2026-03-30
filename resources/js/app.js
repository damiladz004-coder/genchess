import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const THEME_STORAGE_KEY = 'gc_theme';

function systemPrefersDark() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
}

function resolveInitialTheme() {
    const savedTheme = localStorage.getItem(THEME_STORAGE_KEY);
    if (savedTheme === 'dark' || savedTheme === 'light') {
        return savedTheme;
    }

    return systemPrefersDark() ? 'dark' : 'light';
}

function applyTheme(theme) {
    const isDark = theme === 'dark';
    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');

    document.querySelectorAll('[data-theme-toggle-label]').forEach((el) => {
        el.textContent = isDark ? 'Light' : 'Dark';
    });
}

function toggleTheme() {
    const current = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
    const next = current === 'dark' ? 'light' : 'dark';
    localStorage.setItem(THEME_STORAGE_KEY, next);
    applyTheme(next);
}

function initThemeToggle() {
    applyTheme(resolveInitialTheme());

    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-theme-toggle]');
        if (!button) {
            return;
        }

        event.preventDefault();
        toggleTheme();
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThemeToggle);
} else {
    initThemeToggle();
}

const SELAR_UTM_KEYS = [
    'utm_source',
    'utm_medium',
    'utm_campaign',
    'utm_term',
    'utm_content',
    'gclid',
    'fbclid',
    'ttclid',
];

let selarModalElements = null;

function ensureSelarModal() {
    if (selarModalElements) {
        return selarModalElements;
    }

    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 z-[100] hidden bg-slate-950/70 p-4 backdrop-blur-sm';
    overlay.innerHTML = `
        <div class="mx-auto flex min-h-full max-w-5xl items-center justify-center">
            <div class="w-full overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                    <div>
                        <p class="font-semibold text-slate-900">Complete enrollment on Selar</p>
                        <p class="text-sm text-slate-500">If the embedded checkout does not load, you will be redirected automatically.</p>
                    </div>
                    <button type="button" class="rounded-full border border-slate-200 px-3 py-1 text-sm font-medium text-slate-600" data-selar-close>
                        Close
                    </button>
                </div>
                <div class="relative bg-slate-100">
                    <div class="flex h-[75vh] items-center justify-center text-sm text-slate-500" data-selar-loading>
                        Loading secure checkout...
                    </div>
                    <iframe
                        title="Selar checkout"
                        class="hidden h-[75vh] w-full border-0"
                        loading="lazy"
                        allow="payment *"
                        referrerpolicy="strict-origin-when-cross-origin"
                        data-selar-iframe
                    ></iframe>
                </div>
                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 px-4 py-3">
                    <p class="text-xs text-slate-500">Checkout secured by Selar.</p>
                    <a href="#" class="text-sm font-semibold text-brand-700 underline" data-selar-direct-link>
                        Continue on Selar
                    </a>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);

    const iframe = overlay.querySelector('[data-selar-iframe]');
    const loading = overlay.querySelector('[data-selar-loading]');
    const directLink = overlay.querySelector('[data-selar-direct-link]');
    const closeButton = overlay.querySelector('[data-selar-close]');

    const close = () => {
        if (overlay.dataset.selarTimeoutId) {
            window.clearTimeout(Number(overlay.dataset.selarTimeoutId));
            delete overlay.dataset.selarTimeoutId;
        }

        if (overlay.__selarActiveButton) {
            setSelarButtonLoading(overlay.__selarActiveButton, false);
            overlay.__selarActiveButton = null;
        }

        overlay.classList.add('hidden');
        iframe.classList.add('hidden');
        iframe.onload = null;
        iframe.removeAttribute('src');
        loading.classList.remove('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    overlay.addEventListener('click', (event) => {
        if (event.target === overlay) {
            close();
        }
    });

    closeButton.addEventListener('click', close);
    directLink.addEventListener('click', close);

    selarModalElements = { overlay, iframe, loading, directLink, close };

    return selarModalElements;
}

function appendTrackingParams(url, dataset) {
    const parsedUrl = new URL(url, window.location.origin);
    const currentUrl = new URL(window.location.href);

    SELAR_UTM_KEYS.forEach((key) => {
        const currentValue = currentUrl.searchParams.get(key);
        if (currentValue && !parsedUrl.searchParams.has(key)) {
            parsedUrl.searchParams.set(key, currentValue);
        }
    });

    if (dataset.selarCampaign) {
        parsedUrl.searchParams.set('utm_campaign', dataset.selarCampaign);
    }

    if (dataset.selarContent) {
        parsedUrl.searchParams.set('utm_content', dataset.selarContent);
    }

    if (!parsedUrl.searchParams.has('utm_source')) {
        parsedUrl.searchParams.set('utm_source', 'genchess.ng');
    }

    if (!parsedUrl.searchParams.has('utm_medium')) {
        parsedUrl.searchParams.set('utm_medium', 'website');
    }

    parsedUrl.searchParams.set('origin', 'genchess.ng');

    if (dataset.selarSuccessUrl && !parsedUrl.searchParams.has('redirect_url')) {
        parsedUrl.searchParams.set('redirect_url', dataset.selarSuccessUrl);
    }

    return parsedUrl.toString();
}

function setSelarButtonLoading(button, isLoading) {
    const label = button.querySelector('[data-selar-label]');
    if (!label) {
        return;
    }

    if (isLoading) {
        button.dataset.originalLabel = label.textContent;
        label.textContent = 'Opening checkout...';
        button.disabled = true;
        button.classList.add('opacity-80', 'cursor-wait');
        return;
    }

    label.textContent = button.dataset.originalLabel || label.textContent;
    button.disabled = false;
    button.classList.remove('opacity-80', 'cursor-wait');
}

function redirectToSelar(url) {
    window.location.assign(url);
}

function openSelarEmbed(button, url) {
    const modal = ensureSelarModal();
    let loaded = false;

    modal.overlay.classList.remove('hidden');
    modal.loading.classList.remove('hidden');
    modal.iframe.classList.add('hidden');
    modal.directLink.href = url;
    document.body.classList.add('overflow-hidden');
    modal.overlay.__selarActiveButton = button;

    const timeout = window.setTimeout(() => {
        if (!loaded) {
            modal.close();
            redirectToSelar(url);
        }
    }, 4500);
    modal.overlay.dataset.selarTimeoutId = String(timeout);

    modal.iframe.onload = () => {
        loaded = true;
        window.clearTimeout(timeout);
        delete modal.overlay.dataset.selarTimeoutId;
        modal.loading.classList.add('hidden');
        modal.iframe.classList.remove('hidden');
        setSelarButtonLoading(button, false);
        modal.overlay.__selarActiveButton = null;
    };

    modal.iframe.src = url;
}

function initSelarEnrollButtons() {
    document.addEventListener('click', (event) => {
        const button = event.target.closest('[data-selar-enroll]');
        if (!button) {
            return;
        }

        event.preventDefault();

        const mode = (button.dataset.selarMode || 'redirect').toLowerCase();
        const targetUrl = appendTrackingParams(button.dataset.selarUrl, button.dataset);

        setSelarButtonLoading(button, true);

        if (mode === 'embed') {
            openSelarEmbed(button, targetUrl);
            return;
        }

        redirectToSelar(targetUrl);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSelarEnrollButtons);
} else {
    initSelarEnrollButtons();
}
