import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        id_user: '',
        id_role: '',
        status: '',
        token: localStorage.getItem('token') || '',
        user : {},
        item:{},
    },

    mutations: {

        SET_USER: (state, payload) => {
            state.item = payload;
        },

        SET_ID_ROLE: (state, payload) => {
            state.id_role = payload;
        },

        SET_ID_USER: (state, payload) => {
            state.id_user = payload;
        },

        auth_request(state){
            state.status = 'loading'
        },

        auth_success(state, token, user){
            state.status = 'success'
            state.token = token
            state.user = user
        },

        auth_error(state){
            state.status = 'error'
        },

        logout(state){
            state.status = ''
            state.token = ''
        },
    },

    actions: {

        login({commit}, { name, password, token }){
            commit('auth_success', token, {name, password})
            localStorage.setItem('token', token)
        },

        register({commit}, { name, password, token }){
            commit('auth_success', token, {name, password})
            localStorage.setItem('token', token)
        },

        logout({commit}){
            return new Promise((resolve, reject) => {
                commit('logout')
                localStorage.removeItem('token')
                resolve()
            })
        },
    },

    getters : {

        isLoggedIn: state => state.token,
        authStatus: state => state.status,

        USER: state => {
            return state.item;
        },

        ID_ROLE: state => {
            return state.id_role;
        },

        ID_USER: state => {
            return state.id_user;
        },
    }
})