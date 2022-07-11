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
                @keydown.enter.native="handleGetSemester"
            />
          </div>
          <div class="col-md-4">
            <v-btn
                large
                dark
                color="primary"
                @click="handleGetSemester"
            >
              <v-icon left>
                {{ icon.mdiMagnify }}
              </v-icon>
              Tìm kiếm
            </v-btn>
            <v-badge
                :content="badgeFilter"
                :value="badgeFilter"
                color="red"
                overlap
            >
              <v-btn
                  large
                  dark
                  outlined
                  :color="showFilter ? 'red': 'primary'"
                  @click="toggleFilter"
              >
                <v-icon left>
                  {{ showFilter ? icon.mdiFilterRemove : icon.mdiFilter }}
                </v-icon>
                {{ showFilter ? 'Đóng bộ lọc' : 'Bộ lọc' }}
              </v-btn>
            </v-badge>
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
        <div v-if="showFilter" class="row mt-3">
          <h5 class="font-weight-bold">Bộ lọc:</h5>
          <div class="col-md-4">
            <label><span class="font-weight-bold">Năm học:</span></label>
            <v-autocomplete
                :items="schoolYearArrayFilter"
                item-text="name"
                item-value="value"
                dense
                v-model="filter.schoolYear"
                class="mt-2"
                placeholder="Chọn học năm học"
                outlined
            ></v-autocomplete>
          </div>
          <div class="col-md-4">
            <label><span class="font-weight-bold">Học kỳ:</span></label>
            <v-select
                :items="semesterArrFilter"
                item-text="name"
                item-value="value"
                v-model="filter.semester"
                dense
                outlined
                placeholder="Chọn học kỳ"
                class="mt-2"
            ></v-select>
          </div>
          <div class="col-md-4">
            <v-btn
                class="mt-7"
                dark
                color="primary"
                @click="handleGetSemester"
            >
              <v-icon left>
                {{ icon.mdiFilterOutline }}
              </v-icon>
              Lọc dữ liệu
            </v-btn>
            <v-btn
                class="mt-7"
                dark
                color="red"
                @click="resetFilter"
            >
              <v-icon left>
                {{ icon.mdiRefresh }}
              </v-icon>
              Đặt lại
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
              Năm học
            </th>
            <th class="text-left">
              Học Kỳ
            </th>
            <th class="text-center" width="15%">
              Số tuần
            </th>
            <th class="text-left">
              Ngày bắt đầu học kỳ
            </th>
            <th class="text-left">
              Ngày kết thúc học kỳ
            </th>

            <th class="text-center" width="15%">
              Hành động
            </th>
          </tr>
          </thead>
          <tbody>
          <template v-if="semesters.length > 0">
            <tr
                v-for="(item, index) in semesters"
                :key="index"
            >
              <td class="text-center">{{ index + +1 + +page.perPage * (page.currentPage - 1) }}</td>
              <td>
                {{ getValue(item, 'school_year', '') }}
              </td>
              <td>
                {{ getValue(item, 'semester', '') }}
              </td>
              <td class="text-center">
                {{ getValue(item, 'number_weeks', '') }}
              </td>
              <td>
                {{ formatDate(getValue(item, 'semester_start_date', '')) }}
              </td>
              <td>
                {{ formatDate(getValue(item, 'semester_end_date', '')) }}
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
          width="500"
      >
        <v-card>
          <v-card-title class="text-h5 lighten-2">
            Thêm mới học kỳ
          </v-card-title>

          <v-card-text>
            <label><span class="font-weight-bold">Năm học <span class="required">*</span>:</span></label>
            <v-autocomplete
                :items="schoolYearArray"
                dense
                v-model="schoolYear"
                class="mt-2"
                :error-messages="schoolYearErrors"
                @input="$v.schoolYear.$touch()"
                @blur="$v.schoolYear.$touch()"
                placeholder="Chọn học năm học"
                outlined
            ></v-autocomplete>

            <label><span class="font-weight-bold">Học kỳ <span class="required">*</span>:</span></label>
            <v-select
                :items="semesterArr"
                dense
                v-model="semester"
                outlined
                :error-messages="semesterErrors"
                @input="$v.semester.$touch()"
                @blur="$v.semester.$touch()"
                placeholder="Chọn học kỳ"
                class="mt-2"
            ></v-select>
            <label><span class="font-weight-bold">Số tuần <span class="required">*</span>:</span></label>
            <v-text-field
                v-model="numberWeek"
                :error-messages="numberWeekErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.numberWeek.$touch()"
                @blur="$v.numberWeek.$touch()"
            />
            <label><span class="font-weight-bold">Ngày bắt đầu <span
                class="required">*</span>:</span></label>
            <v-menu
                ref="menuPicker"
                v-model="menuPicker"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                max-width="290px"
                min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                    :disabled="!(schoolYear && semester)"
                    readonly
                    v-model="dateFormatted"
                    :error-messages="startDateErrors"
                    outlined
                    class="mt-2"
                    dense
                    placeholder="Chọn ngày bắt đầu"
                    v-bind="attrs"
                    @blur="startDate = parseDate(dateFormatted)"
                    @input="$v.startDate.$touch()"
                    v-on="on"
                />
              </template>
              <v-date-picker
                  v-model="startDate"
                  no-title
                  @input="menuPicker = false"
                  :max="limitYear.max"
                  :min="limitYear.min"
              />
            </v-menu>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                @click="handleCreateSemester"
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
            Cập nhật học kỳ
          </v-card-title>

          <v-card-text>
            <label><span class="font-weight-bold">Năm học <span class="required">*</span>:</span></label>
            <v-autocomplete
                :items="schoolYearArray"
                dense
                v-model="schoolYear"
                class="mt-2"
                disabled
                placeholder="Chọn học năm học"
                outlined
            ></v-autocomplete>

            <label><span class="font-weight-bold">Học kỳ <span class="required">*</span>:</span></label>
            <v-select
                :items="semesterArr"
                dense
                v-model="semester"
                outlined
                :error-messages="semesterErrors"
                @input="$v.semester.$touch()"
                @blur="$v.semester.$touch()"
                placeholder="Chọn học kỳ"
                class="mt-2"
            ></v-select>
            <label><span class="font-weight-bold">Số tuần <span class="required">*</span>:</span></label>
            <v-text-field
                v-model="numberWeek"
                :error-messages="numberWeekErrors"
                outlined
                dense
                color="blue"
                class="mt-2"
                @input="$v.numberWeek.$touch()"
                @blur="$v.numberWeek.$touch()"
            />
            <label><span class="font-weight-bold">Ngày bắt đầu <span
                class="required">*</span>:</span></label>
            <v-menu
                ref="menuPickerUpdate"
                v-model="menuPickerUpdate"
                :close-on-content-click="false"
                transition="scale-transition"
                offset-y
                max-width="290px"
                min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                    :disabled="!(schoolYear && semester)"
                    readonly
                    v-model="dateFormatted"
                    :error-messages="startDateErrors"
                    outlined
                    class="mt-2"
                    dense
                    placeholder="Chọn ngày bắt đầu"
                    v-bind="attrs"
                    @input="$v.startDate.$touch()"
                    v-on="on"
                />
              </template>
              <v-date-picker
                  v-model="startDate"
                  no-title
                  @input="menuPickerUpdate = false"
                  :max="limitYear.max"
                  :min="limitYear.min"
              />
            </v-menu>
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                @click="handleUpdateSemester"
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
          class="dialogShow"
      >
        <v-card>
          <v-card-title class="text-h5 lighten-2">
            Thông tin học kỳ
          </v-card-title>

          <v-card-text>
            <div class="row mt-3">
              <div class="col-md-6">
                <h5>Năm học: <span class="font-weight-bold">{{ schoolYear }}</span></h5>
              </div>
              <div class="col-md-6">
                <h5>Học kỳ: <span class="font-weight-bold">{{ semester }}</span></h5>
              </div>
            </div>
            <Week :semester-id="selectId"/>
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
                @click="handleDeleteSemester"
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
import {
  mdiMagnify,
  mdiFilter,
  mdiMenu,
  mdiPencilBoxOutline,
  mdiPlus,
  mdiTrashCanOutline,
  mdiViewListOutline,
  mdiFilterRemove,
  mdiFilterOutline,
  mdiRefresh
} from "@mdi/js"
import _ from "lodash"
import {required} from "vuelidate/lib/validators"
import moment from "moment";
import api from "../api";
import Week from "../components/Week";

export default {
  name: "Semester",
  components: {Week},
  data: () => ({
    filter: {
      semester: "",
      schoolYear: ""
    },
    menuPicker: false,
    menuPickerUpdate: false,
    dateFormatted: "",
    icon: {
      mdiRefresh,
      mdiFilterOutline,
      mdiFilterRemove,
      mdiFilter,
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
        text: 'Quản lý học kỳ - tuần',
        disabled: true,
        href: '/semester',
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
    numberWeek: "",
    startDate: "",
    semester: "",
    schoolYear: "",
    serveError: {
      numberWeek: '',
      semester: '',
      schoolYear: '',
      startDate: '',
    },
    selectId: "",
    semesterArr: [1, 2, 3],
    limitYear: {
      max: "",
      min: ""
    },
    showFilter: false
  }),
  validations: {
    numberWeek: {
      required
    },
    semester: {
      required
    },
    schoolYear: {
      required
    },
    startDate: {
      required
    },
  },
  computed: {
    numberWeekErrors() {
      const errors = []
      if (!this.$v.numberWeek.$dirty) return errors
      !this.$v.numberWeek.required && errors.push('Số tuần khồng được để trống')
      if (this.serveError.numberWeek) {
        errors.push(this.serveError.numberWeek)
      }
      return errors
    },
    semesterErrors() {
      const errors = []
      if (!this.$v.semester.$dirty) return errors
      !this.$v.semester.required && errors.push('Học kỳ khồng được để trống')
      if (this.serveError.semester) {
        errors.push(this.serveError.semester)
      }
      return errors
    },
    schoolYearErrors() {
      const errors = []
      if (!this.$v.schoolYear.$dirty) return errors
      !this.$v.schoolYear.required && errors.push('Năm học khồng được để trống')
      if (this.serveError.schoolYear) {
        errors.push(this.serveError.schoolYear)
      }
      return errors
    },
    startDateErrors() {
      const errors = []
      if (!this.$v.startDate.$dirty) return errors
      !this.$v.startDate.required && errors.push('Ngày bắt đầu khồng được để trống')
      if (this.serveError.startDate) {
        errors.push(this.serveError.startDate)
      }
      return errors
    },
    schoolYearArray() {
      let now = moment().format('YYYY')
      let schoolYears = []
      for (let startYear = 2005; startYear <= parseInt(now); startYear++) {
        let schoolYear = `${startYear} - ${startYear + +1}`
        schoolYears.push(schoolYear)
      }
      return schoolYears.reverse()
    },
    schoolYearArrayFilter() {
      let now = moment().format('YYYY')
      let schoolYears = []

      for (let startYear = 2005; startYear <= parseInt(now); startYear++) {
        let schoolYear = `${startYear} - ${startYear + +1}`
        schoolYears.push({
          name: schoolYear,
          value: schoolYear
        })
      }

      schoolYears.push({
        name: "Tất cả",
        value: ""
      })
      return schoolYears.reverse()
    },
    semesterArrFilter() {
      return [
        {name: "Tất cả", value: ""},
        {name: "1", value: "1"},
        {name: "2", value: "2"},
        {name: "3", value: "3"},
      ]
    },
    badgeFilter() {
      let filter = Object.values(this.filter).filter(item => item !== "")
      return filter.length
    }
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
    changePage(page) {
      this.page.currentPage = page
      this.handleGetSemester()
    },
    resetForm() {
      this.limitYear = {
        max: "",
        min: ""
      }
      this.semester = ""
      this.startDate = ""
      this.dateFormatted = ""
      this.numberWeek = ""
      this.schoolYear = ""
      this.$v.$reset()
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
      this.selectId = this.getValue(item, 'id', '')
      this.semester = this.getValue(item, 'semester', '')
      this.startDate = this.getValue(item, 'semester_start_date', '')
      this.numberWeek = this.getValue(item, 'number_weeks', '')
      this.schoolYear = this.getValue(item, 'school_year', '')
      this.dateFormatted = this.formatDate(this.startDate)
      this.dialogUpdate = true
    },
    openDialogShow(item) {
      this.selectId = _.get(item, 'id', '')
      this.schoolYear = _.get(item, 'school_year', '')
      this.semester = _.get(item, 'semester', '')
      this.dialogShow = true
    },
    formatDate(date) {
      if (!date) return null

      const [year, month, day] = date.split('-')
      return `${day}/${month}/${year}`
    },
    parseDate(date) {
      if (!date) return null

      const [month, day, year] = date.split('/')
      return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
    },
    handleClearSearch() {
      this.search = ""
      this.handleGetSemester()
    },
    handleGetSemester() {
      this.setLoader(true)

      let payload = {}

      if (this.search) {
        payload.q = this.search
      }

      if (this.filter.semester) {
        payload.semester = this.filter.semester
      }

      if (this.filter.schoolYear) {
        payload.schoolYear = this.filter.schoolYear
      }

      payload.page = this.page.currentPage

      api.getSemesters(payload).then(res => {
        this.semesters = _.get(res, 'data.data.semesters.data', [])
        this.page.currentPage = _.get(res, 'data.data.semesters.current_page', 1)
        this.page.total = _.get(res, 'data.data.semesters.last_page', 0)
        this.page.perPage = _.get(res, 'data.data.semesters.per_page', 0)
      }).catch(() => {
        this.showMessage('error', 'Không tải được dữ liệu')
      }).finally(() => this.setLoader(false))
    },
    handleCreateSemester() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.setLoader(true)

        let payload = {
          number_weeks: this.numberWeek,
          semester_start_date: this.startDate,
          semester: this.semester,
          school_year: this.schoolYear,
        }

        api.createSemester(payload).then(res => {
          this.handleGetSemester()
          this.dialogCreate = false
          this.showMessage('success', 'Tạo mới thành công')
        }).catch(error => {
          let errors = _.get(error.response, 'data.error', {})
          if (Object.keys(errors).length === 0) {
            let message = _.get(error.response, 'data.message', '')
            this.showMessage('error', message)
          }
          if (Object.keys(errors).length > 0) {
            this.serveError.numberWeek = _.get(errors, 'number_weeks[0]', '')
            this.serveError.startDate = _.get(errors, 'semester_start_date[0]', '')
            this.serveError.semester = _.get(errors, 'semester[0]', '')
            this.serveError.school_year = _.get(errors, 'school_year[0]', '')
          }

        }).finally(() => this.setLoader(false))
      }
    },
    handleDeleteSemester() {
      this.setLoader(true)
      api.deleteSemester(this.selectId).then(() => {
        this.handleGetSemester()
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
    handleUpdateSemester() {
      this.$v.$touch()
      if (!this.$v.$invalid) {
        this.setLoader(true)
        let payload = {
          number_weeks: this.numberWeek,
          semester_start_date: this.startDate,
          semester: this.semester,
          school_year: this.schoolYear,
        }

        api.updateSemester(payload, this.selectId).then(res => {
          this.handleGetSemester()
          this.dialogUpdate = false
          this.showMessage('success', 'Cập nhật thành công')
        }).catch(error => {
          let errors = _.get(error.response, 'data.error', {})
          if (Object.keys(errors).length === 0) {
            let message = _.get(error.response, 'data.message', '')
            this.showMessage('error', message)
          }
          if (Object.keys(errors).length > 0) {
            this.serveError.numberWeek = _.get(errors, 'number_weeks[0]', '')
            this.serveError.startDate = _.get(errors, 'semester_start_date[0]', '')
            this.serveError.semester = _.get(errors, 'semester[0]', '')
            this.serveError.school_year = _.get(errors, 'school_year[0]', '')
          }
        }).finally(() => this.setLoader(false))
      }

    },
    showMessage(type, message) {
      this.snackbar = {
        message: message,
        color: type,
        isShow: true,
      }

      setTimeout(() => this.snackbar.isShow = false, 2000)
    },
    toggleFilter() {
      this.showFilter = !this.showFilter
    },
    resetFilter() {
      this.filter.semester = ""
      this.filter.schoolYear = ""
      this.handleGetSemester()
    }
  },
  mounted() {
    this.changeTitle('Quản lý học kỳ - tuần')
    this.setActiveMenu(2)
    this.handleGetSemester()
  },
  watch: {
    startDate(value) {
      if (value) {
        this.serveError.startDate = ""
        this.dateFormatted = this.formatDate(value)
      }
    },
    schoolYear(value) {
      if (value) {
        this.serveError.schoolYear = ""
        let arrYear = value.split("-")
        this.limitYear.max = (parseInt(arrYear[1]) + +1).toString()
        this.limitYear.min = arrYear[0]
      }
    },
    semester(value) {
      if (value) this.serveError.semester = ""

    },
    numberWeek(value) {
      if (value) this.serveError.numberWeek = ""
    },

  }
}
</script>

<style scoped lang="scss">
.breadcrumbWrapper {
  padding-left: 0px;
  padding-top: 0px;
}
</style>
