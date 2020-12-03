/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueAnime from 'vue-animejs';
import Vuex from 'vuex';
 
Vue.use(VueAnime)
Vue.use(Vuex)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('main-menu', require('./components/MainMenu.vue').default);
Vue.component('overlay-menu', require('./components/OverlayMenu.vue').default);
Vue.component('incoming-slider', require('./components/IncomingSlider.vue').default);
Vue.component('home-page', require('./components/HomePage.vue').default);
Vue.component('filter-menu', require('./components/FilterMenu.vue').default);
Vue.component('loader', require('./components/loader.vue').default);
Vue.component('result-list', require('./components/resultList.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const store = new Vuex.Store({
    state: {
        count: 0,
        isLoading: true,
        isChunkLoading: false,
        part: 0,
        parts: 2,
        data: [],
        years: [],
        select: 'all',
        currentGenre: 'all',
        currentYears: 'all',
        currentSort: 'asort-title'
    },
    mutations: {
        SET_SORT(state, sort) {
            state.currentSort = sort;
        },
        SET_YEARS(state, years) {
            state.years = years;
        },
        SET_CURRENT_YEARS(state, years) {
            if(years !== 'all' && years.length > 1) {
                state.currentYears = years.sort();
            } else {
                state.currentYears = years;
            }
        },
        SET_GENRE(state, genre) {
            state.currentGenre = genre;
        },
        SET_SELECT(state, select) {
            state.select = select
        },
        IS_LOADING(state, loading) {
            state.isLoading = loading
        },
        IS_CHUNK_LOADING(state, loading) {
            state.isChunkLoading = loading
        },
        NEXT_PART(state, part) {
            state.part = part
        },
        PARTS_LENGTH(state, parts) {
            state.parts = parts
        },
        GET_DATA(state, data) {
            state.data = data
        },
        GET_CHUNK_DATA(state, chunk) {
            state.data = state.data.concat(chunk);
        }
    },
    actions: {
        async fetchData({ commit, dispatch, state }) {
            commit('IS_LOADING', true);
            commit('NEXT_PART', 0);
            let apiLink = `/api/${state.select}/${state.currentGenre}/${state.currentYears}/${state.currentSort}`;
            await axios.get(apiLink)
                .then(res => {
                    commit('PARTS_LENGTH', res.data.length);
                    commit('GET_DATA', res.data[state.part]);
                })
            commit('IS_LOADING', false);
            commit('NEXT_PART', 1);
        },
        async fetchChunkData({ commit, state }) {
            commit('IS_CHUNK_LOADING', true);
            document.body.style.overflow = 'hidden';
            let apiLink = `/api/${state.select}/${state.currentGenre}/${state.currentYears}/${state.currentSort}`;
            await axios.get(apiLink)
            .then(res => {
                console.log(state.part);
                let chunk = Object.values(res.data[state.part]);
                commit('PARTS_LENGTH', res.data.length);
                commit('GET_CHUNK_DATA', chunk);
                console.log(chunk);
            });
            commit('NEXT_PART', state.part + 1);
            commit('IS_CHUNK_LOADING', false);
            document.body.style.overflow = '';
        },
    }
})

const app = new Vue({
    el: '#app',
    store
});
