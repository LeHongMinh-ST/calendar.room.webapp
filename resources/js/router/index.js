import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

const routes = [
    {
        path: '/home',
        name: 'Home',
        component: () => import('../pages/HomePage.vue')
    },
    {
        path: '/',
        redirect: { name: 'Home' }
    },
    {
        path: '/about',
        name: 'About',
        component: () => import('../pages/AboutPage.vue')
    },
]

const router = new Router({
    mode: 'history',
    base: process.env.APP_URL,
    routes
})

// router.beforeEach((to, from, next) => {
//     if (to.name !== 'Login' && !store.state.auth.isAuthenticated) {
//         next({ name: 'Login' })
//     } else if(to.name === 'Login' && store.state.auth.isAuthenticated) {
//         next({ name: 'Home' })
//     } else {
//         next()
//     }
//     store.state.home.prevRoute = from
// })

export default router