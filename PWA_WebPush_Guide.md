# Web Push Notifications Implementation Guide (WebPushChannel)

## Overview
This guide covers the implementation of Web Push Notifications in a Laravel application using the `NotificationChannels\WebPush` package, along with a robust Service Worker (PWA) setup that safely handles dynamic applications without causing caching loops.

## 1. Prerequisites
- Install the required package: `composer require laravel-notification-channels/webpush`
- Publish the configuration and migrations:
  ```bash
  php artisan vendor:publish --provider="NotificationChannels\WebPush\WebPushServiceProvider" --tag="migrations"
  php artisan vendor:publish --provider="NotificationChannels\WebPush\WebPushServiceProvider" --tag="config"
  php artisan migrate
  ```
- Generate VAPID keys:
  ```bash
  php artisan webpush:vapid
  ```

## 2. Model Configuration
Add the `HasPushSubscriptions` trait to the user model:
```php
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    use HasPushSubscriptions;
    // ...
}
```

## 3. Notification Class
Create a notification class that implements the `WebPushMessage` logic:

```php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class GeneralNotification extends Notification
{
    use Queueable;

    public $title;
    public $body;
    public $url;

    public function __construct($title, $body, $url = '/')
    {
        $this->title = $title;
        $this->body = $body;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['database', WebPushChannel::class];
    }

    public function toWebPush($notifiable, $notification)
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->body)
            ->action('عرض التفاصيل', $this->url)
            ->data(['url' => $this->url])
            ->icon('/logo.png')
            ->badge('/favicon.svg')
            ->vibrate([100, 50, 100])
            ->tag('notification-tag')
            ->options(['TTL' => 1000])
            ->dir('rtl')
            ->lang('ar')
            ->requireInteraction();
    }
}
```

## 4. السكريبت المسؤول عن Service Worker والاشتراكات (Frontend JS)
في ملف `public/js/pwa.js` (يجب تغليفه بـ IIFE `(() => { ... })();` لمنع تعارض المتغيرات في تطبيقات الـ SPA مثل Livewire):

```javascript
(() => {
// 1. تسجيل الـ Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/sw.js').then(function (registration) {
        console.log('Service Worker registered successfully.');
    }).catch(function (error) {
        console.log('Service Worker registration failed:', error);
    });
}

// 2. طلب إذن الإشعارات أوتوماتيك
if ('Notification' in window) {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            subscribeUserToPush();
        }
    });
}

// 3. ربط التفعيل بزر الإشعارات (الجرس) في الـ Navbar
const notificationBell = document.getElementById('notification-bell');
const vapidMeta = document.head.querySelector('meta[name="vapid-public-key"]');
const VAPID_PUBLIC_KEY = vapidMeta ? vapidMeta.content : '';

if (notificationBell) {
    notificationBell.addEventListener('click', function () {
        if ('Notification' in window && 'serviceWorker' in navigator && Notification.permission !== 'granted') {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    subscribeUserToPush();
                }
            });
        }
    });
}

// 4. الاشتراك في Push Notifications
function subscribeUserToPush() {
    navigator.serviceWorker.ready.then(registration => {
        const subscribeOptions = {
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY)
        };
        return registration.pushManager.subscribe(subscribeOptions);
    }).then(pushSubscription => {
        sendSubscriptionToBackEnd(pushSubscription);
        return pushSubscription;
    });
}

// 5. إرسال بيانات الاشتراك للـ Backend
function sendSubscriptionToBackEnd(subscription) {
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

    return fetch('/save-subscription', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(subscription)
    }).then(response => {
        if (!response.ok) throw new Error('Bad status code from server.');
        return response.json();
    }).then(responseData => {
        if (!(responseData.success)) throw new Error('Bad response from server.');
        console.log('Subscription saved on backend successfully.');
    }).catch(error => {
        console.error('Error sending subscription to backend:', error);
    });
}

// 6. دالة مساعدة لتحويل VAPID Key
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
})();
```

## 5. ملف Service Worker (`sw.js`)
هذا الملف يجب أن يكون في المسار `public/sw.js`. تم تحديثه لاستخدام استراتيجية **Network Only with Offline Fallback** لتجنب أخطاء `ERR_FAILED` وتعارض الجلسات (Sessions/CSRF) التي تحدث عند عمل Cache كامل للتطبيقات الديناميكية.

```javascript
'use strict';

// --- >> الجزء الخاص بـ PWA (الكاشينج) << ---
const CACHE_NAME = 'app-cache-v2';
const urlsToCache = [
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
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    // تفعيل السيرفيس وركر فورا لجميع الصفحات المفتوحة
    return self.clients.claim();
});

self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') return;
    
    // تجاهل الطلبات التي لا تبدأ بـ http/https (مثل إضافات المتصفح)
    if (!event.request.url.startsWith('http')) return;

    event.respondWith(
        fetch(event.request).catch(() => {
            // في حالة انقطاع الإنترنت وكان الطلب لصفحة HTML، نعرض صفحة offline
            if (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html')) {
                return caches.match('/offline.html');
            }
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
```

## 6. تفعيل الصوت وزر تثبيت التطبيق وتجنب تعارض Livewire
نظراً لأن Livewire يستخدم تقنية SPA (Single Page Application) للـ Navigation، قد يتم تنفيذ السكريبتات أكثر من مرة. لتجنب الأخطاء، نستخدم نمط `IIFE` المتصل بكائن `window` في `resources/views/partials/scripts.blade.php`:

```html
<script src="{{ asset('js/pwa.js') }}"></script>
<script>
(() => {
    // تحميل الصوت مسبقاً لمنع التأخير (وربطه بكائن window لتجنب إعادة التعريف)
    if (!window.notificationAudio) {
        window.notificationAudio = new Audio('/sounds/notification.mp3');
        window.notificationAudio.load();
    }

    let deferredPrompt;

    document.addEventListener('DOMContentLoaded', () => {
        const installContainers = document.querySelectorAll('.pwa-install-container');
        const installButtons = document.querySelectorAll('.pwa-install-button');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            installContainers.forEach(container => {
                container.classList.remove('hidden');
            });
        });

        installButtons.forEach(button => {
            button.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log(`User response to the install prompt: ${outcome}`);
                    deferredPrompt = null;
                    installContainers.forEach(container => {
                        container.classList.add('hidden');
                    });
                }
            });
        });
    });

    // إضافة المستمع لمرة واحدة فقط باستخدام window
    if ('serviceWorker' in navigator && !window.swMessageListenerAdded) {
        window.swMessageListenerAdded = true;
        navigator.serviceWorker.addEventListener('message', (event) => {
            if (event.data && event.data.type === 'PLAY_NOTIFICATION_SOUND') {
                if (window.notificationAudio) {
                    window.notificationAudio.currentTime = 0;
                    window.notificationAudio.play().catch(error => {
                        console.error('Error playing sound:', error);
                        new Audio(event.data.soundUrl).play();
                    });
                }
            }
        });
    }
})();
</script>
```

## 7. ضبط إعدادات الـ Cron Jobs للـ Queue في الاستضافة
حتى تعمل الإشعارات بالخلفية بدون تأخير (عبر `Queueable`)، يفضل استخدام إضافة سطر في ملف `routes/console.php` ليقوم بمعالجة الطابور كجزء من المجدول التلقائي للمهام:

```php
use Illuminate\Support\Facades\Schedule;

// إضافة أمر تشغيل قائمة الانتظار للعمل كل دقيقة وعدم التداخل
Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();
```

ثم من لوحة التحكم (مثل cPanel)، يتم إنشاء Cron Job ليعمل كل دقيقة (`* * * * *`) لتنفيذ الأمر التالي:

```bash
/usr/bin/php /home/path_to_your_project/artisan schedule:run >> /dev/null 2>&1
```

*(استبدل `/usr/bin/php` بمسار نسخة الـ PHP المستخدمة و `path_to_your_project` بالمسار الفعلي لمشروعك).*
