/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueAnime from 'vue-animejs';
import Vuex from 'vuex';
import VueLodash from 'vue-lodash';
import lodash from 'lodash';

 
Vue.use(VueAnime)
Vue.use(Vuex)
Vue.use(VueLodash, {name, lodash: lodash})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

Vue.component('main-menu', require('./components/MainMenu.vue').default);
Vue.component('overlay-menu', require('./components/OverlayMenu.vue').default);
Vue.component('incoming-slider', require('./components/IncomingSlider.vue').default);
Vue.component('filter-menu', require('./components/FilterMenu.vue').default);
Vue.component('loader', require('./components/Loader.vue').default);
Vue.component('result-list', require('./components/ResultList.vue').default);
Vue.component('item-result', require('./components/ItemResult.vue').default);
Vue.component('sign-in-form', require('./components/pages/SignInForm.vue').default);
Vue.component('type-sorting', require('./components/TypeSorting.vue').default);
Vue.component('desktop-menu', require('./components/DesktopMenu.vue').default);
Vue.component('footer-item', require('./components/Footer.vue').default);

Vue.component('home-page', require('./components/pages/HomePage.vue').default);
Vue.component('all-page', require('./components/pages/AllPage.vue').default);
Vue.component('items-page', require('./components/pages/items/ItemsPage.vue').default);
Vue.component('item-page', require('./components/pages/items/ItemPage.vue').default);
Vue.component('search-page', require('./components/pages/SearchPage.vue').default);
Vue.component('incomings-page', require('./components/pages/IncomingsPage.vue').default);
Vue.component('profile-page', require('./components/pages/ProfilePage.vue').default);
Vue.component('post-page', require('./components/pages/PostPage.vue').default);

Vue.component('profile-filter-menu', require('./components/profile/ProfileFilterMenu.vue').default);
Vue.component('profile-result-list', require('./components/profile/ProfileResultList.vue').default);
Vue.component('profile-item-result', require('./components/profile/ProfileItemResult.vue').default);

Vue.component('admin-page-users', require('./components/admin/users.vue').default);
Vue.component('admin-page-items', require('./components/admin/items.vue').default);
Vue.component('admin-page-incomings', require('./components/admin/incomings.vue').default);
Vue.component('admin-page-posts', require('./components/admin/posts.vue').default);
Vue.component('admin-panel', require('./components/admin/panel.vue').default);


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
        isSearchPageVisible: false,
        isSignInPageVisible: false,
        isListBlocks: true,
        isProfileSite: false,
        profileId: '',
        part: 0,
        parts: 2,
        data: [],
        searchData: [],
        years: [],
        watched: 'all',
        toWatch: 'all',
        rated: 'all',
        select: 'all',
        list: 'all',
        currentGenre: 'all',
        currentYears: 'all',
        currentSort: 'asort-title',
        displaySort: 'A TO Z',
        query: '',
        estimatedTimeDisplay: [],
        releaseDateDisplay: []
    },
    mutations: {
        ESTIMATED_TIME(state, time) {
            const current = Date.now();
            const release = new Date(time).getTime();
            const estimated = release - current;
            const formatDate = (value) => value < 10 ? `0${value}` : value;
            const days = Math.floor(estimated / (1000 * 60 * 60 * 24));
            const hours = Math.floor((estimated % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((estimated % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((estimated % (1000 * 60)) / 1000);
            const timer = `${days}days ${formatDate(hours)}:${formatDate(minutes)}:${formatDate(seconds)}`;
            state.estimatedTimeDisplay = seconds < 0 ? '0 days 0:00:00' : timer;
        },
        RELEASE_DATE(state, date) {
            const year = new Date(date).getUTCFullYear();
            const day = new Date(date).getUTCDate();
            const dif = new Date(date).getTime() - Date.now();
            let month = new Date(date).getUTCMonth();
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            state.releaseDateDisplay = dif <= 0 ? 'released' : `${day} ${months[month]} ${year}`;
        },
        RESET_FILTER(state) {
            state.currentGenre = 'all',
            state.currentYears = 'all',
            state.currentSort = 'asort-title',
            state.displaySort = 'A TO Z'
        },
        SET_PROFILE_MENU(state, [type, value]) {
            state[type] = value
        },
        SET_SORT(state, sort) {
            state.currentSort = sort;
        },
        SET_DISPLAY_SORT(state, sort) {
            state.displaySort = sort;
        },
        SET_YEARS(state, years) {
            state.years = years;
        },
        SET_CURRENT_YEARS(state, years) {
            if(years !== 'all' && years.length > 1) {
                state.currentYears = years.sort();
            } else if(years.length < 1) {
                state.currentYears = [0];
            }
            else {
                state.currentYears = years;
            }
        },
        SET_GENRE(state, genre) {
            state.currentGenre = genre;
        },
        SET_SELECT(state, select) {
            state.select = select
        },
        SET_SEARCH_PAGE_VISIBLE(state) {
            state.isSearchPageVisible = !state.isSearchPageVisible;
        },
        SET_SIGN_IN_PAGE_VISIBLE(state) {
            state.isSignInPageVisible = !state.isSignInPageVisible;
        },
        SET_QUERY(state, query) {
            state.query = query;
        },
        IS_PROFILE_SITE(state, [profile, profileId]) {
            state.isProfileSite = profile;
            state.profileId = profileId;
        },
        IS_LOADING(state, loading) {
            state.isLoading = loading
        },
        IS_CHUNK_LOADING(state, loading) {
            state.isChunkLoading = loading
        },
        IS_LIST_BLOCKS(state, current) {
            state.isListBlocks = current;
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
        },
        GET_SEARCH_DATA(state, data) {
            state.searchData = data;
        },
        GET_SEARCH_CHUNK_DATA(state, chunk) {
            state.searchData = state.searchData.concat(chunk);
        }
    },
    actions: {
        async fetchData({ commit, state }, type) {
            commit('IS_LOADING', true);
            commit('NEXT_PART', 0);
            let apiLink = '';
            if(state.isProfileSite) {
                apiLink = `/movies-list/api/profile/${type}/${state.profileId}`;
            } else if(state.select === 'news') {
                apiLink = `/movies-list/api/news/${state.currentSort}`
            } else {
                apiLink = `/movies-list/api/${state.select}/${state.currentGenre}/${state.currentYears}/${state.currentSort}`;
            }
            await axios.get(apiLink)
                .then(res => {
                    commit('PARTS_LENGTH', res.data.length);
                    commit('GET_DATA', res.data[state.part]);
                })
            commit('IS_LOADING', false);
            commit('NEXT_PART', 1);
        },
        async fetchChunkData({ commit, state }, type) {
            commit('IS_CHUNK_LOADING', true);
            document.body.style.overflow = 'hidden';
            let apiLink = '';
            if(state.isProfileSite) {
                apiLink = `/movies-list/api/profile/${type}/${state.profileId}`;
            } else if(state.select === 'news') {
                apiLink = `/movies-list/api/news/${state.currentSort}`
            } else {
                apiLink = `/movies-list/api/${state.select}/${state.currentGenre}/${state.currentYears}/${state.currentSort}`;
            }
            await axios.get(apiLink)
            .then(res => {
                let chunk = Object.values(res.data[state.part]);
                commit('PARTS_LENGTH', res.data.length);
                commit('GET_CHUNK_DATA', chunk);
            });
            commit('NEXT_PART', state.part + 1);
            commit('IS_CHUNK_LOADING', false);
            document.body.style.overflow = '';
        },
        async fetchSearchData({ commit, state }) {
            commit('IS_LOADING', true);
            commit('NEXT_PART', 0);
            const query = state.query.length > 0 ? state.query : '*';
            const years = state.currentYears.length > 0 ? state.currentYears : 'all';
            const apiLink = `/movies-list/api/search/${state.select}/${state.currentGenre}/${years}/${state.currentSort}/${query}`;
            await axios.get(apiLink)
                .then(res => {
                    commit('PARTS_LENGTH', res.data.length);
                    commit('GET_SEARCH_DATA', res.data[state.part]);
                })
            commit('IS_LOADING', false);
            commit('NEXT_PART', 1);
        },
        async fetchChunkSearchData({ commit, state }) {
            commit('IS_CHUNK_LOADING', true);
            document.body.style.overflow = 'hidden';
            const query = state.query.length > 0 ? state.query : '*';
            const years = state.currentYears.length > 0 ? state.currentYears : 'all';
            const apiLink = `/movies-list/api/search/${state.select}/${state.currentGenre}/${years}/${state.currentSort}/${query}`;
            await axios.get(apiLink)
            .then(res => {
                let chunk = Object.values(res.data[state.part]);
                commit('PARTS_LENGTH', res.data.length);
                commit('GET_CHUNK_SEARCH_DATA', chunk);
            });
            commit('NEXT_PART', state.part + 1);
            commit('IS_CHUNK_LOADING', false);
            document.body.style.overflow = '';
        },
        estimatedTime(time) {
            const current = Date.now();
            const release = new Date(time).getTime();
            const estimated = release - current;
            const formatDate = (value) => value < 10 ? `0${value}` : value;
            const days = Math.floor(estimated / (1000 * 60 * 60 * 24));
            const hours = Math.floor((estimated % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((estimated % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((estimated % (1000 * 60)) / 1000);
            const timer = `${days}days ${formatDate(hours)}:${formatDate(minutes)}:${formatDate(seconds)}`;
            return seconds < 0 ? '0 days 0:00:00' : timer;
        },
        releaseDate(date) {
            const year = new Date(date).getUTCFullYear();
            const day = new Date(date).getUTCDate();
            const dif = new Date(date).getTime() - Date.now();
            let month = new Date(date).getUTCMonth();
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            for(let i = 0; i < 12; i ++) {
                if(month === i) month = months[i];
            }
            return dif <= 0 ? 'released' : `${day} ${month} ${year}`
        }
    }
})

const app = new Vue({
    el: '#app',
    store
});
