import axios from "axios"
import router from "../router"
import store from "../store"

const baseUrl = process.env.MIX_API_URL;

export const apiAxios = axios.create({
    baseURL: `${baseUrl}/api`,
    headers: {
        post: {
            'Content-Type': 'application/json'
        }
    }
})

apiAxios.interceptors.request.use(config => {
    let token = store.state.auth.accessToken
    if (token) {
        config.headers.common['Authorization'] = `Bearer ${token}`
    }
    return config
}, error => {
    return Promise.reject(error)
})

apiAxios.interceptors.response.use(undefined, (error) => {
    if (error) {
        const originalRequest = error.config;
        if (error.response.status === 401 && !originalRequest._retry && router.currentRoute.name !== 'Login') {
            store.commit('auth/updateLoginStatus', false)
            store.commit('auth/updateAuthUser', {})
            store.commit('auth/updateAccessToken', '')
            return router.push({name: 'Login'})
        }
    }
    return Promise.reject(error)
})

apiAxios.interceptors.response.use(undefined, error => {
    if (error) {
        if (error.response.status === 403) {
            if (
                error.response.data.error.error_permission &&
                error.response.data.error.error_permission.length > 0
            ) {
                return router.push({name: 'Home', params: {errorPermission: "true"}});
            }
        }
    }
    return Promise.reject(error)
})

export default {
    getAuthUser() {
        return apiAxios({
            method: 'get',
            url: '/auth/me'
        })
    },
    login(data) {
        return apiAxios({
            method: 'post',
            url: '/auth/login',
            data: data
        })
    },
    getEventsCalendar(params = null) {
        return apiAxios({
            method: 'get',
            url: '/get-events-calendar',
            params: params
        })
    },

    //faculty
    getFaculties(params = null) {
        return apiAxios({
            method: 'get',
            url: '/faculty',
            params: params
        })
    },
    createFaculty(data) {
        return apiAxios({
            method: 'post',
            url: '/faculty',
            data: data
        })
    },
    updateFaculty(data, id) {
        return apiAxios({
            method: 'put',
            url: `/faculty/${id}`,
            data: data
        })
    },
    deleteFaculty(id) {
        return apiAxios({
            method: 'delete',
            url: `/faculty/${id}`,
        })
    },

    //department
    getDepartmentByFaculty(facultyId){
        return apiAxios({
            method: 'get',
            url: `/department/get-by-faculty/${facultyId}`,
        })
    },
    createDepartment(data) {
        return apiAxios({
            method: 'post',
            url: '/department',
            data: data
        })
    },
    updateDepartment(data, id) {
        return apiAxios({
            method: 'put',
            url: `/department/${id}`,
            data: data
        })
    },
    deleteDepartment(id) {
        return apiAxios({
            method: 'delete',
            url: `/department/${id}`,
        })
    },

    //semester
    getSemesters(params = null) {
        return apiAxios({
            method: 'get',
            url: '/semester',
            params: params
        })
    },
    createSemester(data) {
        return apiAxios({
            method: 'post',
            url: '/semester',
            data: data
        })
    },
    updateSemester(data, id) {
        return apiAxios({
            method: 'put',
            url: `/semester/${id}`,
            data: data
        })
    },
    deleteSemester(id) {
        return apiAxios({
            method: 'delete',
            url: `/semester/${id}`,
        })
    },
}
