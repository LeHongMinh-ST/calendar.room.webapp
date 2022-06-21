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
                                :class="{ facultyActive: getValue(item, 'is_active', false) }"
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
                      <v-list-item @click="">
                        <v-list-item-title>
                          <v-icon>
                            {{ icon.mdiPencilBoxOutline }}
                          </v-icon>
                          Chỉnh sửa
                        </v-list-item-title>
                      </v-list-item>
                      <v-list-item @click="">
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
  </div>
</template>

<script>
import _ from "lodash";
import {mdiMagnify, mdiMenu, mdiPencilBoxOutline, mdiPlus, mdiTrashCanOutline, mdiViewListOutline} from "@mdi/js";
import api from "../api";
import {mapMutations} from "vuex";

export default {
  name: "Department",
  data: ()=>({
    icon: {
      mdiMagnify,
      mdiPlus,
      mdiPencilBoxOutline,
      mdiTrashCanOutline,
      mdiMenu,
      mdiViewListOutline
    },
    departments: '',
    snackbar: {
      isShow: false,
      color: 'success',
      message: '',
    }
  }),
  props: ['facultyId'],
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
            this.departments = _.get(res, 'data.data.departments.data', [])
          }
          this.setLoader(false)
        }).catch(error => {
          let errors = _.get(error.response, 'data.error', {})
          if (Object.keys(errors).length === 0) {
            let message = _.get(error.response, 'data.message', '')
            this.showMessage('error', message)
          }
          this.setLoader(false)

        })
      }
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
    this.handleGetDepartmentByFaculty(this.facultyId)
  }
}
</script>

<style scoped>

</style>