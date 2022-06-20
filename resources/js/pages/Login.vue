<template>
  <v-container>
    <v-row align="center" justify="center">
      <v-col cols="12" sm="10">
        <v-card class="elevation-6 mt-10">
          <v-row>
            <v-col cols="12" md="6">
              <v-card-text class="mt-12">
                <div class="logoWrapper">
                  <div class="logo">
                    <a href="">
                      <img src="/images/FITA.png" alt="">
                    </a>
                  </div>
                </div>
                <h4 class="text-center">Đăng nhập</h4>
                <v-row align="center" justify="center">
                  <v-col cols="12" sm="8">
                    <v-text-field
                        v-model="userName"
                        :error-messages="userNameErrors"
                        label="Tài khoản"
                        outlined
                        dense
                        color="blue"
                        autocomplete="false"
                        class="mt-5"
                        @input="$v.userName.$touch()"
                        @blur="$v.userName.$touch()"
                    />
                    <v-text-field
                        v-model="password"
                        :error-messages="passwordErrors"
                        label="Mật khẩu"
                        outlined
                        dense
                        color="blue"
                        autocomplete="false"
                        type="password"
                        class="mt-2"
                        @input="$v.password.$touch()"
                        @blur="$v.password.$touch()"
                    />

                    <v-btn color="blue" dark block tile @click="handleLogin">Đăng nhập</v-btn>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-col>
            <v-col cols="12" md="6">
              <div class="text-center mb-10">

                <div class="text-center">
                  <img class="loginImage" src="/images/login.jpg" alt="Login">
                </div>
              </div>
            </v-col>
          </v-row>
        </v-card>
      </v-col>
    </v-row>
    <v-snackbar
        :value="snackbar.isShow"
        :timeout="2000"
        absolute
        top
        :color="snackbar.color"
        right
    >
      {{ snackbar.message }}
      <template v-slot:action="{ attrs }">
        <v-btn
            color="white"
            text
            v-bind="attrs"
            @click="snackbar.isShow = false"
        >
          X
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>

<script>
import {mapMutations, mapState} from "vuex"
import api from "./../api"
import _ from "lodash"
import {required} from 'vuelidate/lib/validators'

export default {
  name: "Login",
  data: () => ({
    userName: '',
    password: '',
    snackbar: {
      isShow: false,
      color: 'success',
      message: '',
    }
  }),
  validations: {
    userName: {
      required
    },
    password: {
      required
    },
  },
  mounted() {
  },
  computed: {
    ...mapState("auth", ["isAuthenticated", "authUser"]),
    userNameErrors() {
      const errors = []
      if (!this.$v.userName.$dirty) return errors
      !this.$v.userName.required && errors.push('Tài khoản không được bỏ trống')
      return errors
    },
    passwordErrors() {
      const errors = []
      if (!this.$v.password.$dirty) return errors
      !this.$v.password.required && errors.push('Mật khẩu không được bỏ trống')
      return errors
    },
  },

  methods: {
      ...mapMutations("auth", ["updateLoginStatus", "updateAccessToken", "updateAuthUser"]),
      ...mapMutations("home", ["setLoader"]),
      handleLogin() {
          this.$v.$touch()
          if (!this.$v.$invalid) {
              this.setLoader(true)
              let data = {
                  user_name: this.userName,
                  password: this.password,
              }
              api.login(data).then(async (response) => {
                  if (response) {
                      this.updateAccessToken(_.get(response, "data.access_token"));
                      this.updateLoginStatus(true);

                      await this.getAuthUser().then((auth) => {
                          this.setLoader(false)
                          if (_.get(auth, 'role_id') === 0) {
                              this.$router.push({name: 'Calendar'})
                          }

                          if (_.get(auth, 'role_id') === 1) {
                              this.$router.push({name: 'Dashboard'})
                          }
                      })
                  }
                  this.setLoader(false)
              }).catch(() => {
                  this.snackbar = {
                      message: "Thông tin tài khoản hoặc mật khẩu không chính xác",
                      color: "error",
                      isShow: true,
                  }
                  this.setLoader(false)
              });
      }
    },
    async getAuthUser() {
      let auth = {}
      await api.getAuthUser().then((res) => {
        this.updateAuthUser(_.get(res, 'data'))
        auth = _.get(res, 'data');
      })
      return auth
    }
  }
}
</script>

<style scoped lang="scss">
.logoWrapper {
  .logo {
    text-align: center;
    padding: 0px 20px 20px 20px;

    a {

      img {
        width: 100px;
      }
    }
  }
}

.loginImage {
  width: 100%;
}
</style>
