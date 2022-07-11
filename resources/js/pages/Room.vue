<template>
  <v-container class="py-8 px-6" fluid>
    <v-breadcrumbs :items="breadcrumbs" class="breadcrumbWrapper">
      <template v-slot:item="{ item }">
        <v-breadcrumbs-item
            :href="item.href"
            :disabled="item.disabled"
        >
          {{ item.text.toUpperCase() }}
        </v-breadcrumbs-item>
      </template>
    </v-breadcrumbs>
    <v-card class="mb-5">
      <div class="p-2 pt-5">
        <div class="row">
          <div class="col-md-4">
            <v-text-field
                class="mt-1"
                v-model="search"
                dense
                outlined
                placeholder="Nhập từ khóa để tìm kiếm..."
                clearable
                @click:clear=""
                @keydown.enter.native=""
            />
          </div>
          <div class="col-md-4">
            <v-btn
                large
                dark
                color="primary"
                @click=""
            >
              <v-icon left>
                {{ icon.mdiMagnify }}
              </v-icon>
              Tìm kiếm
            </v-btn>
          </div>
          <div class="col-md-4 text-end">
            <v-btn
                outlined
                large
                dark
                color="primary"
                @click="openDialogCreate"
            >
              <v-icon left>
                {{ icon.mdiPlus }}
              </v-icon>
              Thêm mới
            </v-btn>
          </div>
        </div>
      </div>
    </v-card>
    <v-card>
      <v-simple-table>
        <template v-slot:default>
          <thead>
          <tr>
            <th class="text-center" width="10%">
              STT
            </th>
            <th class="text-left" width="15%">
              Mã phòng
            </th>
            <th class="text-left">
              Tên phòng
            </th>
            <th class="text-center" width="10%">
              Số máy
            </th>
            <th class="text-left">
              Địa chỉ
            </th>
            <th class="text-center" width="15%">
              Trạng thái
            </th>
            <th class="text-center" width="15%">
              Hành động
            </th>
          </tr>
          </thead>
          <tbody>
          <template v-if="rooms.length > 0">
            <tr
                v-for="(item, index) in rooms"
                :key="index"
            >
              <td class="text-center">{{ index + +1 + +page.perPage * (page.currentPage - 1) }}</td>
              <td>
                {{ getValue(item, 'faculty_id', '') }}
              </td>
              <td>
                <span class="nameRoom" @click="openDialogShow(item)">
                    {{ getValue(item, 'name', '') }}
                </span>
              </td>
              <td class="text-center">
                  <span
                      :class="{ roomActive:getValue(item, 'is_active', false) }"
                      class="roomStatus font-weight-bold"
                  >
                      {{ getValue(item, 'is_active', false) ? 'Hoạt động' : 'Ẩn' }}
                  </span>
              </td>
              <td class="text-center">
                <v-menu offset-y>
                  <template v-slot:activator="{ on, attrs }">
                    <v-icon
                        v-bind="attrs"
                        v-on="on"
                    >
                      {{ icon.mdiMenu }}
                    </v-icon>
                  </template>
                  <v-list>
                    <v-list-item @click="openDialogShow(item)">
                      <v-list-item-title>
                        <v-icon>
                          {{ icon.mdiViewListOutline }}
                        </v-icon>
                        Xem thông tin
                      </v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="openDialogUpdate(item)">
                      <v-list-item-title>
                        <v-icon>
                          {{ icon.mdiPencilBoxOutline }}
                        </v-icon>
                        Chỉnh sửa
                      </v-list-item-title>
                    </v-list-item>
                    <v-list-item @click="openDialogDelete(item)">
                      <v-list-item-title>
                        <v-icon>
                          {{ icon.mdiTrashCanOutline }}
                        </v-icon>
                        Xóa
                      </v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td class="text-center" colspan="7">
                <img src="/images/empty.png" width="450px" alt="">
              </td>
            </tr>
          </template>

          </tbody>
        </template>
      </v-simple-table>
    </v-card>

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
import {mdiMagnify, mdiMenu, mdiPencilBoxOutline, mdiPlus, mdiTrashCanOutline, mdiViewListOutline} from "@mdi/js";
import {mapMutations} from "vuex";
import _ from "lodash";
import {required} from "vuelidate/lib/validators";
import api from "../api";

export default {
  name: "Room",
  data: () => ({
    icon: {
      mdiMagnify,
      mdiPlus,
      mdiPencilBoxOutline,
      mdiTrashCanOutline,
      mdiMenu,
      mdiViewListOutline
    },
    breadcrumbs: [
      {
        text: 'Bảng điều khiển',
        disabled: false,
        href: '/dashboard',
      },
      {
        text: 'Quản lý phòng máy',
        disabled: true,
        href: '/room',
      }
    ],
    search: '',
    semesters: [],
    page: {
      currentPage: 1,
      total: 0,
      perPage: 10
    },
    dialogCreate: false,
    dialogUpdate: false,
    dialogShow: false,
    dialogDelete: false,
    snackbar: {
      isShow: false,
      color: 'success',
      message: '',
    },
    rooms: [],
    roomId: "",
    name: "",
    subjects: [],
    computerNumber: "",
    address: "",
    software: "",
    serveError: {
      name: '',
      roomId: '',
      address: '',
      computerNumber: '',
      subjects: ''
    },
  }),
  validations: {
    name: {
      required
    },
    roomId: {
      required
    },
    address: {
      required
    },
    computerNumber: {
      required
    },

  },
  computed: {
    nameErrors() {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      !this.$v.name.required && errors.push('Tên phòng máy không được bỏ trống')
      if (this.serveError.name) {
        errors.push(this.serveError.name)
      }
      return errors
    },
    roomIdErrors() {
      const errors = []
      if (!this.$v.roomId.$dirty) return errors
      !this.$v.roomId.required && errors.push('Mã phòng máy không được bỏ trống')
      if (this.serveError.roomId) {
        errors.push(this.serveError.roomId)
      }
      return errors
    },
    computerNumberErrors() {
      const errors = []
      if (!this.$v.computerNumber.$dirty) return errors
      !this.$v.computerNumber.required && errors.push('Số lượng máy không được bỏ trống')
      if (this.serveError.computerNumber) {
        errors.push(this.serveError.computerNumber)
      }
      return errors
    },
    addressErrors() {
      const errors = []
      if (!this.$v.address.$dirty) return errors
      !this.$v.address.required && errors.push('Địa chỉ phòng máy không được bỏ trống')
      if (this.serveError.address) {
        errors.push(this.serveError.address)
      }
      return errors
    },
    subjectsErrors() {
      const errors = []
      if (!this.$v.subjects.$dirty) return errors
      !this.subjects.length > 0 && errors.push('Môn học không được bỏ trống')
      if (this.serveError.subjects) {
        errors.push(this.serveError.subjects)
      }
      return errors
    },
  },
  methods: {
    ...mapMutations('home', [
      'changeTitle',
      'setActiveMenu',
      'setLoader'
    ]),
    getValue(res, data = '', df = undefined) {
      return _.get(res, data, df)
    },
    resetForm() {

      this.$v.$reset()
    },

    handleGetRooms() {
      this.setLoader(true)

      let payload = {}

      if (this.search) {
        payload.q = this.search
      }

      payload.page = this.page.currentPage

      api.getFaculties(payload).then(res => {
        this.rooms = _.get(res, 'data.data.rooms.data', [])
        this.page.currentPage = _.get(res, 'data.data.rooms.current_page', 1)
        this.page.total = _.get(res, 'data.data.rooms.last_page', 0)
        this.page.perPage = _.get(res, 'data.data.rooms.per_page', 0)
        this.setLoader(false)
      }).catch(() => {
        this.showMessage('error', 'Không tải được dữ liệu')
      }).finally(() => this.setLoader(false))
    },
    changePage(page) {
      this.page.currentPage = page
      this.handleGetRooms()
    },
    openDialogCreate() {
      this.resetForm()
      this.dialogCreate = true
    },
    openDialogDelete(item) {
      this.resetForm()
      this.selectId = _.get(item, 'id', '')
      this.dialogDelete = true
    },
    openDialogUpdate(item) {
      this.resetForm()
      this.name = _.get(item, 'name', '')
      this.roomId = _.get(item, 'room_id', '')
      this.computerNumber = _.get(item, 'computer_number', '')
      this.subjects = _.get(item, 'subjects', '')
      this.software = _.get(item, 'software', '')
      this.isActive = _.get(item, 'is_active', false)
      this.selectId = _.get(item, 'id', '')
      this.isActive = _.get(item, 'is_active', '')
      this.dialogUpdate = true
    },
    showMessage(type, message) {
      this.snackbar = {
        message: message,
        color: type,
        isShow: true,
      }
      setTimeout(() => this.snackbar.isShow = false, 2000)
    },
    openDialogShow(item) {
      this.resetForm()
      this.name = _.get(item, 'name', '')
      this.roomId = _.get(item, 'room_id', '')
      this.computerNumber = _.get(item, 'computer_number', '')
      this.subjects = _.get(item, 'subjects', '')
      this.software = _.get(item, 'software', '')
      this.isActive = _.get(item, 'is_active', false)
      this.dialogShow = true
    },
  },
  mounted() {
    this.changeTitle('Quản lý phòng máy')
    this.setActiveMenu(4)
  },
  watch: {
    name(value) {
      if (value) this.serveError.name = ""

    },
    roomId(value) {
      if (value) this.serveError.roomId = ""
    },
    address(value) {
      if (value) this.serveError.address = ""
    },
    computerNumber(value) {
      if (value) this.serveError.computerNumber = ""
    },
    subject(value) {
      if (value) this.serveError.subject = ""
    }
  }
}
</script>

<style scoped lang="scss">
.breadcrumbWrapper {
  padding-left: 0px;
  padding-top: 0px;
}
.roomStatus {
  color: #EF5350;
}

.roomActive {
  color: #4CAF50 !important;
}

.nameRoom {
  cursor: pointer;
  font-weight: bold;
}
</style>
