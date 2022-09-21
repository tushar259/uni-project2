import { createWebHistory, createRouter } from 'vue-router';
import home from '../js/pages/home.vue';
import notFound from '../js/pages/notFound.vue';
import Login from '../js/pages/login.vue';
import Registration from '../js/pages/registration.vue';
import MyUploads from '../js/pages/myUploads.vue';
import UserRequest from '../js/pages/userRequests.vue'

const routes = [
    {
        path : '/:pathMatch(.*)*',
        component : notFound
    },
    {
        path : '/',
        component : home
    },
    {
        path : '/login',
        component : Login
    },
    {
        path : '/registration',
        component : Registration
    },
    {
        path : '/myuploads',
        component : MyUploads
    },
    {
        path : '/userrequests',
        component : UserRequest
    },
    
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;

