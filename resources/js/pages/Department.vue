<template>

</template>

<script>
import {mapMutations} from "vuex";

export default {
    name: "Department",
    methods: {
        ...mapMutations('home', [
            'changeTitle',
            'setActiveMenu'
        ])
    },
    mounted() {
        this.changeTitle('Quản lý khoa - bộ môn')
        this.setActiveMenu(1)
    }
}
</script>

<style scoped>

</style>
