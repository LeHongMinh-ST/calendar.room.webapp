import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from "vuex-persistedstate";
import auth from './modules/auth'
import home from './modules/home'
import schedule from './modules/schedule'


Vue.use(Vuex)

const store = new Vuex.Store({
    modules: {
        auth,
        schedule,
        home
    },
    plugins: [createPersistedState({paths: ['auth']})]
})

export default store
