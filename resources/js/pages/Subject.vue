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
              Mã môn học
            </th>
            <th class="text-left">
              Tên môn học
            </th>
            <th class="text-left">
              Bộ môn
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
          <template v-if="subjects.length > 0">
            <tr
                v-for="(item, index) in subjects"
                :key="index"
            >
              <td class="text-center">{{ index + +1 + +page.perPage * (page.currentPage - 1) }}</td>
              <td>
                {{ getValue(item, 'faculty_id', '') }}
              </td>
              <td>

              </td>
              <td class="text-center">
                    <span
                        :class="{ subjectActive:getValue(item, 'is_active', false) }"
                        class="subjectStatus font-weight-bold"
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
              <td class="text-center" colspan="6">
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

import {mapMutations} from "vuex";
import {mdiMagnify, mdiMenu, mdiPencilBoxOutline, mdiPlus, mdiTrashCanOutline, mdiViewListOutline} from "@mdi/js";
import _ from "lodash";
import api from "../api";

export default {
  name: "Subject",
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
        text: 'Quản lý môn học',
        disabled: true,
        href: '/subject',
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
    subjectId: "",
    name: "",
    departmentId: "",
    subjects: [],
    serveError: {
      name: '',
      subjectId: '',
      departmentId: '',
    },
  }),
  computed: {
    nameErrors() {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      !this.$v.name.required && errors.push('Tên môn học không được bỏ trống')
      if (this.serveError.name) {
        errors.push(this.serveError.name)
      }
      return errors
    },
    subjectIdErrors() {
      const errors = []
      if (!this.$v.subjectId.$dirty) return errors
      !this.$v.subjectId.required && errors.push('Mã môn học không được bỏ trống')
      if (this.serveError.subjectId) {
        errors.push(this.serveError.subjectId)
      }
      return errors
    },
    departmentIdErrors() {
      const errors = []
      if (!this.$v.departmentId.$dirty) return errors
      !this.$v.departmentId.required && errors.push('Mã bộ môn không được bỏ trống')
      if (this.serveError.departmentId) {
        errors.push(this.serveError.departmentId)
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
      this.name = ""
      this.subjectId = ""
      this.departmentId = ""
      this.$v.$reset()
    },
    openDialogCreate() {
      this.resetForm()
      this.dialogCreate = true
    },
    handleGetSubjects() {
      this.setLoader(true)

      let payload = {}

      if (this.search) {
        payload.q = this.search
      }

      payload.page = this.page.currentPage

      api.getSubject(payload).then(res => {
        this.faculties = _.get(res, 'data.data.subjects.data', [])
        this.page.currentPage = _.get(res, 'data.data.subjects.current_page', 1)
        this.page.total = _.get(res, 'data.data.subjects.last_page', 0)
        this.page.perPage = _.get(res, 'data.data.subjects.per_page', 0)
        this.setLoader(false)
      }).catch(() => {
        this.showMessage('error', 'Không tải được dữ liệu')
      }).finally(() => this.setLoader(false))
    },
    changePage(page) {
      this.page.currentPage = page
      this.handleGetSubjects()
    },
  },
  mounted() {
    this.changeTitle('Quản lý môn học')
    this.setActiveMenu(3)
    this.handleGetSubjects()
  }
}
</script>

<style scoped lang="scss">
.breadcrumbWrapper {
  padding-left: 0px;
  padding-top: 0px;
}

.subjectStatus {
  color: #EF5350;
}

.subjectActive {
  color: #4CAF50 !important;
}
</style>
