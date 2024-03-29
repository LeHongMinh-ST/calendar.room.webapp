import Vue from 'vue'
import Router from 'vue-router'
import store from "../store";

Vue.use(Router)

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: () => import('../pages/Login.vue')
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('../pages/Dashboard.vue')
    },
    {
        path: '/',
        redirect: {name: 'Dashboard'}
    },
    {
        path: '/faculty',
        name: 'Faculty',
        component: () => import('../pages/Faculty.vue')
    },
    {
        path: '/calendar',
        name: 'Calendar',
        component: () => import('../pages/Calendar.vue')
    },
    {
        path: '/schedules',
        name: 'Schedules',
        component: () => import('../pages/Request.vue')
    },
    {
        path: '/semester',
        name: 'Semester',
        component: () => import('../pages/Semester.vue')
    },
    {
        path: '/subject',
        name: 'Subject',
        component: () => import('../pages/Subject.vue')
    },
    {
        path: '/room',
        name: 'Room',
        component: () => import('../pages/Room.vue')
    }
]

const router = new Router({
    mode: 'history',
    base: process.env.APP_URL,
    routes
})

router.beforeEach((to, from, next) => {
    if (to.name !== 'Login' && !store.state.auth.isAuthenticated) {
        next({name: 'Login'})
    } else if (to.name === 'Login' && store.state.auth.isAuthenticated && store.state.auth.authUser.role_id !== 1) {
        next({name: 'Calendar'})
    } else if (to.name === 'Login' && store.state.auth.isAuthenticated && store.state.auth.authUser.role_id === 1) {
        next({name: 'Dashboard'})
    } else {
        next()
    }
    store.state.home.prevRoute = from
})

export default router
