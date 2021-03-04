<template>
<div class="profile-filter-menu">
    <div :class="toggler">
        <div class="title" @click="dropdownHandler()">
            <div class="arrow"></div>
            <button>
                {{ toggler === 'toWatch' ? `TO WATCH - ${$store.state[toggler].toUpperCase()}` : 
                    `${toggler.toUpperCase()} - ${$store.state[toggler].toUpperCase()}` }}
            </button>
        </div>
        <ul :ref="toggler">
            <li><a @click.prevent="selectProfile(toggler, 'all')">All</a></li>
            <li><a @click.prevent="selectProfile(toggler, 'movies')">Movies</a></li>
            <li><a @click.prevent="selectProfile(toggler, 'series')">Series</a></li>
        </ul>
    </div>

</div>
</template>

<script>
export default {
    props: [
        'toggler',
        'profile'
    ],
    data() {
        return {
            toggle: false,
            height: '',
        }
    },
    mounted() {
        this.height = this.$refs[this.toggler].getBoundingClientRect().height;
        this.$refs[this.toggler].style = 'display: none; height: 0';
        this.$store.commit('IS_PROFILE_SITE', [true, this.profile.id]);
        this.$store.dispatch('fetchData', this.$store.state[this.toggler]);
    },
    methods: {
        selectProfile(type, value) {
            this.dropdownHandler(type);
            this.$store.commit('SET_PROFILE_MENU', [type, value]);
            this.$store.dispatch('fetchData', this.$store.state[type]);
        },
        dropdownHandler() {
            this.toggle = !this.toggle;
            const arrow = document.querySelector(`.profile-filter-menu .${this.toggler} .arrow`);
            const list = document.querySelector(`.profile-filter-menu .${this.toggler} ul`);
            const label = document.querySelector(`.profile-filter-menu .${this.toggler} label`);
            const button = document.querySelector(`.profile-filter-menu .${this.toggler}`);
            button.style.pointerEvents = 'none';
            setTimeout(() => button.style.pointerEvents = '', 700);
            if(this.toggle) {
                arrow.classList.add('active');
                list.style.display = 'block';
                this.$anime.timeline().add({
                    targets: list,
                    height: this.height,
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
    }
}
</script>

<style lang="scss" scoped>
$lightgray: #C5C6C6;
$green: #009846;
$bg: #1A1A1A;
.profile-filter-menu {
    display: flex;
    flex-direction: column;
    margin-top: -1px;
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
            .arrow {
                width: 30px;
                height: 20px;
                margin: -16px 0 0 16px;
                background-image: url('/movies-list/img/arrow.png');
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
            height: 100%;
            background-color: $bg;
            transition: all 0.4s;
            li {
                padding: 16px 0 16px 50px;
                display: block;
                font-size: 1em;
                line-height: 3em;
                opacity: 0;
                color: $lightgray;
            }
        }
    }
}
@media screen and (min-width: 375px) {
    .profile-filter-menu {
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
</style>