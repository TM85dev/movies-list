<template>
    <main>
        <!-- <incoming-slider /> -->
        <desktop-menu :user="user" />
        <div class="site">
            <div class="sorting">
                <div class="category">{{ type }}</div>
                <type-sorting />
            </div>
            <filter-menu />
            <result-list />
        </div>
    </main>    
</template>

<script>
export default {
    props: [
        'type',
        'auth'
    ],
    data() {
        return {
            user: this.auth ? JSON.parse(this.auth) : ''
        }
    },
    mounted() {
        this.$store.commit('SET_SELECT', this.type.toLowerCase());
        this.$store.dispatch('fetchData');
    }
}
</script>

<style lang="scss" scoped>
$gray: #727271;
$lightgray: #C5C6C6;
main {
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
}
@media screen and (min-width: 375px) {
    main {
        .sorting {
            .category {
                font-size: 1.5em;
            }
        }
    }
}
@media screen and (min-width: 1024px) {
    .site {
        width: 100%;
    }
    main {
        display: flex;
    }
    main .sorting {
        height: 70px;
    }
    main .sorting .category {
        font-size: 1.6em;
        margin-top: 24px;
    }
}
@media screen and (min-width: 1366px) {

}
</style>