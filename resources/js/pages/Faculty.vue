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
                placeholder="Nhập tên hoặc mã khoa để tìm kiếm..."
                clearable
                @click:clear="handleClearSearch"
                @keydown.enter.native="handleGetFaculties"
            />
          </div>
          <div class="col-md-4">
            <v-btn
                large
                dark
                color="primary"
                @click="handleGetFaculties"
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
              Mã khoa
            </th>
            <th class="text-left">
              Tên khoa
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
          <template v-if="faculties.length > 0">
            <tr
                v-for="(item, index) in faculties"
                :key="index"
            >
              <td class="text-center">{{ index + +1 + +page.perPage * (page.currentPage - 1) }}</td>
              <td>
                {{ getValue(item, 'faculty_id', '') }}
              </td>
              <td>
                <span class="nameFaculty" @click="openDialogShow(item)">
                    {{ getValue(item, 'name', '') }}
                </span>
              </td>
              <td class="text-center">
                  <span
                      :class="{ facultyActive:getValue(item, 'is_active', false) }"
                      class="facultyStatus font-weight-bold"
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
              <td class="text-center" colspan="5">
                <img src="/images/empty.png" width="450px" alt="">
              </td>
            </tr>
          </template>

          </tbody>
        </template>
      </v-simple-table>
    </v-card>
    <v-row justify="center">
      <v-col cols="8">
        <v-container class="max-width">
          <v-pagination
              @input="changePage"
              v-model="page.currentPage"
              class="my-4"
              :length="page.total"
              :total-visible="10"
          ></v-pagination>
        </v-container>
      </v-col>
    </v-row>
    <div class="text-center">
      <v-dialog
          v-model="dialogCreate"
          width="500"
      >
        <v-card>
          <v-card-title class="text-h5 lighten-2">
            Thêm mới khoa
          </v-card-title>

          <v-card-text>
            <v-text-field
                v-model="facultyId"
                :error-messages="facultyIdErrors"
                label="Mã khoa"
                outlined
                dense
                color="blue"
                autocomplete="false"
                class="mt-5"
                @input="$v.facultyId.$touch()"
                @blur="$v.facultyId.$touch()"
            />
            <v-text-field
                v-model="name"
                :error-messages="nameErrors"
                label="Tên khoa"
                outlined
                dense
                color="blue"
                autocomplete="false"
                class="mt-2"
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
            />
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                @click="handleCreateFaculty"
                color="primary"
            >
              Thêm mới
            </v-btn>
            <v-btn
                color="red"
                text
                @click="dialogCreate = false"
            >
              Đóng
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog
          v-model="dialogUpdate"
          width="500"
      >
        <v-card>
          <v-card-title class="text-h5 lighten-2">
            Chỉnh sửa khoa
          </v-card-title>

          <v-card-text>
            <v-text-field
                v-model="facultyId"
                :error-messages="facultyIdErrors"
                label="Mã khoa"
                outlined
                dense
                color="blue"
                autocomplete="false"
                class="mt-5"
                @input="$v.facultyId.$touch()"
                @blur="$v.facultyId.$touch()"
            />
            <v-text-field
                v-model="name"
                :error-messages="nameErrors"
                label="Tên khoa"
                outlined
                dense
                color="blue"
                autocomplete="false"
                class="mt-2"
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
            />

            <v-switch
                v-model="isActive"
                inset
                color="success"
                :label="`${isActive ? 'Hoạt động' : 'Ẩn'}`"
            ></v-switch>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                @click="handleUpdateFaculty"
                color="primary"
            >
              Lưu
            </v-btn>
            <v-btn
                color="red"
                text
                @click="dialogUpdate = false"
            >
              Đóng
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog
          v-model="dialogShow"
          width="60%"
      >
        <v-card>
          <v-card-title class="text-h5 lighten-2">
            Thông tin khoa
          </v-card-title>

          <v-card-text>
            <div class="row">
              <div class="col-md-6">
                <h5>Mã khoa: <span class="font-weight-bold">{{ facultyId }}</span></h5>
              </div>
              <div class="col-md-6">
                <h5>Trạng thái: <span class="font-weight-bold facultyStatus"
                                      :class="{ facultyActive: isActive}">{{
                    isActive ? 'Hoạt động' : 'Ẩn'
                  }}</span></h5>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <h5>Tên khoa: <span class="font-weight-bold">{{ name }}</span></h5>
              </div>
            </div>
            <Department :faculty-id="selectId"/>

          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="red"
                text
                @click="dialogShow = false"
            >
              Đóng
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
      <v-dialog
          v-model="dialogDelete"
          width="450"
      >
        <v-card>
          <v-card-title class="text-h5 grey lighten-2">
            Xoá bản ghi ?
          </v-card-title>

          <v-card-text>
            <div class="font-weight-bold">
              Bạn chắc chắn muốn xoá bản ghi? Dữ liệu không thể phục hồi!
            </div>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="#E53935"
                dark
                @click="handleDeleteFaculty"
            >
              Đồng ý
            </v-btn>
            <v-btn
                color="primary"
                text
                @click="dialogDelete = false"
            >
              Đóng
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
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
import {mapMutations} from "vuex"
import {mdiMagnify, mdiMenu, mdiPencilBoxOutline, mdiPlus, mdiTrashCanOutline, mdiViewListOutline} from '@mdi/js'
import _ from "lodash"
import api from '../api'
import {required} from 'vuelidate/lib/validators'
import Department from "../components/Department"

export default {
  name: "Faculty",
  components: {Department},
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
        text: 'Quản lý khoa - bộ môn',
        disabled: true,
        href: '/department',
      }
    ],
    search: '',
    faculties: [],
    page: {
      currentPage: 1,
      total: 0,
      perPage: 10
    },
    dialogCreate: false,
    dialogUpdate: false,
    dialogShow: false,
    dialogDelete: false,
    facultyId: '',
    name: '',
    isActive: false,
    selectId: '',
    serveError: {
      name: '',
      facultyId: ''
    },
    departments: [],
    snackbar: {
      isShow: false,
      color: 'success',
      message: '',
    }
  }),
  validations: {
    name: {
      required
    },
    facultyId: {
      required
    },
  },
  computed: {
    nameErrors() {
      const errors = []
      if (!this.$v.name.$dirty) return errors
      !this.$v.name.required && errors.push('Tên khoa không được bỏ trống')
      if (this.serveError.name) {
        errors.push(this.serveError.name)
      }
      return errors
    },
    facultyIdErrors() {
      const errors = []
      if (!this.$v.facultyId.$dirty) return errors
      !this.$v.facultyId.required && errors.push('Mã khoa không được bỏ trống')
      if (this.serveError.facultyId) {
        errors.push(this.serveError.facultyId)
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
    handleGetFaculties() {
      this.setLoader(true)

      let payload = {}

      if (this.search) {
        payload.q = this.search
      }

      payload.page = this.page.currentPage

      api.getFaculties(payload).then(res => {
        this.faculties = _.get(res, 'data.data.faculties.data', [])
        this.page.currentPage = _.get(res, 'data.data.faculties.current_page', 1)
        this.page.total = _.get(res, 'data.data.faculties.last_page', 0)
        this.page.perPage = _.get(res, 'data.data.faculties.per_page', 0)
        this.setLoader(false)
      }).catch(() => {
        this.showMessage('error', 'Không tải được dữ liệu')
        this.setLoader(false)
      })
    },

    handleClearSearch() {
      this.search = ""
      this.handleGetFaculties()
    },
    handleCreateFaculty() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.setLoader(true)
        let payload = {
          faculty_id: this.facultyId,
          name: this.name
        }

        api.createFaculty(payload).then(res => {
          if (res) {
            this.handleGetFaculties()
            this.dialogCreate = false
            this.showMessage('success', 'Tạo mới thành công')
          }
        }).catch(error => {
          let errors = _.get(error.response, 'data.error', {})
          if (Object.keys(errors).length === 0) {
            let message = _.get(error.response, 'data.message', '')
            this.showMessage('error', message)
          }
          if (Object.keys(errors).length > 0) {
            this.serveError.name = _.get(errors, 'name[0]', '')
            this.serveError.facultyId = _.get(errors, 'faculty_id[0]', '')
          }
          this.setLoader(false)

        })
      }
    },
    handleUpdateFaculty() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.setLoader(true)

        let payload = {
          faculty_id: this.facultyId,
          name: this.name,
          is_active: this.isActive
        }

        api.updateFaculty(payload, this.selectId).then(res => {
          if (res) {
            this.handleGetFaculties()
            this.dialogUpdate = false
            this.showMessage('success', 'Tạo mới thành công')
          }
        }).catch(error => {
          let errors = _.get(error.response, 'data.error', {})
          if (Object.keys(errors).length === 0) {
            let message = _.get(error.response, 'data.message', '')
            this.showMessage('error', message)
          }
          if (Object.keys(errors).length > 0) {
            this.serveError.name = _.get(errors, 'name[0]', '')
            this.serveError.facultyId = _.get(errors, 'faculty_id[0]', '')
          }
          this.setLoader(false)
        })
      }
    },
    handleDeleteFaculty() {
      this.setLoader(true)
      api.deleteFaculty(this.selectId).then(() => {
        this.handleGetFaculties()
        this.dialogDelete = false
        this.showMessage('success', 'Tạo mới thành công')
      }).catch(error => {
        let errors = _.get(error.response, 'data.error', {})
        if (Object.keys(errors).length === 0) {
          let message = _.get(error.response, 'data.message', '')
          this.showMessage('error', message)
        }
        this.setLoader(false)
      })
    },
    changePage(page) {
      this.page.currentPage = page
      this.getFaculties()
    },
    resetForm() {
      this.facultyId = ''
      this.name = ''
      this.selectId = ''
      this.isActive = ''
      this.serveError = {
        name: '',
        facultyId: ''
      }
      this.$v.$reset()
    },
    openDialogCreate() {
      this.resetForm()
      this.dialogCreate = true
    },
    openDialogUpdate(item) {
      this.resetForm()
      this.name = _.get(item, 'name', '')
      this.facultyId = _.get(item, 'faculty_id', '')
      this.selectId = _.get(item, 'id', '')
      this.isActive = _.get(item, 'is_active', '')
      this.dialogUpdate = true
    },
    async openDialogShow(item) {
      this.resetForm()
      this.name = _.get(item, 'name', '')
      this.facultyId = _.get(item, 'faculty_id', '')
      this.selectId = _.get(item, 'id', '')
      this.isActive = _.get(item, 'is_active', false)
      this.dialogShow = true
    },
    openDialogDelete(item) {
      this.resetForm()
      this.selectId = _.get(item, 'id', '')
      this.dialogDelete = true
    },
    showMessage(type, message) {
      this.snackbar = {
        message: message,
        color: type,
        isShow: true,
      }
    }
  },
  mounted() {
    this.changeTitle('Quản lý khoa - bộ môn')
    this.setActiveMenu(1)
    this.handleGetFaculties()
  }
}
</script>

<style scoped lang="scss">
.breadcrumbWrapper {
  padding-left: 0px;
  padding-top: 0px;
}

.facultyStatus {
  color: #EF5350;
}

.facultyActive {
  color: #4CAF50 !important;
}

.nameFaculty {
  cursor: pointer;
  font-weight: bold;
}
</style>
