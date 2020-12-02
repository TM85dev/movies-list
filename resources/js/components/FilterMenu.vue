<template>
    <div class="filter-menu">
        <div class="genres">
            <div class="title" @click="dropdownHandler('genres')">
                <div class="arrow"></div>
                <button>ALL GENRES</button>
            </div>
            <ul>
                <li v-for="genre in genres" :key="genre">
                    <a @click.prevent="selectGenre(genre)">{{ genre }}</a>
                </li>
            </ul>
        </div>
        <div class="years">
            <div class="title" @click="dropdownHandler('years')">
                <div class="arrow"></div>
                <button>ALL YEARS</button>
            </div>
            <ul>
                <li @click="selectAllYears">All Years</li>
                <li>
                    <p>
                        <span>Select year(s):</span>
                        <span @click="removeSelectAllYears">Remove All</span> 
                    </p>
                    <div class="list-of-years">
                        <div 
                            v-for="(year, index) in years" 
                            :key="index" 
                            :style="year.selected ? 'color: lightgray': ''"
                            @click="yearSelected(index)">
                                {{ year.current }}
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="sorts">
            <div class="title" @click="dropdownHandler('sorts')">
                <div class="arrow"></div>
                <button>A TO Z</button>
            </div>
            <ul>
                <li>A TO Z <span>(title)</span></li>
                <li>Z TO A <span>(title)</span></li>
                <li>0 TO 9 <span>(year)</span></li>
                <li>9 TO 0 <span>(year)</span></li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            toggles: {
                genres: false,
                years: false,
                sorts: false
            },
            years: [],
            listOfYears: [],
            genres: [
                'All Genres', 'Action', 'Adventure', 'Biography', 'Comedy', 'Drama', 'Document'
            ]                
        }
    },
    beforeMount() {
        this.years = this.createYears
    },
    computed: {
        createYears() {
            let yearsList = [];
            for(let i = 1970; i < 2021; i++) {
                yearsList.push({selected: false, current: i});
            }
            return yearsList;
        }
    },
    methods: {
        dropdownHandler(value) {
            this.toggles.genres = value==='genres' ? !this.toggles.genres : this.toggles.genres;
            this.toggles.years = value==='years' ? !this.toggles.years : this.toggles.years;
            this.toggles.sorts = value==='sorts' ? !this.toggles.sorts : this.toggles.sorts;
            const arrow = document.querySelector(`.filter-menu .${value} .arrow`);
            const list = document.querySelector(`.filter-menu .${value} ul`);
            const label = document.querySelector(`.filter-menu .${value} label`);
            if((value==='genres' && this.toggles.genres) || (value==='years' && this.toggles.years) || (value==='sorts' && this.toggles.sorts)) {
                arrow.classList.add('active');
                list.style.display = 'block';
                this.$anime.timeline().add({
                    targets: list,
                    height: ['0px', '400px'],
                    easing: 'easeOutExpo',
                    duration: 100
                }).add({
                    targets: list.querySelectorAll('li'),
                    translateY: [-20, 0],
                    opacity: 1,
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
                    height: ['400px', '0px'],
                    easing: 'easeInExpo',
                    duration: 100
                })
                setTimeout(() => {
                    list.style.display = 'none';
                }, 1200)
            }
        },
        selectGenre(genre) {
            // this.$store.commit('SET_GENRE', genre.toLowerCase());
            this.$store.commit('FILTER_BY_GENRE', genre.toLowerCase());
        },
        yearSelected(id) {
            this.years[id].selected = !this.years[id].selected;
        },
        selectAllYears() {
            this.years.forEach(year => year.selected = true)
        },
        removeSelectAllYears() {
            this.years.forEach(year => year.selected = false)
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
                .arrow {
                    width: 30px;
                    height: 20px;
                    margin: -16px 0 0 16px;
                    background-image: url('/img/arrow.png');
                    transform: rotate(-90deg);
                    background-repeat: no-repeat;
                    background-size: contain;
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
                }
            }
            ul {
                width: 100%;
                margin: 0;
                padding: 0;
                height: 0;
                display: none;
                background-color: $bg;
                transition: all 0.4s;
                li {
                    padding: 16px 0 16px 50px;
                    display: block;
                    font-size: 1em;
                    line-height: 3em;
                    opacity: 0;
                    color: $lightgray;
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
                            margin: 0 16px;
                            transition: all 0.4s;
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
                    }
                }
            }
        }
        .sorts {
            ul li span {
                font-size: 0.8em;
                color: gray;
                text-transform: uppercase;
            }
        }
    }
</style>