<template>
<div>
    <div class="search-page">
        <div class="top">
            <div class="close" @click="closeHandler">x</div>
            <div class="logo"></div>
            <label class="search-input">
                <div class="lupe"></div>
                <input placeholder="SEARCH" v-model="query" @input="getDataHandler" />
            </label>
        </div>
        <div class="sorting">
            <div class="category">
                <div>Search result</div>
            </div>
            <div class="type-sorting">
                <div ref='blocks' :class="$store.state.isListBlocks ? 'blocks active' : 'blocks'" @click="typeHandler('blocks')">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    
                </div>
                <div :class="$store.state.isListBlocks ? 'lines' : 'lines active'" @click="typeHandler('lines')">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <result-list />
    </div>
    <div class="aside-menu">
        <filter-menu :isSearchPage="true" />
        <div class="toggle-button" @click="toggleButton">
            <img src="/img/arrow.png" alt="arrow" />
        </div>
    </div>
    <div class="bg-search"></div>
</div>
</template>

<script>
export default {
    data() {
        return {
            isOpenAside: false,
            query: this.$store.state.query,
            location: window.location.pathname.slice(1)
        }
    },
    mounted() {
        this.$store.commit('SET_SELECT', 'all');
        this.$store.commit('RESET_FILTER');
        this.$store.dispatch('fetchSearchData');
        this.$anime.timeline().add({
            targets: '.search-page, .bg-search',
            translateX: [-20, 0],
            opacity: [0, 0.9],
            easing: 'easeOutExpo',
            duration: 300
        }).add({
            targets: '.search-page>.top>*',
            translateY: [-20, 0],
            opacity: [0, 1],
            easing: 'easeOutExpo',
            duration: 300
        })
    },
    beforeDestroy() {
        this.$store.commit('SET_QUERY', '');
        this.$store.commit('SET_SELECT', this.location === '' ? 'all' : this.location);
        this.$store.commit('RESET_FILTER');
        const years = this.$store.state.years;
        years.forEach(year => year.selected = false);
        this.$store.commit('SET_YEARS', years);
        this.$store.dispatch('fetchData');
    },
    methods: {
        toggleButton() {
            this.isOpenAside = !this.isOpenAside;
            this.$anime({
                targets: '.aside-menu',
                translateX: this.isOpenAside ? ['calc(-100% + 20px)','calc(0% + 0px)'] : ['calc(0% + 0px)','calc(-100% + 20px)'],
                easing: 'easeOutExpo'
            })
        },
        getDataHandler() {
            this.$store.commit('SET_QUERY', this.query);
            if(this.query.length > 2) {
                this.$store.dispatch('fetchSearchData');
            }
        },
        typeHandler(value) {
            this.$store.commit('IS_LIST_BLOCKS', value==='blocks' ? true : false);
        },
        closeHandler() {
            this.$anime.timeline().add({
                targets: '.search-page>.top>*',
                translateY: [0, -20],
                opacity: [1, 0],
                easing: 'easeOutExpo',
                duration: 300
            }).add({
                targets: '.search-page',
                translateX: [0, -20],
                opacity: [1, 0],
                easing: 'easeOutExpo',
                duration: 300
            })
            setTimeout(() => {
                this.$store.commit('SET_SEARCH_PAGE_VISIBLE');
            }, 600)
        },
    }
}
</script>

<style lang="scss" scoped>
$top-bg: #1A1A1A;
$bg: #2B2A29;
$lightgray: #C5C6C6;
$gray: #727271;
$green: #009846;
$logo: url('/img/logo.png');
$search: url('/img/search.png');
.search-page {
    width: 100%;
    min-height: 100%;
    position: absolute;
    z-index: 999;
    top: 0;
    left: 0;
    background-color: $bg;
    box-shadow: 0 0 10px 0 #2B2A29;
    .top {
        display: flex;
        flex-direction: column;
        background-color: $top-bg;
        .close {
            align-self: flex-end;
            color: $lightgray;
            margin: 3% 5% 0 0;
            cursor: pointer;
        }
        .logo {
            margin: 0 auto 3% auto;
            background-image: $logo;
            width: 130px;
            height: 50px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        .search-input {
            display: flex;
            width: 80%;
            margin: 0 auto 8% auto;
            .lupe {
                background-image: $search;
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
                width: 20px;
                height: 20px;
                cursor: pointer;
                margin-right: 5%;
            }
            input {
                border-width: 0 0 1px 0;
                border-bottom-color: $gray;
                background-color: $top-bg;
                color: $lightgray;
                font-size: 0.9em;
                outline: none;
                width: 100%;
                transition: all 0.4s ease-out;
                &:hover {
                    border-bottom-color: $lightgray;
                }
            }
        }
    }
    .sorting {
        display: flex;
        justify-content: space-between;
        height: 60px;
        width: 95%;
        margin: 0 auto;
        .category {
            margin: 6px auto 0 auto;
            font-size: 1.36em;
            color: $lightgray;
            align-self: center;
        }
        .type-sorting {
            display: flex;
            align-items: flex-end;
            justify-content: space-around;
            width: 70px;
            .blocks {
                width: 24px;
                height: 24px;
                display: flex;
                flex-wrap: wrap;
                cursor: pointer;
                div {
                    width: 8px;
                    height: 8px;
                    margin: 2px;
                    background-color: $gray;
                    transition: all 0.4s;
                }
                &.active div {
                    background-color: $lightgray;
                }
            }
            .lines {
                width: 24px;
                height: 24px;
                cursor: pointer;
                div {
                    width: 100%;
                    height: 4px;
                    margin: 2px 2px;
                    background-color: $gray;
                    transition: all 0.4s;
                    &:nth-of-type(2) {
                        margin: 4px 2px;
                    }
                }
                &.active div {
                    background-color: $lightgray;
                }
            }
        }
    }
}
.aside-menu {
    margin-top: 20px;
    position: fixed;
    display: flex;
    left: 0;
    width: 100%;
    z-index: 999;
    transform: translateX(calc(-100% + 20px));
    .toggle-button {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 16px;
        width: 26px;
        background-color: $green;
        img {
            filter: brightness(0.1);
            width: 10px;
            transform: rotateY(180deg);
        }
    }
}
.bg-search {
    position: fixed;
    background-color: $bg;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 998;
}
</style>