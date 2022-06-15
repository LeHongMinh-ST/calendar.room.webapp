<template>
  <div>
    Hello
  </div>
</template>

<script>
import {mapMutations} from "vuex";

export default {
  name: "Request",
  methods: {
    ...mapMutations('home', [
      'changeTitle',
      'setActiveMenu'
    ])
  },
  mounted() {
    this.changeTitle('Danh sách yêu cầu')
    this.setActiveMenu(2)
  }
}
</script>

<style scoped>

</style>
