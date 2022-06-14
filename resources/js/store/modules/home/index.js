export default {
    namespaced: true,
    state: {
        title: 'Bảng điều khiển',
        activeMenu: 0
    },
    mutations: {
        changeTitle(state, data) {
            state.title = data
        },
        setActiveMenu(state, data) {
            state.activeMenu = data
        }
    },
}
