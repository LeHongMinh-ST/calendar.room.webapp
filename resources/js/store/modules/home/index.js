export default {
    namespaced: true,
    state: {
        title: 'Bảng điều khiển',
        activeMenu: 0,
        loader: false
    },
    mutations: {
        changeTitle(state, data) {
            state.title = data
        },
        setActiveMenu(state, data) {
            state.activeMenu = data
        },
        setLoader(state, data) {
            state.loader = data
        }
    },
}
