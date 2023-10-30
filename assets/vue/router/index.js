import Home from '../views/Home';
import Users from '../views/Users';
import Login from '../views/Login';
import store from '../store';
import { createRouter, createWebHashHistory } from 'vue-router'

const routes = [
    { path: '/home', component: Home },
    { path: '/users', component: Users, meta: { requiresAuth: true } },
    { path: "/login", component: Login },
    { path: '/:pathMatch(.*)*', redirect: '/home' }
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (store.getters["security/isAuthenticated"]) {
            next();
        } else {
            next({
                path: "/login",
                query: { redirect: to.fullPath }
            });
        }
    } else {
        next(); // make sure to always call next()!
    }
});

export default router;
