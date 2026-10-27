// Phone / desktop alerts (Web Push). The service worker in /sw.js shows them even when the site is closed.

// Read fresh each time: Livewire's wire:navigate swaps pages (e.g. login → dashboard) without reloading this script
const vapidKey = () => document.querySelector('meta[name="vapid-public-key"]')?.content;
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content;
const OFF_FLAG = 'dexter-push-off';

const store = {
    get: (k) => { try { return localStorage.getItem(k); } catch (e) { return null; } },
    set: (k, v) => { try { v === null ? localStorage.removeItem(k) : localStorage.setItem(k, v); } catch (e) {} },
};

const supported = () => !!vapidKey() &&'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;
const isIos = () => /iPad|iPhone|iPod/.test(navigator.userAgent) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);

function keyBytes(base64) {
    const padded = (base64 + '='.repeat((4 - (base64.length % 4)) % 4)).replace(/-/g, '+').replace(/_/g, '/');
    return Uint8Array.from(atob(padded), (c) => c.charCodeAt(0));
}

async function post(url, body) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf() },
        credentials: 'same-origin',
        body: JSON.stringify(body),
    });
    if (!res.ok) throw new Error('Push request failed: ' + res.status);
}

const payload = (sub) => ({
    ...sub.toJSON(),
    contentEncoding: (PushManager.supportedContentEncodings || ['aes128gcm'])[0] === 'aesgcm' ? 'aesgcm' : 'aes128gcm',
});

async function subscription(create) {
    const reg = await navigator.serviceWorker.register('/sw.js');
    await navigator.serviceWorker.ready;
    let sub = await reg.pushManager.getSubscription();
    if (!sub && create) {
        sub = await reg.pushManager.subscribe({ userVisibleOnly: true, applicationServerKey: keyBytes(vapidKey()) });
    }
    return sub;
}

window.dexterPush = {
    /** 'on' | 'off' | 'blocked' | 'ios-install' | 'unsupported' */
    state() {
        if (!supported()) return isIos() && !navigator.standalone ? 'ios-install' : 'unsupported';
        if (Notification.permission === 'denied') return 'blocked';
        if (Notification.permission === 'granted' && store.get(OFF_FLAG) !== '1') return 'on';
        return 'off';
    },

    async enable() {
        if (!supported()) return this.state();
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') return this.state();
        try {
            const sub = await subscription(true);
            await post('/push/subscribe', payload(sub));
            store.set(OFF_FLAG, null);
            return 'on';
        } catch (e) {
            console.error(e);
            return 'off';
        }
    },

    async disable() {
        try {
            const sub = await subscription(false);
            if (sub) {
                await post('/push/unsubscribe', { endpoint: sub.endpoint });
                await sub.unsubscribe();
            }
        } catch (e) {
            console.error(e);
        }
        store.set(OFF_FLAG, '1');
        return this.state();
    },
};

// Signed-in pages: if this device already allowed alerts, make sure it's linked to whoever is signed in now
// (e.g. after logging back in, or after the browser renewed its subscription).
function syncDevice() {
    if (supported() && document.querySelector('meta[name="vapid-public-key"][data-signed-in]') && window.dexterPush.state() === 'on') {
        subscription(true)
            .then((sub) => post('/push/subscribe', payload(sub)))
            .catch((e) => console.error(e));
    }
}
// Fires on the first page load and after every wire:navigate page change
document.addEventListener('livewire:navigated', syncDevice);
