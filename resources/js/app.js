import './bootstrap';

import {createApp} from 'vue';
import router from './router.js';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import App from '../js/App.vue';

const app = createApp(App);
app.use(router);
app.use(VueSweetalert2);
app.mount("#app");
