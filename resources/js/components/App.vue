<template>
  <div>
    <AdminLayout v-if="isAuthenticated"/>
    <AuthLayout v-else/>
  </div>
</template>

<script>

import AdminLayout from "../layouts/AdminLayout";
import AuthLayout from "../layouts/AuthLayout";
import {mapState} from 'vuex'

export default {
  name: "App",
  components: {
    AdminLayout,
    AuthLayout
  },
  computed: {
    ...mapState('auth', [
        'isAuthenticated'
    ])
  }
}
</script>

<style scoped>

</style>
