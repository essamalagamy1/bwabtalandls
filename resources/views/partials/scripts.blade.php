@yield('script')
<script src="{{ asset('js/pwa.js') }}"></script>
<script>
    // تحميل الصوت مسبقاً لمنع التأخير
    let notificationAudio = null;
    document.addEventListener('DOMContentLoaded', () => {
        notificationAudio = new Audio('/sounds/notification.mp3');
        notificationAudio.load();
        
        let deferredPrompt;
        const installContainer = document.getElementById('install-container');
        const installButton = document.getElementById('install-button');

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            if (installContainer) {
                installContainer.classList.remove('hidden');
            }
        });

        if (installButton) {
            installButton.addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
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
