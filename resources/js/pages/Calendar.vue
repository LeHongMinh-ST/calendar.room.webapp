<template>
  <v-container class="py-8 px-6" fluid>
    <div>
      <v-card>
        <div class="calendarWrapper p-2 pt-5">
          <v-row>
            <v-col class="col-4">
              <v-select
                  label="Học kỳ"
                  solo
              ></v-select>
            </v-col>
            <v-col class="col-4">
              <v-select
                  label="Phòng máy"
                  solo
              ></v-select>
            </v-col>
          </v-row>
          <v-sheet>
            <div class="headerCalendar text-center">
              <div class="header roomTitle text-h5 font-weight-bold">
                Phòng THCNTT01
              </div>
              <div class="subTitle text-h6">
                Học kỳ 2 năm học 2021-2022
              </div>
              <div class="subTitle text-h6">
                Trực sáng: Lê Văn Hỗ - 0986233438; Trực chiều: Thân Thị Huyền - 0976366748
              </div>
            </div>
          </v-sheet>
          <v-sheet height="70">
            <v-toolbar
                flat
            >
              <v-btn
                  dark
                  class="mr-4"
                  color="blue darken-2"
                  @click="setToday"
              >
                Hôm nay
              </v-btn>
              <v-spacer></v-spacer>

              <v-toolbar-title>
                {{ titleCalendar }}
              </v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn
                  fab
                  text
                  small
                  color="grey darken-2"
                  @click="prev"
              >
                <v-icon small>
                  {{ icon.mdiChevronLeft }}
                </v-icon>
              </v-btn>
              <v-btn
                  fab
                  text
                  small
                  color="grey darken-2"
                  @click="next"
              >
                <v-icon small>
                  {{ icon.mdiChevronRight }}
                </v-icon>
              </v-btn>
            </v-toolbar>
          </v-sheet>
          <v-sheet height="80vh">
            <v-calendar
                ref="calendar"
                v-model="today"
                :events="events"
                color="primary"
                :weekdays="[1, 2, 3, 4, 5, 6, 0]"
                type="week"
                first-time="06:00"
                interval-count="15"
                @click:time="clickTime"
                @click:event="clickEvent"
                @change="changeCalendar"
            >
              <template #event="event">
                <div :class="{ 'eventWrapper': !getValue(event, 'event.isEventWeek', false)}">
                  <strong>{{ getValue(event, 'event.name', '') }}</strong>
                  <span></span>
                  <br>
                  <strong>GV: {{ getValue(event, 'event.teacher_name', '') }}</strong>
                  <br>
                  <strong>Lớp: {{ getValue(event, 'event.class', '') }}</strong>
                  <br>
                  <strong>Tiết: {{ getValue(event, 'event.session', '') }}</strong>
                  <br>
                  {{ event.timeSummary() }}
                </div>
              </template>
            </v-calendar>

          </v-sheet>
        </div>
      </v-card>
      <div class="text-center">
        <v-dialog
            v-model="dialogCreate"
            width="75vw"
        >
          <v-card>
            <v-card-title class="text-h5 lighten-2">
              Đăng ký thời khoá biểu
            </v-card-title>

            <v-card-text>
              <div class="row">
                <div class="col-4">

                </div>
                <div class="col-4"></div>
                <div class="col-4"></div>
              </div>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn
                  @click=""
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
  </v-container>

</template>

<script>
import {mapMutations} from "vuex"
import moment from 'moment'
import {mdiChevronLeft, mdiChevronRight} from '@mdi/js'
import api from '../api'
import _ from 'lodash'

export default {
  name: "Calendar",
  data: () => ({
    icon: {
      mdiChevronLeft,
      mdiChevronRight
    },
    today: moment().format('YYYY-MM-DD'),
    titleCalendar: '',
    roomId: '',
    semesterId: '',
    events: [],
    dialogCreate: false,
    snackbar: {
      isShow: false,
      color: 'success',
      message: '',
    },
  }),
  methods: {
    ...mapMutations('home', [
      'changeTitle',
      'setActiveMenu',
      'setLoader'
    ]),
    resetForm(){

    },
    openDialogCreate() {
      this.resetForm()
      this.dialogCreate = true
    },
    getValue(res, data = '', df = undefined) {
      return _.get(res, data, df)
    },
    clickTime(event) {
      api.getWeekNow({date: event.date}).then(res => {
        this.openDialogCreate()

      }).catch(error => {
        let errors = _.get(error, 'response.data.error', {})
        if (Object.keys(errors).length === 0) {
          let message = _.get(error, 'response.data.message', '')
          this.showMessage('error', message)
        }
      })
    },
    clickEvent(event) {
      console.log(event)
    },
    setToday() {
      this.today = moment().format('YYYY-MM-DD')
    },
    prev() {
      this.$refs.calendar.prev()
    },
    next() {
      this.$refs.calendar.next()
    },
    setTitleCalendar(title) {
      this.titleCalendar = title
    },
    changeCalendar() {
      this.setTitleCalendar(this.$refs.calendar.title)
    },
    getScheduleCalendar(roomId = null, semesterId = null) {
      this.setLoader(true)
      let params = {
        room_id: roomId,
        semester_id: semesterId
      }
      api.getEventsCalendar(params).then((res) => {
        if (res) {
          this.events = _.get(res, 'data.data.events', [])
        }
        this.setLoader(false)
      })
    },
    showMessage(type, message) {
      this.snackbar = {
        message: message,
        color: type,
        isShow: true,
      }
      setTimeout(() => this.snackbar.isShow = false, 2000)
    },
  },
  mounted() {
    this.setTitleCalendar(this.$refs.calendar.title)
    this.changeTitle('Thời khoá biểu')
    this.setActiveMenu(7)
    this.getScheduleCalendar()
  },
  computed: {}
}
</script>

<style scoped lang="scss">
.calendarWrapper {
  .subTitle {
    color: #6c757d;
  }

  .eventWrapper {
    padding: 5px;
  }
}


</style>
