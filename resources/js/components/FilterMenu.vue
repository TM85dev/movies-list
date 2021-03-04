<template>
    <div class="filter-menu">
        <div v-if="isSearchPage" class="selects">
            <div class="title" @click="dropdownHandler('selects')" @mouseenter.self="hoverHandler" @mouseleave.self="leaveHandler">
                <div class="arrow"></div>
                <button>
                    {{ $store.state.select.toUpperCase() }}
                </button>
            </div>
            <ul ref="selects">
                <li v-for="select in selects" :key="select">
                    <a :href="select" @click.prevent="selectSelects(select)">{{ select }}</a>
                </li>
            </ul>
        </div>
        <template v-if="!isHomePage || isSearchPage">
            <div class="genres">
                <div class="title" @click="dropdownHandler('genres')" @mouseenter.self="hoverHandler" @mouseleave.self="leaveHandler">
                    <div class="arrow"></div>
                    <button>
                        {{ $store.state.currentGenre==='all' ? 'ALL GENRES' : $store.state.currentGenre.toUpperCase() }}
                    </button>
                </div>
                <ul ref="genres">
                    <li v-for="genre in genres" :key="genre">
                        <a :href="genre" @click.prevent="selectGenre(genre)">{{ genre }}</a>
                    </li>
                </ul>
            </div>
            <div class="years">
                <div class="title" @click="dropdownHandler('years')" @mouseenter.self="hoverHandler" @mouseleave.self="leaveHandler">
                    <div class="arrow"></div>
                    <button>ALL YEARS</button>
                </div>
                <ul ref="years">
                    <li @click.prevent="selectAllYears"><a href="All Years">All Years</a></li>
                    <li>
                        <p>
                            <span>Select year(s):</span>
                            <span @click="removeSelectAllYears">Remove All</span> 
                        </p>
                        <div class="list-of-years">
                            <div 
                                v-for="(year, index) in $store.state.years" 
                                :key="index" 
                                :style="year.selected ? 'color: lightgray': ''"
                                @click="yearSelected(index)">
                                    {{ year.current }}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </template>
        <div class="sorts">
            <div class="title" @click="dropdownHandler('sorts')" @mouseenter.self="hoverHandler" @mouseleave.self="leaveHandler">
                <div class="arrow"></div>
                <button>
                    {{ $store.state.displaySort }}
                </button>
            </div>
            <ul ref="sorts">
                <li v-for="(sort, index) in sorts" :key="index">
                    <a :href="sort.name" @click="e => sortSelected(e, sort)">{{ sort.name }}</a> 
                    <span>{{ sort.kind }}</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'isSearchPage',
        'isOpenAside',
        'isHomePage'
    ],
    data() {
        return {
            toggles: {
                selects: false,
                genres: false,
                years: false,
                sorts: false
            },
            genres: ['All Genres', 'Action', 'Adventure', 'Biography', 'Comedy', 'Drama', 'Document'],
            sorts: [
                { name: 'A TO Z', kind: '(title)', sorting: 'asort-title'},
                { name: 'Z TO A', kind: '(title)', sorting: 'dsort-title'},
                { name: '0 TO 9', kind: '(date)', sorting: 'asort-year'},
                { name: '9 TO 0', kind: '(date)', sorting: 'dsort-year'},
            ],
            selects: ['All', 'Movies', 'Series'],
            heights: {
                selects: '',
                genres: '',
                years: '',
                sorts: ''
            },
            location: window.location.pathname.slice(1)
        }
    },
    beforeMount() {
        this.$store.commit('SET_YEARS', this.createYears);
    },
    mounted() {
        const loop = Object.keys(this.$refs);
        loop.forEach(value => {
            this.heights[value] = this.$refs[value].getBoundingClientRect().height;
            this.$refs[value].style = 'display: none; height: 0';
        });
    },
    computed: {
        createYears() {
            let yearsList = [];
            for(let i = 1970; i < 2021; i++) {
                yearsList.push({selected: true, current: i});
            }
            return yearsList;
        },
    },
    watch: {
        isOpenAside() {
            if(!this.isOpenAside) {
                this.toggleItems();
            }
        }
    },
    methods: {
        hoverHandler(e) {
            e.target.querySelector('.arrow').style = 'filter: saturate(3)';
            e.target.querySelector('button').style = 'color: white';
        },
        leaveHandler(e) {
            e.target.querySelector('.arrow').style = "filter: ''";
            e.target.querySelector('button').style = "color: ''";
        },
        toggleItems() {
            const loop = Object.keys(this.$refs);
            loop.forEach(value => this.toggles[value] = false);
            document.querySelectorAll('.filter-menu>div').forEach(item => {
                item.querySelector('.arrow').classList.remove('active');
                item.querySelector('ul').style = 'display: none; height: 0'
            });
        },
        dropdownHandler(value) {
            this.toggles[value] = !this.toggles[value];
            const arrow = document.querySelector(`.filter-menu .${value} .arrow`);
            const list = document.querySelector(`.filter-menu .${value} ul`);
            const label = document.querySelector(`.filter-menu .${value} label`);
            const button = document.querySelector(`.filter-menu .${value}`);
            button.style.pointerEvents = 'none';
            setTimeout(() => button.style.pointerEvents = '', 700);
            if(this.toggles[value]) {
                arrow.classList.add('active');
                list.style.display = 'block';
                this.$anime.timeline().add({
                    targets: list,
                    height: this.heights[value],
                    easing: 'easeOutExpo',
                    duration: 100
                }).add({
                    targets: list.querySelectorAll('li'),
                    translateY: [-20, 0],
                    opacity: [0, 1],
                    easing: 'easeOutExpo',
                    duration: 400,
                    delay: this.$anime.stagger(100)
                })
            } else {
                arrow.classList.remove('active');
                this.$anime.timeline().add({
                    targets: list.querySelectorAll('li'),
                    translateY: [0, -20],
                    opacity: 0,
                    easing: 'easeInExpo',
                    duration: 100,
                    delay: this.$anime.stagger(100, {direction: 'reverse'})
                }).add({
                    targets: list,
                    height: 0,
                    easing: 'easeInExpo',
                    duration: 100
                })
                setTimeout(() => list.style.display = 'none', 1000);
            }
        },
        /* SORT BY SELECTS */
        selectSelects(select) {
            this.dropdownHandler('selects');
            this.$store.commit('SET_SELECT', select.toLowerCase());
            if(this.isSearchPage) {
                this.$store.dispatch('fetchSearchData');
            } else {
                this.$store.dispatch('fetchData');
            }
        },
        /* SORT BY GENRE */
        selectGenre(genre) {
            this.dropdownHandler('genres');
            if(genre === 'All Genres') {
                this.$store.commit('SET_GENRE', 'all');
            } else {
                this.$store.commit('SET_GENRE', genre.toLowerCase());
            }
            if(this.isSearchPage) {
                this.$store.dispatch('fetchSearchData');
            } else {
                this.$store.dispatch('fetchData');
            }
        },
        /* SORT BY YEAR */
        yearSelected(id) {
            let years = this.$store.state.years;
            let currentYears = this.$store.state.currentYears;
            years[id].selected = !years[id].selected;
            if(currentYears === 'all') {
                currentYears = [];
            }
            if(years[id].selected) {
                currentYears = currentYears.concat([years[id].current]);
                if(currentYears.length === years.length) {
                    currentYears = 'all'
                }
            } else {
                let filtered = [];
                filtered = years.filter(year => year.selected);
                filtered = filtered.map(year => year.current);
                currentYears = filtered;
            }
            this.$store.commit('SET_YEARS', years);
            this.$store.commit('SET_CURRENT_YEARS', currentYears);
            if(this.isSearchPage) {
                this.$store.dispatch('fetchSearchData');
            } else {
                this.$store.dispatch('fetchData');
            }
        },
        selectAllYears() {
            let years = this.$store.state.years;
            let currentYears = 'all';
            years.forEach(year => year.selected = true);
            this.$store.commit('SET_YEARS', years);
            this.$store.commit('SET_CURRENT_YEARS', currentYears);
            if(this.isSearchPage) {
                this.$store.dispatch('fetchSearchData');
            } else {
                this.$store.dispatch('fetchData');
            }
        },
        removeSelectAllYears() {
            let years = this.$store.state.years;
            let currentYears = [];
            years.forEach(year => year.selected = false)
            this.$store.commit('SET_YEARS', years);
            this.$store.commit('SET_CURRENT_YEARS', currentYears);
            if(this.isSearchPage) {
                this.$store.dispatch('fetchSearchData');
            } else {
                this.$store.dispatch('fetchData');
            }
        },
        /* SORT BY ASC/DESC */
        sortSelected(e, sort) {
            e.preventDefault();
            this.$store.commit('SET_SORT', sort.sorting);
            this.$store.commit('SET_DISPLAY_SORT', sort.name);
            this.dropdownHandler('sorts');
            if(this.isSearchPage) {
                this.$store.dispatch('fetchSearchData');
            } else {
                this.$store.dispatch('fetchData');
            }
        }
    }
}
</script>

<style lang="scss" scoped>
    $lightgray: #C5C6C6;
    $green: #009846;
    $bg: #1A1A1A;
    .filter-menu {
        display: flex;
        flex-direction: column;
        margin-top: 16px;
        width: 100%;
        &>div {
            .title {
                display: flex;
                align-items:center;
                flex-wrap: wrap;
                width: 100%;
                height: 60px;
                background-color: $bg;
                border-top: 1px solid $green;
                border-bottom: 1px solid $green;
                cursor: pointer;
                .arrow {
                    width: 30px;
                    height: 20px;
                    margin: -16px 0 0 16px;
                    background-image: url('/movies-list/img/arrow.png');
                    transform: rotate(-90deg);
                    background-repeat: no-repeat;
                    background-size: contain;
                    transition: all 0.4s;
                    &.active {
                        transform: rotate(90deg);
                        margin: 16px 0 0 16px;
                    }
                }
                button {
                    background: transparent;
                    color: $lightgray;
                    outline: 0;
                    border: 0;
                    font-size: 1.4em;
                    transition: all 0.4s;
                }
            }
            ul {
                width: 100%;
                margin: 0;
                padding: 0;
                height: 100%;
                background-color: $bg;
                transition: all 0.4s;
                li {
                    padding: 16px 0 16px 50px;
                    display: block;
                    font-size: 1em;
                    line-height: 3em;
                    opacity: 0;
                    a {
                        color:$lightgray;
                        text-decoration: none;
                        transition: all 0.4s;
                        &:hover {
                            color: white;
                        }
                    }
                    .list-of-years {
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: center;
                        border: 1px solid gray;
                        padding: 0;
                        margin: 0 50px 0 0;
                        line-height: 2.6em;
                        div {
                            color: gray;
                            margin: 0 12px;
                            transition: all 0.4s;
                            cursor: pointer;
                            &:hover {
                                filter: brightness(1.4);
                            }
                        }
                    }
                    input {
                        background-color: #1a1a1a;
                        border: none;
                        border-bottom: 1px solid gray;
                        width: calc(100% - 50px);
                        padding: 10px;
                        line-height: 0em;
                        outline: 0;
                        color: lightgray;
                        transition: all 0.4s;
                        &:focus {
                            border-bottom-color: lightgray;
                        }
                    }
                    p {
                        display: flex;
                        justify-content: space-between;
                        line-height: 1.4em;
                        font-size: 0.8em;
                        color: gray;
                        margin: -20px 50px 10px 0;
                        text-align: justify;
                        span:last-of-type {
                            transition: all 0.4s;
                            cursor: pointer;
                            &:hover {
                                filter: brightness(1.6);
                            }
                        }
                    }
                }
            }
        }
        .sorts {
            ul li {
                span {
                    font-size: 0.8em;
                    color: gray;
                    text-transform: uppercase;
                }
            } 
        }
    }
@media screen and (min-width: 375px) {
    .filter-menu {
        &>div {
            .title {
                .arrow {
                    height: 24px;
                }
                button {
                    font-size: 1.5em;
                    margin-left: 10px;
                }
            }
            ul li {
                padding: 16px 0 16px 60px;
                font-size: 1.2em;
            }
        }
    }
}
@media screen and (min-width: 1024px) {
    .filter-menu {
        margin-top: 20px;
        div {
            .title {
                height: 70px;
                button {
                    font-size: 1.6em;
                }
            }
            ul li {
                font-size: 1.3em;
                span {
                    font-size: 0.9em;
                }
            }
        }
    }
}
</style>