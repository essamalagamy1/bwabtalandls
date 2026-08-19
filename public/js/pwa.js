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
})();
