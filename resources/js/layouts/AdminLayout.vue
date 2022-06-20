<template>
  <v-app id="inspire">
    <v-navigation-drawer
        v-model="drawer"
        app
        class="mainDrawer"
    >
      <div class="logoWrapper">
        <div class="logo">
          <a href="">
            <img src="/images/FITA.png" alt="">
          </a>
        </div>
      </div>
      <v-list
          nav
      >
        <v-list-item-group :value="activeMenu">
          <v-list-item link to="/dashboard">
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiViewDashboard }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Bảng điều khiển
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item-subtitle>
            Quản lý chung
          </v-list-item-subtitle>
          <v-list-item link to="/faculty">
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiBank }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Quản lý khoa - bộ môn
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiViewWeek }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Quản lý học kỳ - tuần
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiBookOpenVariant }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Quản lý môn học
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiLaptop }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Quản lý phòng máy
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item-subtitle>
            Thời khóa biểu
          </v-list-item-subtitle>
          <v-list-item link >
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiCalendarCheck }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Xác nhận yêu cầu
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link to="/schedules">
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiCalendarText }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Danh sách yêu cầu
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link to="/calendar">
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiCalendarMonth }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Thời khóa biểu
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item-subtitle>
            Người dùng
          </v-list-item-subtitle>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiAccount }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Quản lý người dùng
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiAccountMultipleCheck }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Phân công lịch trực
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiMessageAlert }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Phản ánh
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>

          <v-list-item-subtitle>
            Báo cáo thông kê
          </v-list-item-subtitle>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiChartBar }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Thống kê số tiết học TH
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiFileChartOutline }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Thống kê số nhóm học TH
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item-subtitle>
            Hệ thống
          </v-list-item-subtitle>
          <v-list-item link>
            <v-list-item-icon>
              <v-icon>
                {{ icon.mdiLock }}
              </v-icon>
            </v-list-item-icon>
            <v-list-item-content>
              <v-list-item-title>
                Vai trò
              </v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>

      </v-list>
    </v-navigation-drawer>

    <v-app-bar app color="#0F74BB">
      <v-app-bar-nav-icon @click="drawer = !drawer" color="white"/>

      <v-toolbar-title>
        <div class="titlePage">{{ title }}</div>
      </v-toolbar-title>
      <v-spacer></v-spacer>

      <v-menu offset-y>
        <template v-slot:activator="{ on, attrs }">
          <v-avatar
              color="primary"
              size="48"
              v-bind="attrs"
              v-on="on"
          >
            <img
                src="https://cdn.vuetifyjs.com/images/john.jpg"
                alt="John"
            >
          </v-avatar>
        </template>
        <v-list>
          <v-list-item>
            <v-list-item-title>
              <span>Thông tin</span>
            </v-list-item-title>
          </v-list-item>
          <v-list-item @click="logout">
            <v-list-item-title>
              <span> Đăng xuất</span>
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>

    <v-main app class="main">
      <router-view/>
    </v-main>
  </v-app>
</template>

<script>

import {
  mdiViewDashboard,
  mdiBank,
  mdiViewWeek,
  mdiBookOpenVariant,
  mdiLaptop,
  mdiCalendarMonth,
  mdiCalendarText,
  mdiCalendarCheck,
  mdiAccount,
  mdiAccountMultipleCheck,
  mdiMessageAlert,
  mdiChartBar,
  mdiFileChartOutline,
  mdiLock,
} from '@mdi/js'

import {mapState, mapActions} from 'vuex'

export default {
  name: 'AdminLayout',
  computed: {
    ...mapState('home', [
      'title',
      'activeMenu'
    ]),
  },
  data: () => ({
    drawer: null,
    icon: {
      mdiViewDashboard,
      mdiBank,
      mdiViewWeek,
      mdiBookOpenVariant,
      mdiLaptop,
      mdiCalendarMonth,
      mdiCalendarText,
      mdiCalendarCheck,
      mdiAccount,
      mdiAccountMultipleCheck,
      mdiMessageAlert,
      mdiChartBar,
      mdiFileChartOutline,
      mdiLock,
    },

  }),
  methods: {
    ...mapActions('auth', ['logout']),
  }
}
</script>

<style scoped lang="scss">
.logoWrapper {
  .logo {
    text-align: center;
    padding: 20px;

    a {

      img {
        width: 100px;
      }
    }
  }
}

.titlePage {
  color: white;
}

.main {
  background-color: #F3F5F8;
}

.v-list-item__title {
  white-space: normal;
}

a {
  text-decoration: none
}
.v-navigation-drawer__content::-webkit-scrollbar {
  width: 6px;
  background-color: #F5F5F5;
}
</style>
