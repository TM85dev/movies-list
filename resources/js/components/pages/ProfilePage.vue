<template>
<main>
    <desktop-menu :user="profile" />
    <div class="site">
        <admin-panel v-if="profile.account_type==='admin'" />
        <div class="sorting">
            <div class="category">My Profile</div>
            <type-sorting />
        </div>
        <div ref="profileMenu" class="toggle-menu">
            <button v-for="(item, index) in items" :key="index" @click="switcherHandler(index)">
                {{ item.toUpperCase() }}
            </button>
        </div>
        <div class="toggle-result">
            <div v-for="(toggler, index) in togglers" :key="index">
                <template v-if="switchers[index]">
                    <profile-filter-menu :profile="profile" :toggler="toggler" />
                    <profile-result-list :toggler="toggler" />
                </template>
            </div>
        </div>
    </div>
</main>    
</template>

<script>
export default {
    props: [
        'auth'
    ],
    data() {
        return {
            profile: JSON.parse(this.auth),
            items: ['all', 'watched', 'to watch', 'rated'],
            togglers: ['list', 'watched', 'toWatch', 'rated'],
            switchers: [true, false, false, false],
            menu: ''
        }
    },
    mounted() {
        this.menu = [...this.$refs.profileMenu.children];
        this.menu[0].classList = 'active';
    },
    methods: {
        switcherHandler(index) {
            this.menu.forEach(item => item.classList = '');
            this.menu[index].classList = 'active'
            this.switchers = this.switchers.map(switcher => false);
            this.switchers[index] = true;
        }
    }
}
</script>

<style lang="scss" scoped>
$gray: #727271;
$lightgray: #C5C6C6;
$bg: #1A1A1A;
main {
    * {
        transition: all 0.4s;
    }
    .sorting {
        display: flex;
        justify-content: space-between;
        height: 60px;
        width: 95%;
        margin: 0 auto;
        .category {
            align-self: center;
            font-size: 1.36em;
            color: $lightgray;
            margin-top: 16px;
        }
    }
    .toggle-menu {
        display: flex;
        justify-content: space-evenly;
        background-color: $bg;
        width: 100%;
        height: 60px;
        margin: 16px auto 20px auto;
        padding-left:10px;
        width: 100%;
        button {
            background-color: $bg;
            color: $gray;
            border: 0;
            outline: none;
            &.active {
                color: lightgray;
            }
        }
    }
}
@media screen and (min-width: 375px) {
    main {
        .sorting .category {
            font-size: 1.5em;
        }
        .toggle-menu {
            font-size: 1.2em;
        }
    }
}
@media screen and (min-width: 668px) {
    main .toggle-menu {
        justify-content: start;
        button {
            margin-left: 16px;
            margin-right: 1vw;
        }
    }
}
@media screen and (min-width: 1024px) {
    .site {
        width: 100%;
        position: relative;
    }
    main {
        display: flex;
    }
}
</style>