<template>
    <div class="weekWrapper">
        <div class="row">
            <div class="col-md-12">
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                        <tr>
                            <th class="text-center" width="10%">
                                STT
                            </th>
                            <th class="text-center" width="15%">
                                Tuần
                            </th>
                            <th class="text-center">
                                Ngày bắt đầu
                            </th>
                            <th class="text-center">
                                Ngày kết thúc
                            </th>
                            <th class="text-center" width="15%">
                                Ghi chú
                            </th>
                            <th class="text-center" width="15%">
                                Hành động
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-if="weeks.length > 0">
                            <tr
                                v-for="(item, index) in weeks"
                                :key="index"
                            >
                                <td class="text-center">{{ index + +1 + +page.perPage * (page.currentPage - 1) }}</td>
                                <td class="text-center">
                                    {{ getValue(item, 'week', '') }}
                                </td>
                                <td class="text-center">
                                    {{ getValue(item, 'start_day', '') }}

                                </td>
                                <td class="text-center">
                                    {{ getValue(item, 'end_day', '') }}
                                </td>

                                <td class="text-center">
                                    {{ getValue(item, 'note', '') }}
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
            </div>
        </div>
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
    </div>
</template>

<script>
import api from "../api"
import {mapMutations} from "vuex";
import _ from "lodash";
import {mdiMagnify, mdiMenu, mdiPencilBoxOutline, mdiPlus, mdiTrashCanOutline, mdiViewListOutline} from "@mdi/js"

export default {
    name: "Week",
    data: () => ({
        icon: {
            mdiMagnify,
            mdiPlus,
            mdiPencilBoxOutline,
            mdiTrashCanOutline,
            mdiMenu,
            mdiViewListOutline
        },
        weeks: "",
        page: {
            currentPage: 1,
            total: 0,
            perPage: 10
        },
    }),
    props: ['semesterId'],
    methods: {
        ...mapMutations('home', [
            'setLoader'
        ]),
        getValue(res, data = '', df = undefined) {
            return _.get(res, data, df)
        },
        changePage(page) {
            this.page.currentPage = page
            this.getWeeks(this.semesterId)
        },
        getWeeks(semesterId) {
            let payload = {}
            payload.page = this.page.currentPage
            if(semesterId) {
                this.setLoader(true)
                api.getWeeksBySemester(this.semesterId, payload).then(res => {
                    this.weeks = _.get(res, 'data.data.weeks.data', [])
                    this.page.currentPage = _.get(res, 'data.data.weeks.current_page', 1)
                    this.page.total = _.get(res, 'data.data.weeks.last_page', 0)
                    this.page.perPage = _.get(res, 'data.data.weeks.per_page', 0)
                }).catch(error => {

                }).finally(() => this.setLoader(false) )
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
    },
    mounted() {
        this.getWeeks(this.semesterId)
    },
    watch: {
        semesterId(value) {
            this.getWeeks(value)
        }
    }
}
</script>

<style scoped>

</style>
