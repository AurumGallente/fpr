import './bootstrap';

import 'bootstrap';

import Alpine from 'alpinejs';

import { createApp } from 'vue';

window.Alpine = Alpine;

Alpine.start();


createApp(app).mount('#app');
