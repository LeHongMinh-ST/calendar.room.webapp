<template>
    <v-container fluid>
        <v-card>
            <div>Hellllo</div>
        </v-card>
    </v-container>
</template>

<script>

import {mapMutations} from 'vuex'

export default {
    name: 'Dashboard',
    methods: {
        ...mapMutations('home', [
            'changeTitle',
            'setActiveMenu'
        ])
    },
    mounted() {
        this.changeTitle('Bảng điều khiển')
        this.setActiveMenu(1)
    }
}
</script>

<style scoped>

</style>
