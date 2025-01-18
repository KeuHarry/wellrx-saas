import './assets/css/global.scss';

import { createApp } from 'vue';
import { createPinia } from 'pinia';

import App from './App.vue';
import router from './router';
import { useSetupAxios } from './composables/api/useAxiosTenant';

const app = createApp(App);

app.use(createPinia());
app.use(router);
useSetupAxios();

app.mount('#app');
