// app as vue with vue-router as single page application setup

import app from './app.vue';
import {createApp} from 'vue';
import router from './router/router.js';
createApp(app).use(router).mount('#app')
