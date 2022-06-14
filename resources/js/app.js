import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css'
import router from "./router"
require('./bootstrap')
window.Vue = require('vue').default
Vue.component('app', require('./components/App.vue').default)

const app = new Vue({
    el: '#app',
    vuetify: new Vuetify(),
    router
});
