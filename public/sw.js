'use strict';

// --- >> الجزء الخاص بـ PWA (الكاشينج) << ---
const CACHE_NAME = 'app-cache-v1';
const urlsToCache = [
    '/',
    '/offline.html',
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(urlsToCache))
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(cacheName => {
                    if (cacheWhitelist.indexOf(cacheName) === -1) return caches.delete(cacheName);
                })
            );
        })
    );
});

self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') return;
    event.respondWith(
        caches.match(event.request).then(cachedResponse => {
            if (cachedResponse) return cachedResponse;
            return fetch(event.request).catch(() => caches.match('/offline.html'));
        })
    );
});


// --- >> الجزء الخاص بالإشعارات (Web Push) << ---
self.addEventListener('push', function (event) {
    const data = event.data.json();
    const options = {
        body: data.body,
        icon: data.icon,
        badge: data.badge,
        vibrate: data.vibrate || [200, 100, 200],
        tag: data.tag,
        requireInteraction: data.requireInteraction,
        actions: data.actions,
        lang: data.lang,
        dir: data.dir,
        silent: false,
        data: {
            url: data.data?.url,
            sound: data.data?.sound || '/sounds/notification.mp3'
        }
    };

    event.waitUntil(
        Promise.all([
            self.registration.showNotification(data.title, options),
            // إرسال رسالة لواجهة المستخدم لتشغيل الصوت
            clients.matchAll({ includeUncontrolled: true, type: 'window' }).then(windowClients => {
                windowClients.forEach(client => {
                    client.postMessage({
                        type: 'PLAY_NOTIFICATION_SOUND',
                        soundUrl: data.data?.sound || '/sounds/notification.mp3'
                    });
                });
            })
        ])
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url)
    );
});
