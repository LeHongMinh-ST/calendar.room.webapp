<template>
  <div>
    <AdminLayout v-if="isAuthenticated"/>
    <AuthLayout v-else/>
      <Loader/>
  </div>
</template>

<script>

import AdminLayout from "../layouts/AdminLayout";
import AuthLayout from "../layouts/AuthLayout";
import Loader from "./partials/Loader";

import {mapState} from 'vuex'

export default {
    name: "App",
    components: {
        AdminLayout,
        AuthLayout,
        Loader
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
