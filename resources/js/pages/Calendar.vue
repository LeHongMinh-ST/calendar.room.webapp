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
                @change="changeCalendar"
            />

          </v-sheet>
        </div>
      </v-card>

    </div>
  </v-container>

</template>

<script>
import {mapMutations} from "vuex"
import moment from 'moment'
import {mdiChevronLeft, mdiChevronRight} from '@mdi/js'

export default {
  name: "Calendar",
  data: () => ({
    icon: {
      mdiChevronLeft,
      mdiChevronRight
    },
    today: moment().format('YYYY-MM-DD'),
    titleCalendar: '',
    events: [
      {
        name: 'Weekly Meeting',
        start: '2022-01-07 09:00',
        end: '2022-01-07 10:00',
      },
      {
        name: `Thomas' Birthday`,
        start: '2022-01-10',
      },
      {
        name: 'Mash Potatoes',
        start: '2022-01-09 12:30',
        end: '2022-01-09 15:30',
      },
    ],
  }),
  methods: {
    ...mapMutations('home', [
      'changeTitle',
      'setActiveMenu'
    ]),
    clickTime(event) {
      console.log(event)
    },
    setToday() {
      this.today = moment().format('YYYY-MM-DD')
    },
    prev() {
      console.log(this.$refs.calendar.prev())
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
    }
  },
  mounted() {
    this.setTitleCalendar(this.$refs.calendar.title)
    this.changeTitle('Thời khoá biểu')
    this.setActiveMenu(7)
  },
  computed: {}
}
</script>

<style scoped lang="scss">
.calendarWrapper {
  .subTitle {
    color: #6c757d;
  }
}


</style>
