import '../css/app.css';
import '../css/dashboard.css'; // ✅ CSS الخاص بالداشبورد

// Cairo Font (Local)
import '@fontsource/cairo/200.css';
import '@fontsource/cairo/300.css';
import '@fontsource/cairo/400.css';
import '@fontsource/cairo/500.css';
import '@fontsource/cairo/600.css';
import '@fontsource/cairo/700.css';
import '@fontsource/cairo/800.css';
import '@fontsource/cairo/900.css';

// Font Awesome
import '@fortawesome/fontawesome-free/css/all.min.css';

// Boxicons
import 'boxicons/css/boxicons.min.css';

// Cropper.js
import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';
window.Cropper = Cropper;

// Sortable.js
import Sortable from 'sortablejs';
window.Sortable = Sortable;

// EasyMDE
import EasyMDE from 'easymde';
import 'easymde/dist/easymde.min.css';
window.EasyMDE = EasyMDE;

// intl-tel-input
import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';
window.intlTelInput = intlTelInput;

// AOS (Animate On Scroll)
import AOS from 'aos';
import 'aos/dist/aos.css';
window.AOS = AOS;

// Initialize AOS
document.addEventListener('DOMContentLoaded', () => {
    AOS.init();
});

// Re-initialize AOS after Livewire navigation
document.addEventListener('livewire:navigated', () => {
    AOS.refresh();
});

