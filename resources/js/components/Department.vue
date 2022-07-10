<template>
  <div class="departmentWrapper">
    <div class="row">
      <div class="col-md-6">
        <h5>Danh sách bộ môn</h5>
      </div>
      <div class="col-md-6 text-end">
        <v-btn
            small
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
    <div class="row">
      <div class="col-md-12">
        <v-simple-table>
          <template v-slot:default>
            <thead>
            <tr>
              <th class="text-center" width="10%">
                STT
              </th>
              <th class="text-left" width="15%">
                Mã bộ môn
              </th>
              <th class="text-left">
                Tên bộ môn
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
            <template v-if="departments.length > 0">
              <tr
                  v-for="(item, index) in departments"
                  :key="index"
              >
                <td class="text-center">{{ index + 1 }}</td>
                <td>
                  {{ getValue(item, 'department_id', '') }}
                </td>
                <td>
                  {{ getValue(item, 'name', '') }}

                </td>
                  <td class="text-center">
                    <span
                        :class="{ departmentActive: getValue(item, 'is_active', false) }"
                        class="departmentStatus font-weight-bold"
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
                <td class="text-center" colspan="5">
                    <img src="/images/empty.png" width="450px" alt="">
                </td>
              </tr>
            </template>

            </tbody>
          </template>
        </v-simple-table>
      </div>
    </div>
      <div class="text-center">
          <v-dialog
              v-model="dialogCreate"
              width="500"
          >
              <v-card>
                  <v-card-title class="text-h5 lighten-2">
                      Thêm mới bộ môn
                  </v-card-title>

                  <v-card-text>
                      <label><span class="font-weight-bold">Mã bộ môn <span class="required">*</span>:</span></label>
                      <v-text-field
                          v-model="departmentId"
                          :error-messages="departmentIdErrors"
                          outlined
                          dense
                          color="blue"
                          class="mt-2"
                          @input="$v.departmentId.$touch()"
                          @blur="$v.departmentId.$touch()"
                      />
                      <label><span class="font-weight-bold">Tên bộ môn<span class="required">*</span>:</span></label>
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
                  </v-card-text>

                  <v-divider></v-divider>

                  <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn
                          @click="handleCreateDepartment"
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
                      <label><span class="font-weight-bold">Mã bộ môn <span class="required">*</span>:</span></label>
                      <v-text-field
                          v-model="departmentId"
                          :error-messages="departmentIdErrors"
                          outlined
                          dense
                          color="blue"
                          class="mt-2"
                          @input="$v.departmentId.$touch()"
                          @blur="$v.departmentId.$touch()"
                      />
                      <label><span class="font-weight-bold">Tên bộ môn<span class="required">*</span>:</span></label>
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
                          @click="handleUpdateDepartment"
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
                          @click="handleDeleteDepartment"
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
  </div>
</template>

<script>
import _ from "lodash"
import {mdiMagnify, mdiMenu, mdiPencilBoxOutline, mdiPlus, mdiTrashCanOutline, mdiViewListOutline} from "@mdi/js"
import api from "../api"
import {mapMutations} from "vuex"
import {required} from 'vuelidate/lib/validators'

export default {
    name: "Department",
    data: () => ({
        icon: {
            mdiMagnify,
            mdiPlus,
            mdiPencilBoxOutline,
            mdiTrashCanOutline,
            mdiMenu,
            mdiViewListOutline
        },
        departments: '',
        dialogCreate: false,
        dialogUpdate: false,
        dialogDelete: false,
        snackbar: {
            isShow: false,
            color: 'success',
            message: '',
        },
        serveError: {
            name: '',
            departmentId: ''
        },
        name: '',
        departmentId: '',
        isActive: false
    }),
    validations: {
        name: {
            required
        },
        departmentId: {
            required
        },
    },
    props: ['facultyId'],
    computed: {
        nameErrors() {
            const errors = []
            if (!this.$v.name.$dirty) return errors
            !this.$v.name.required && errors.push('Tên bộ môn không được bỏ trống')
            if (this.serveError.name) {
                errors.push(this.serveError.name)
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
            'setLoader'
        ]),
        getValue(res, data = '', df = undefined) {
            return _.get(res, data, df)
        },
        handleGetDepartmentByFaculty(facultyId) {
            if (facultyId) {
                this.setLoader(true)
                api.getDepartmentByFaculty(facultyId).then(res => {
                    if (res) {
                        this.departments = _.get(res, 'data.data.departments', [])
                    }
                    this.setLoader(false)
                }).catch(error => {
                    let errors = _.get(error.response, 'data.error', {})
                    if (Object.keys(errors).length === 0) {
                        let message = _.get(error.response, 'data.message', '')
                        this.showMessage('error', message)
                    }
                }).finally(() => this.setLoader(false))
            }
        },
        handleCreateDepartment() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                this.setLoader(true)
                let payload = {
                    faculty_id: this.facultyId,
                    name: this.name,
                    department_id: this.departmentId
                }

                api.createDepartment(payload).then(res => {
                    if (res) {
                        this.handleGetDepartmentByFaculty(this.facultyId)
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
                        this.serveError.departmentId = _.get(errors, 'department_id[0]', '')
                    }

                }).finally(() => this.setLoader(false))
            }
        },
        handleUpdateDepartment() {
            this.$v.$touch()
            if (!this.$v.$invalid) {
                this.setLoader(true)

                let payload = {
                    faculty_id: this.facultyId,
                    name: this.name,
                    department_id: this.departmentId,
                    is_active: this.isActive
                }

                api.updateDepartment(payload, this.selectId).then(() => {
                    this.handleGetDepartmentByFaculty(this.facultyId)
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
                        this.serveError.departmentId = _.get(errors, 'department_id[0]', '')
                    }
                }).finally(() => this.setLoader(false))
            }
        },
        handleDeleteDepartment() {
            this.setLoader(true)
            api.deleteDepartment(this.selectId).then(() => {
                this.handleGetDepartmentByFaculty(this.facultyId)
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
        showMessage(type, message) {
            this.snackbar = {
                message: message,
                color: type,
                isShow: true,
            }

            setTimeout(() => this.snackbar.isShow = false, 2000)

        },
        resetForm() {
            this.departmentId = ''
            this.name = ''
            this.selectId = ''
            this.serveError = {
                name: '',
                departmentId: ''
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
    },
    mounted() {
        this.handleGetDepartmentByFaculty(this.facultyId)
    },
    watch: {
        facultyId(value) {
            this.handleGetDepartmentByFaculty(value)
        }
    }
}
</script>

<style scoped>

</style>
