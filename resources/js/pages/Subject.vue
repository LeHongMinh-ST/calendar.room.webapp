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
                @click:clear="handleClearSearch"
                @keydown.enter.native="handleGetSubjects"
            />
          </div>
          <div class="col-md-4">
            <v-btn
                large
                dark
                color="primary"
                @click="handleGetSubjects"
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
                {{ getValue(item, 'subject_id', '') }}
              </td>
              <td>
                {{ getValue(item, 'name', '') }}
              </td>
              <td>
                {{ getValue(item, 'department.name', 'Đang cập nhật') }}
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
            Thêm mới môn học
          </v-card-title>

          <v-card-text>
            <label><span class="font-weight-bold">Mã môn học <span class="required">*</span>:</span></label>
            <v-text-field
                v-model="subjectId"
                :error-messages="subjectIdErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.subjectId.$touch()"
                @blur="$v.subjectId.$touch()"
            />
            <label><span class="font-weight-bold">Tên môn học<span class="required">*</span>:</span></label>
            <v-text-field
                v-model="name"
                :error-messages="nameErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
            />

            <label><span class="font-weight-bold">Bộ môn<span class="required">*</span>:</span></label>
            <v-autocomplete
                :items="departments"
                item-text="name"
                item-value="id"
                :error-messages="departmentIdErrors"
                dense
                v-model="departmentId"
                class="mt-2"
                placeholder="Chọn bộ môn"
                outlined
                @input="$v.departmentId.$touch()"
                @blur="$v.departmentId.$touch()"
            />
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                @click="handleCreateSubject"
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
            <label><span class="font-weight-bold">Mã môn học <span class="required">*</span>:</span></label>
            <v-text-field
                v-model="subjectId"
                :error-messages="subjectIdErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.subjectId.$touch()"
                @blur="$v.subjectId.$touch()"
            />
            <label><span class="font-weight-bold">Tên môn học<span class="required">*</span>:</span></label>
            <v-text-field
                v-model="name"
                :error-messages="nameErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.name.$touch()"
                @blur="$v.name.$touch()"
            />

            <label><span class="font-weight-bold">Bộ môn<span class="required">*</span>:</span></label>
            <v-autocomplete
                :items="departments"
                item-text="name"
                item-value="id"
                :error-messages="departmentIdErrors"
                dense
                v-model="departmentId"
                class="mt-2"
                placeholder="Chọn bộ môn"
                outlined
                @input="$v.departmentId.$touch()"
                @blur="$v.departmentId.$touch()"
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
                @click="handleUpdateSubject"
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
                @click="handleDeleteSubject"
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

import {mapMutations} from "vuex";
import {mdiMagnify, mdiMenu, mdiPencilBoxOutline, mdiPlus, mdiTrashCanOutline, mdiViewListOutline} from "@mdi/js";
import _ from "lodash";
import api from "../api";
import {required} from "vuelidate/lib/validators";

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
    isActive: false,
    serveError: {
      name: '',
      subjectId: '',
      departmentId: '',
    },
    departments: [],
    selectId: "",

  }),
  validations: {
    name: {
      required
    },
    subjectId: {
      required
    },
    departmentId: {
      required
    },
  },
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
    handleClearSearch() {
      this.search = ""
      this.handleGetSubjects()
    },
    getValue(res, data = '', df = undefined) {
      return _.get(res, data, df)
    },
    resetForm() {
      this.name = ""
      this.subjectId = ""
      this.departmentId = ""
      this.serveError = {
        name: '',
        departmentId: '',
        subjectId: '',
      }
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
        this.subjects = _.get(res, 'data.data.subjects.data', [])
        this.page.currentPage = _.get(res, 'data.data.subjects.current_page', 1)
        this.page.total = _.get(res, 'data.data.subjects.last_page', 0)
        this.page.perPage = _.get(res, 'data.data.subjects.per_page', 0)
        this.setLoader(false)
      }).catch(() => {
        this.showMessage('error', 'Không tải được dữ liệu')
      }).finally(() => this.setLoader(false))
    },
    handleGetListDepartment() {
      api.getListDepartment().then(res => {
        this.departments = _.get(res, 'data.data.departments', [])
      })
    },
    handleCreateSubject() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.setLoader(true)
        let payload = {
          subject_id: this.subjectId,
          name: this.name,
          department_id: this.departmentId,
        }

        api.createSubject(payload).then(res => {
          if (res) {
            this.handleGetSubjects()
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
            this.serveError.subjectId = _.get(errors, 'subject_id[0]', '')
            this.serveError.departmentId = _.get(errors, 'department_id[0]', '')
          }

        }).finally(() => this.setLoader(false))
      }
    },
    handleUpdateSubject() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.setLoader(true)

        let payload = {
          subject_id: this.subjectId,
          name: this.name,
          department_id: this.departmentId,
          is_active: this.isActive
        }

        api.updateSubject(payload, this.selectId).then(() => {
          this.handleGetSubjects()
          this.dialogUpdate = false
          this.showMessage('success', 'Cập nhật thành công')
        }).catch(error => {
          let errors = _.get(error.response, 'data.error', {})
          if (Object.keys(errors).length === 0) {
            let message = _.get(error.response, 'data.message', '')
            this.showMessage('error', message)
          }
          if (Object.keys(errors).length > 0) {
            this.serveError.name = _.get(errors, 'name[0]', '')
            this.serveError.subjectId = _.get(errors, 'subject_id[0]', '')
            this.serveError.departmentId = _.get(errors, 'department_id[0]', '')
          }
        }).finally(() => this.setLoader(false))
      }
    },
    handleDeleteSubject() {
      this.setLoader(true)
      api.deleteSubject(this.selectId).then(() => {
        this.handleGetFaculties()
        this.dialogDelete = false
        this.showMessage('success', 'Xóa thành công')
      }).catch(error => {
        let errors = _.get(error.response, 'data.error', {})
        if (Object.keys(errors).length === 0) {
          let message = _.get(error.response, 'data.message', '')
          this.showMessage('error', message)
        }
      }).finally(() => this.setLoader(false))
    },
    changePage(page) {
      this.page.currentPage = page
      this.handleGetSubjects()
    },
    openDialogUpdate(item) {
      this.resetForm()
      this.name = _.get(item, 'name', '')
      this.subjectId = _.get(item, 'subject_id', '')
      this.departmentId = _.get(item, 'department_id', '')
      this.selectId = _.get(item, 'id', '')
      this.isActive = _.get(item, 'is_active', '')
      this.dialogUpdate = true
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
      setTimeout(() => this.snackbar.isShow = false, 2000)
    }
  },
  mounted() {
    this.changeTitle('Quản lý môn học')
    this.setActiveMenu(3)
    this.handleGetListDepartment()
    this.handleGetSubjects()
  },
  watch: {
    name(value) {
      if (value) this.serveError.name = ""
    },
    subjectId(value) {
      if (value) this.serveError.subjectId = ""
    },
    departmentId(value) {
      if (value) this.serveError.departmentId = ""
    },
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
