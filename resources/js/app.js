import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import router from "./router"
import store from './store'

require('./bootstrap')
window.Vue = require('vue').default
Vue.component('app', require('./components/App.vue').default)
Vue.use(Vuetify)

const vuetify = new Vuetify({
    icons: {
        iconfont: ['mdiSvg'],
    },
})

const app = new Vue({
    el: '#app',
    vuetify: vuetify,
    router,
    store
});
