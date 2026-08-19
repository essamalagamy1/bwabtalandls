@yield('script')
<script src="{{ asset('js/pwa.js') }}"></script>
<script>
(() => {
    // تحميل الصوت مسبقاً لمنع التأخير
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
