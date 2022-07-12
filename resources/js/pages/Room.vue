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
                {{ getValue(item, 'room_id', '') }}
              </td>
              <td>
                <span class="nameRoom" @click="openDialogShow(item)">
                    {{ getValue(item, 'name', '') }}
                </span>
              </td>
              <td class="text-center">
                {{ getValue(item, 'computer_number', 0) }}
              </td>
              <td class="text-left">
                {{ getValue(item, 'address', 'Đang cập nhật') }}
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
          width="800"
      >
        <v-card>
          <v-card-title class="text-h5 lighten-2">
            Thêm mới phòng máy
          </v-card-title>

          <v-card-text>
            <label><span class="font-weight-bold">Mã phòng máy <span class="required">*</span>:</span></label>
            <v-text-field
                v-model="roomId"
                :error-messages="roomIdErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.roomId.$touch()"
                @blur="$v.roomId.$touch()"
            />
            <label><span class="font-weight-bold">Tên phòng máy<span class="required">*</span>:</span></label>
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
            <label><span class="font-weight-bold">Số lượng máy<span class="required">*</span>:</span></label>
            <v-text-field
                v-model="computerNumber"
                :error-messages="computerNumberErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.computerNumber.$touch()"
                @blur="$v.computerNumber.$touch()"
            />
            <label><span class="font-weight-bold">Địa chỉ<span class="required">*</span>:</span></label>
            <v-text-field
                v-model="address"
                :error-messages="addressErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.address.$touch()"
                @blur="$v.address.$touch()"
            />
            <label><span class="font-weight-bold">Môn học<span class="required">*</span>:</span></label>
            <v-autocomplete
                :items="subjectArray"
                item-text="name"
                item-value="name"
                :error-messages="subjectErrors"
                dense
                v-model="subject"
                class="mt-2"
                placeholder="Chọn bộ môn"
                outlined
                @input="$v.subject.$touch()"
                @blur="$v.subject.$touch()"
                multiple
                chips
            />
            <label><span class="font-weight-bold">Phần mềm môn học:</span></label>
            <v-text-field
                v-model="software"
                outlined
                dense
                color="blue"
                class="mt-2"
            />
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                @click="handleCreateRoom"
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
          width="800"
      >
        <v-card>
          <v-card-title class="text-h5 lighten-2">
            Chỉnh sửa khoa
          </v-card-title>

          <v-card-text>
            <label><span class="font-weight-bold">Mã phòng máy <span class="required">*</span>:</span></label>
            <v-text-field
                v-model="roomId"
                :error-messages="roomIdErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.roomId.$touch()"
                @blur="$v.roomId.$touch()"
            />
            <label><span class="font-weight-bold">Tên phòng máy<span class="required">*</span>:</span></label>
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
            <label><span class="font-weight-bold">Số lượng máy<span class="required">*</span>:</span></label>
            <v-text-field
                v-model="computerNumber"
                :error-messages="computerNumberErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.computerNumber.$touch()"
                @blur="$v.computerNumber.$touch()"
            />
            <label><span class="font-weight-bold">Địa chỉ<span class="required">*</span>:</span></label>
            <v-text-field
                v-model="address"
                :error-messages="addressErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.address.$touch()"
                @blur="$v.address.$touch()"
            />
            <label><span class="font-weight-bold">Môn học<span class="required">*</span>:</span></label>
            <v-autocomplete
                :items="subjectArray"
                item-text="name"
                item-value="name"
                :error-messages="subjectErrors"
                dense
                v-model="subject"
                class="mt-2"
                placeholder="Chọn bộ môn"
                outlined
                @input="$v.subject.$touch()"
                @blur="$v.subject.$touch()"
                multiple
                chips
            />
            <label><span class="font-weight-bold">Phần mềm môn học:</span></label>
            <v-text-field
                v-model="software"
                outlined
                dense
                color="blue"
                class="mt-2"
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
                @click="handleUpdateRoom"
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
            Thông tin phòng máy
          </v-card-title>

          <v-card-text>
            <div class="row">
              <div class="col-md-6">
                <h5>Mã phòng máy: <span class="font-weight-bold">{{ roomId }}</span></h5>
              </div>
              <div class="col-md-6">
                <h5>Trạng thái: <span class="font-weight-bold roomStatus"
                                      :class="{ roomActive: isActive}">{{
                    isActive ? 'Hoạt động' : 'Ẩn'
                  }}</span></h5>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <h5>Tên phòng máy: <span class="font-weight-bold">{{ name }}</span></h5>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <h5>Số lượng máy: <span class="font-weight-bold">{{ computerNumber }}</span></h5>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <h5>Địa chỉ: <span class="font-weight-bold">{{ address }}</span></h5>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <h5>Môn học: <span class="font-weight-bold">{{ subject.toString() }}</span></h5>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <h5>Phần mềm: <span class="font-weight-bold">{{ software }}</span></h5>
              </div>
            </div>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="red"
                dark
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
                @click="handleDeleteRoom"
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
    subject: [],
    computerNumber: "",
    address: "",
    software: "",
    serveError: {
      name: '',
      roomId: '',
      address: '',
      computerNumber: '',
      subject: ''
    },
    subjectArray: [],
    isActive: false,
    selectId: ""
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
    subject: {}
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
    subjectErrors() {
      const errors = []
      if (!this.$v.subject.$dirty) return errors
      !this.subject.length > 0 && errors.push('Môn học không được bỏ trống')
      if (this.serveError.subject) {
        errors.push(this.serveError.subject)
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
      this.roomId = ""
      this.computerNumber = ""
      this.subject = ""
      this.software = ""
      this.address = ""
      this.serveError = {
        name: '',
        roomId: '',
        address: '',
        computerNumber: '',
        subject: ''
      }
      this.$v.$reset()
    },
    getListSubject() {
      api.getAllSubject().then(res => {
        this.subjectArray = _.get(res, 'data.data.subjects', [])
      })
    },
    handleGetRooms() {
      this.setLoader(true)

      let payload = {}

      if (this.search) {
        payload.q = this.search
      }

      payload.page = this.page.currentPage

      api.getRoom(payload).then(res => {
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
      this.subject = _.get(item, 'subject', '')
      if (this.subject) {
        this.subject = this.subject.split(',')
      }
      this.software = _.get(item, 'software', '')
      this.address = _.get(item, 'address', '')
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
      this.subject = _.get(item, 'subject', '')
      this.address = _.get(item, 'address', '')
      this.software = _.get(item, 'software', '')
      this.isActive = _.get(item, 'is_active', false)
      this.dialogShow = true
    },
    handleCreateRoom() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.setLoader(true)
        let payload = {
          room_id: this.roomId,
          name: this.name,
          computer_number: this.computerNumber,
          address: this.address,
          subject: this.subject,
          software: this.software,
        }

        api.createRoom(payload).then(res => {
          if (res) {
            this.handleGetRooms()
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
            this.serveError.roomId = _.get(errors, 'room_id[0]', '')
            this.serveError.subject = _.get(errors, 'subject[0]', '')
            this.serveError.computerNumber = _.get(errors, 'computer_number[0]', '')
            this.serveError.address = _.get(errors, 'address[0]', '')
          }

        }).finally(() => this.setLoader(false))
      }
    },
    handleUpdateRoom() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.setLoader(true)

        let payload = {
          room_id: this.roomId,
          name: this.name,
          computer_number: this.computerNumber,
          address: this.address,
          subject: this.subject,
          software: this.software,
          is_active: this.isActive
        }

        api.updateRoom(payload, this.selectId).then(() => {
          this.handleGetRooms()
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
            this.serveError.roomId = _.get(errors, 'room_id[0]', '')
            this.serveError.subject = _.get(errors, 'subject[0]', '')
            this.serveError.computerNumber = _.get(errors, 'computer_number[0]', '')
            this.serveError.address = _.get(errors, 'address[0]', '')
          }
        }).finally(() => this.setLoader(false))
      }
    },
    handleDeleteRoom() {
      this.setLoader(true)
      api.deleteRoom(this.selectId).then(() => {
        this.handleGetRooms()
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
  },
  mounted() {
    this.changeTitle('Quản lý phòng máy')
    this.setActiveMenu(4)
    this.getListSubject()
    this.handleGetRooms()
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
