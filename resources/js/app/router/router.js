import { createRouter, createWebHistory } from "vue-router";

import authLayout from "../auth/layout/layout.vue";
import login from "../auth/pages/login.vue";
import registration from "../auth/pages/registration.vue";
import forgot from "../auth/pages/forgot.vue";
import reset from "../auth/pages/reset.vue";
import verification from "../auth/pages/verification.vue";

import portalLayout from "../portal/layout/layout.vue";
import index from "../portal/pages/index.vue";

const title = "Chat App - ";

const routes = [
    {
        path: '/auth', name: 'authLayout', component: authLayout,
        meta: { requiresGuest: true },
        children: [
            { path: 'login', name: 'login', component: login, meta: { title: title + 'Login' } },
            { path: 'registration', name: 'registration', component: registration, meta: { title: title + 'Registration' } },
            { path: 'forgot', name: 'forgot', component: forgot, meta: { title: title + 'Forgot' } },
            { path: 'reset', name: 'reset', component: reset, meta: { title: title + 'Reset' } },
            { path: 'verification', name: 'verification', component: verification, meta: { title: title + 'Verification' } },
        ]
    },
    {
        path: '/', name: 'portalLayout', component: portalLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', name: 'index', component: index, meta: { title: title + 'Chat' } },
        ]
    },
];

const router = createRouter({
    history: createWebHistory(), routes,
    scrollBehavior(to, from, savedPosition) {
        if (to.hash) {
            return { el: to.hash, behavior: 'smooth' };
        } else {
            return { top: 0, behavior: 'smooth' };
        }
    }
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token');
    if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
        next({ name: 'login' });
    } else if (to.matched.some(record => record.meta.requiresGuest) && isAuthenticated) {
        next({ name: 'index' });
    } else {
        next();
    }
});

export default router;