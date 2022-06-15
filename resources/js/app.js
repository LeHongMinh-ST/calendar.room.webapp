import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import router from "./router"
import store from './store'

require('./bootstrap')
window.Vue = require('vue').default
Vue.component('app', require('./components/App.vue').default)

import vi from 'vuetify/lib/locale/vi'
import Vuelidate from 'vuelidate'

Vue.use(Vuelidate)
Vue.use(Vuetify)

const vuetify = new Vuetify({
    icons: {
        iconfont: ['mdiSvg'],
    },
    lang: {
        locales: { vi },
        current: 'vi',
    },
})

const app = new Vue({
    el: '#app',
    vuetify: vuetify,
    router,
    store
});
