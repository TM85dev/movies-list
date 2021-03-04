<template>
<div>
    <div class="search-page">
        <div class="top">
            <div class="close" @click="closeHandler"></div>
            <div class="logo"></div>
            <label class="search-input">
                <div ref="lupe" class="lupe"></div>
                <input ref="searchInput" placeholder="SEARCH" v-model="query" @focus="focusHandler" @blur="blurHandler" />
            </label>
        </div>
        <div class="sorting">
            <div class="category">
                <div>Search result</div>
            </div>
            <type-sorting />
        </div>
        <result-list :isSearchPage="true" />
    </div>
    <div class="aside-menu">
        <filter-menu :isSearchPage="true" :isOpenAside="isOpenAside" />
        <div class="toggle-button" @click="toggleButton">
            <img src="/movies-list/img/arrow.png" alt="arrow" />
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
            queryDelay: '',
            location: window.location.pathname.slice(1)
        }
    },
    mounted() {
        this.$refs.searchInput.focus();
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
        });
    },
    beforeDestroy() {
        this.$store.commit('SET_QUERY', '');
        this.$store.commit('RESET_FILTER');
        if(this.location === '') {
            this.$store.commit('SET_SELECT', 'news');
            this.$store.commit('SET_DISPLAY_SORT', '0 TO 9');
        } else if(this.location === 'movies-series') {
            this.$store.commit('SET_SELECT', 'all');
        } else {
            this.$store.commit('SET_SELECT', this.location);
        }
        const years = this.$store.state.years;
        years.forEach(year => year.selected = false);
        this.$store.commit('SET_YEARS', years);
    },
    watch: {
        query() {
            if(!this.$store.state.isLoading) {
                this.getDataHandler();
            }
        },

    },
    methods: {
        focusHandler() {
            this.$refs.lupe.style = "filter: brightness(1)";
        },
        blurHandler() {
            this.$refs.lupe.style = "";
        },
        toggleButton() {
            this.isOpenAside = !this.isOpenAside;
            document.querySelector('.aside-menu').style.position = this.isOpenAside ? 'absolute' : 'fixed';
            this.$anime({
                targets: '.aside-menu',
                translateX: this.isOpenAside ? ['calc(-100% + 20px)','calc(0% + 0px)'] : ['calc(0% + 0px)','calc(-100% + 20px)'],
                easing: 'easeOutExpo'
            });
        },
        getDataHandler() {
            this.$store.commit('SET_QUERY', this.query);
            if(this.query.length > 2) {
                this.$store.dispatch('fetchSearchData');
            }
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
$logo: url('/movies-list/img/logo.png');
$search: url('/movies-list/img/search.png');
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
            background-image: url('/movies-list/img/close.svg');
            background-size: contain;
            background-repeat: no-repeat;
            width: 20px;
            height: 20px;
            cursor: pointer;
            filter: brightness(0.8);
            transition: all 0.4s;
            &:hover {
                filter: brightness(1);
            }
        }
        .logo {
            margin: 1% auto 5% auto;
            background-image: $logo;
            width: 47%;
            max-width: 180px;
            height: 7vw;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        .search-input {
            display: flex;
            width: 80%;
            margin: 0 auto 8% auto;
            max-width: 600px;
            .lupe {
                background-image: $search;
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center;
                width: 20px;
                height: 20px;
                margin-right: 5%;
                cursor: pointer;
                filter: brightness(0.9);
                transition: all 0.4s;
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
            margin-right: 4%;
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
    max-width: 432px;
    z-index: 999;
    transform: translateX(calc(-100% + 20px));
    .toggle-button {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 16px;
        width: 26px;
        background-color: $green;
        cursor: pointer;
        img {
            filter: brightness(0.1);
            width: 10px;
            transform: rotateY(180deg);
        }
        &:hover {
            filter: saturate(1.3);
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
@media screen and (min-width: 375px) {
    .search-page {
        .top {
            .close {
                font-size: 1.5em;
            }
            .search-input {
                .lupe {
                    height: 24px;
                    width: 24px;
                    margin: 4px calc(6px + 1.2vw) 0 0;
                }
                input {
                    font-size: 1.1em;
                }
            }
        }
        .sorting {
            .category {
                font-size: 1.5em;
            }
            .type-sorting {
                transform: scale(1.13);
            }
        }
    }
}
@media screen and (min-width: 667px) {
    .search-page .top .close {
        width: 24px;
        height: 24px;
    }
}
@media screen and (min-width: 768px) {
    .search-page .top .logo {
        max-width: 220px;
        margin-bottom: 48px;
        max-height: 60px;
    }
    .search-page .top .search-input {
        margin-bottom: 48px;
    }
    .search-page .top .close {
        margin: 34px 56px 0 0;
    }
}
</style>