// Dexter Auto service worker: shows booking alerts, even when the site is closed.

self.addEventListener('install', () => self.skipWaiting());
self.addEventListener('activate', (event) => event.waitUntil(self.clients.claim()));

self.addEventListener('push', (event) => {
    let data = {};
    try {
        data = event.data ? event.data.json() : {};
    } catch (e) {
        data = { title: 'Dexter Auto', body: event.data ? event.data.text() : '' };
    }

    const title = data.title || 'Dexter Auto';
    const options = {
        body: data.body || '',
        icon: data.icon || '/images/shop.png',
        badge: data.badge || '/images/shop.png',
        tag: data.tag,
        renotify: !!data.renotify,
        data: data.data || {},
        vibrate: [120, 60, 120],
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    // Only open pages on this site
    let url = '/';
    try {
        const target = new URL(event.notification.data?.url || '/', self.location.origin);
        if (target.origin === self.location.origin) url = target.href;
    } catch (e) {}

    event.waitUntil((async () => {
        const windows = await self.clients.matchAll({ type: 'window', includeUncontrolled: true });
        for (const client of windows) {
            if (new URL(client.url).origin === self.location.origin && 'focus' in client) {
                await client.focus();
                return client.navigate ? client.navigate(url) : undefined;
            }
        }
        return self.clients.openWindow(url);
    })());
});
