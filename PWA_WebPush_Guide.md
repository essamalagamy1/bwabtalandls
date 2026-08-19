# دليل إعداد PWA وإشعارات Web Push في مشاريع Laravel

هذا الملف يحتوي على الدليل الشامل والمفصل لكيفية بناء **Progressive Web App (PWA)** ودمج نظام **إشعارات الويب (Web Push Notifications)** في أي مشروع Laravel بالاعتماد على الهيكلية المستخدمة في تطبيقك. يمكنك تزويد الذكاء الاصطناعي بهذا الملف في أي مشروع مستقبلي ليقوم بتوليد الكود اللازم بشكل مطابق.

---

## الجزء الأول: Progressive Web App (PWA)

لتحويل الموقع إلى تطبيق ويب (PWA) يمكن تثبيته على الأجهزة، نحتاج إلى ثلاثة عناصر أساسية: ملف الـ Manifest، الـ Service Worker (لإدارة الكاش والعمل بدون إنترنت)، وزر التثبيت في الواجهة.

### 1. ملف `manifest.json`
يجب إنشاء هذا الملف في مجلد `public/manifest.json`. هو المسؤول عن تعريف اسم التطبيق، أيقوناته، وألوانه عند تثبيته.

```json
{
  "name": "اسم التطبيق",
  "short_name": "اسم قصير",
  "start_url": "/dashboard",
  "display": "standalone",
  "background_color": "#FFFFFF",
  "theme_color": "#4A90E2",
  "description": "وصف التطبيق.",
  "icons": [
    {
      "src": "/logo.png",
      "sizes": "192x192",
      "type": "image/png"
    },
    {
      "src": "/logo.png",
      "sizes": "512x512",
      "type": "image/png"
    }
  ]
}
```

### 2. تضمين الـ Manifest في الـ Header
في ملف `resources/views/partials/head.blade.php` (أو أي ملف رئيسي للـ Header)، نضيف رابط الـ Manifest ومعلومات أخرى مهمة:

```html
<!-- في وسم الـ head -->
<link rel="apple-touch-icon" href="{{asset('logo.png')}}">
<link rel="manifest" href="{{ asset('manifest.json') }}">
<!-- نحتاج هذا الـ meta لاحقاً للإشعارات -->
<meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}">
```

### 3. زر تثبيت التطبيق وتهيئة الـ PWA
في ملف `resources/views/partials/scripts.blade.php` أو في نهاية ملف الـ Layout الرئيسي:

```html
<script>
    document.addEventListener('DOMContentLoaded', () => {
        let deferredPrompt;
        const installContainer = document.getElementById('install-container'); // قم بإنشاء هذا العنصر في الـ HTML ليحتوي على زر التثبيت
        const installButton = document.getElementById('install-button');

        window.addEventListener('beforeinstallprompt', (e) => {
            // منع المتصفح من إظهار الإشعار التلقائي
            e.preventDefault();
            // حفظ الحدث لاستخدامه لاحقًا
            deferredPrompt = e;
            // إظهار الزر المخصص
            if (installContainer) {
                installContainer.classList.remove('hidden');
            }
        });

        if (installButton) {
            installButton.addEventListener('click', async () => {
                if (deferredPrompt) {
                    // إظهار نافذة التثبيت
                    deferredPrompt.prompt();
                    // انتظار قرار المستخدم
                    const { outcome } = await deferredPrompt.userChoice;
                    console.log(`User response to the install prompt: ${outcome}`);
                    deferredPrompt = null;
                    if (installContainer) {
                        installContainer.classList.add('hidden');
                    }
                }
            });
        }
    });
</script>
```

---

## الجزء الثاني: إشعارات Web Push Notifications

نعتمد هنا على حزمة `laravel-notification-channels/webpush`.

### 1. التثبيت والإعداد في الـ Backend

**أ) تثبيت الحزمة وإعداد قاعدة البيانات:**
```bash
composer require laravel-notification-channels/webpush
php artisan vendor:publish --provider="NotificationChannels\WebPush\WebPushServiceProvider" --tag="migrations"
php artisan migrate
```

**ب) توليد مفاتيح VAPID:**
```bash
php artisan webpush:vapid
```
سيقوم هذا الأمر بإضافة المفاتيح في ملف `.env`:
```env
VAPID_PUBLIC_KEY=...
VAPID_PRIVATE_KEY=...
```

**ج) تجهيز موديل User:**
أضف الـ trait `HasPushSubscriptions` إلى موديل المستخدم:
```php
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable {
    use HasPushSubscriptions;
    // ...
}
```

### 2. تسجيل الاشتراك في الـ Backend

**أ) إنشاء Controller لحفظ بيانات الاشتراك:**
`app/Http/Controllers/NotificationManagerController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationManagerController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required',
        ]);

        $user = auth()->user();
        $user->updatePushSubscription($request->endpoint, $request->keys['p256dh'], $request->keys['auth']);

        return response()->json(['success' => true], 200);
    }
}
```

**ب) إضافة المسار Route:**
في `routes/web.php`:
```php
use App\Http\Controllers\NotificationManagerController;

Route::post('/save-subscription', NotificationManagerController::class)->name('save-subscription')->middleware('auth');
```

### 3. إنشاء كلاس الـ Notification
`app/Notifications/UserNotification.php`

```php
<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class UserNotification extends Notification
{
    use Queueable;

    public function __construct(public string $title, public string $body, public $url = null)
    {
        $this->url = $this->url ?: route('dashboard');
    }

    public function via(object $notifiable): array
    {
        return ['database', WebPushChannel::class]; // إرسال للإشعارات العادية والمتصفح
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->body($this->body)
            ->data(['url' => $this->url, 'sound' => '/sounds/notification.mp3'])
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

### 4. السكريبت المسؤول عن Service Worker والاشتراكات (Frontend JS)
في ملف `public/js/main.js` (أو أي ملف جافاسكريبت مجمع عندك):

```javascript
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
        } else if (permission === 'default') {
            // إظهار زر طلب الإشعار يدوياً
            const btn = document.getElementById('enable-notifications');
            if(btn) btn.style.display = 'block';
        }
    });
}

// 3. زر مخصص لتفعيل الإشعارات يدويًا
const enableNotificationsButton = document.getElementById('enable-notifications');
const vapidMeta = document.head.querySelector('meta[name="vapid-public-key"]');
const VAPID_PUBLIC_KEY = vapidMeta ? vapidMeta.content : '';

if(enableNotificationsButton) {
    enableNotificationsButton.addEventListener('click', function () {
        if ('Notification' in window && 'serviceWorker' in navigator) {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    subscribeUserToPush();
                    enableNotificationsButton.style.display = 'none';
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
```

### 5. ملف Service Worker (`sw.js`)
هذا الملف يجب أن يكون في المسار `public/sw.js`. وهو يجمع بين وظائف PWA (الكاش) ووظائف الإشعارات.

```javascript
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
```

### 6. تفعيل الصوت عند وصول إشعار
يتم ذلك عن طريق استقبال الرسالة التي أرسلها الـ `sw.js` في الواجهة وتشغيل الصوت.
في ملف السكريبتات الرئيسي (`resources/views/partials/scripts.blade.php`):

```html
<script>
    // تحميل الصوت مسبقاً لمنع التأخير
    let notificationAudio = null;
    document.addEventListener('DOMContentLoaded', () => {
        notificationAudio = new Audio('/sounds/notification.mp3');
        notificationAudio.load();
    });

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.addEventListener('message', (event) => {
            if (event.data && event.data.type === 'PLAY_NOTIFICATION_SOUND') {
                if (notificationAudio) {
                    notificationAudio.currentTime = 0;
                    notificationAudio.play().catch(error => {
                        console.error('Error playing sound:', error);
                        new Audio(event.data.soundUrl).play();
                    });
                }
            }
        });
    }
</script>
```

> **ملاحظة هامة**: تأكد من وجود الملفات التالية في مجلد `public`:
> - `offline.html` صفحة الخطأ في حال انقطاع الإنترنت.
> - ملفات الصوت للأشعارات `sounds/notification.mp3`.
> - أيقونات التطبيق `logo.png` و `favicon.svg`.
