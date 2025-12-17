import './bootstrap';

import Alpine from 'alpinejs';

import collapse from '@alpinejs/collapse'; // Importar el plugin
Alpine.plugin(collapse);

window.Alpine = Alpine;

Alpine.start();
