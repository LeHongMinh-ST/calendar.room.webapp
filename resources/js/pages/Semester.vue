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
                                {{ getValue(item, 'faculty_id', '') }}
                            </td>
                            <td>

                            </td>
                            <td class="text-center">

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
                        <v-select
                            dense
                            v-model="schoolYearErrors"
                            class="mt-2"
                            :error-messages="schoolYearErrors"
                            @input="$v.numberWeek.$touch()"
                            @blur="$v.numberWeek.$touch()"
                            placeholder="Chọn học năm học"
                            outlined
                        ></v-select>

                        <label><span class="font-weight-bold">Học kỳ <span class="required">*</span>:</span></label>
                        <v-select
                            dense
                            v-model="semester"
                            outlined
                            :error-messages="semesterErrors"
                            @input="$v.numberWeek.$touch()"
                            @blur="$v.numberWeek.$touch()"
                            placeholder="Chọn họ kỳ"
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
                        <label><span class="font-weight-bold">Ngày bắt đầu <span class="required">*</span>:</span></label>
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
                                    v-model="dateFormatted"
                                    :error-messages="startDateErrors"
                                    outlined
                                    class="mt-2"
                                    dense
                                    placeholder="Chọn ngày bắt đầu"
                                    v-bind="attrs"
                                    @blur="startDate = parseDate(dateFormatted)"
                                    @input="$v.numberWeek.$touch()"
                                    v-on="on"
                                />
                            </template>
                            <v-date-picker
                                v-model="startDate"
                                no-title
                                @input="menuPicker = false"
                                max=""
                                min=""
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
import {mdiMagnify, mdiMenu, mdiPencilBoxOutline, mdiPlus, mdiTrashCanOutline, mdiViewListOutline} from "@mdi/js"
import _ from "lodash"
import {required} from "vuelidate/lib/validators"

export default {
    name: "Semester",
    data: vm => ({
        menuPicker: false,
        dateFormatted: "",
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
        selectId: ""
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
            this.dialogUpdate = true
        },
        openDialogShow(item) {
            this.resetForm()

            this.dialogShow = true
        },
        formatDate (date) {
            if (!date) return null

            const [year, month, day] = date.split('-')
            return `${day}/${month}/${year}`
        },
        parseDate (date) {
            if (!date) return null

            const [month, day, year] = date.split('/')
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
        },
        handleCreateSemester() {
            this.$v.$touch()
        }
    },
    mounted() {
        this.changeTitle('Quản lý học kỳ - tuần')
        this.setActiveMenu(2)
    },
    watch: {
        startDate(value) {
            this.dateFormatted = this.formatDate(value)
        }
    }
}
</script>

<style scoped>

</style>
