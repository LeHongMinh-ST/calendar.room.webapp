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
                            placeholder="Nhập tên hoặc mã khoa để tìm kiếm"
                            clearable
                            @click:clear="handleClearSearch"
                            @keydown.enter.n.native="handleGetFaculties"
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
                            <td>{{ getValue(item, 'faculty_id', '') }}</td>
                            <td>{{ getValue(item, 'name', '') }}</td>
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
    </v-container>
</template>

<script>
import {mapMutations} from "vuex"
import {mdiMagnify, mdiMenu, mdiPencilBoxOutline, mdiPlus, mdiTrashCanOutline} from '@mdi/js'
import _ from "lodash"
import api from '../api'

export default {
    name: "Department",
    data: () => ({
        icon: {
            mdiMagnify,
            mdiPlus,
            mdiPencilBoxOutline,
            mdiTrashCanOutline,
            mdiMenu
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
        }
    }),
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
                this.snackbar = {
                    message: "Lỗi! không tải được dữ liệu",
                    color: "error",
                    isShow: true,
                }
                this.setLoader(false)
            })
        },
        changePage(page) {
            this.page.currentPage = page
            this.getFaculties()
        },
        handleClearSearch(){
            this.search = ""
            this.handleGetFaculties()
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
</style>
