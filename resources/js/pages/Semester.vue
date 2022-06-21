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
        </v-card>

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

export default {
    name: "Semester",
    data: ()=> ({
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
        resetForm() {

            this.$v.$reset()
        },
        openDialogCreate() {
            this.resetForm()
            this.dialogCreate = true
        },
    },
    mounted() {
        this.changeTitle('Quản lý học kỳ - tuần')
        this.setActiveMenu(2)
    }
}
</script>

<style scoped>

</style>
