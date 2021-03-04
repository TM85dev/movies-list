<template>
    <main>
        <desktop-menu :user="user" />
        <div class="site">
            <incoming-slider />
            <div class="sorting">
                <div class="category-sorting">
                    <a v-for="link in links" :key="link" 
                        :href="link.toLowerCase()" 
                        :class="$store.state.select===link.toLowerCase() ? 'active' : ''" 
                        @click.prevent="categoryHandler(link.toLowerCase())
                    ">{{ link }}</a>
                </div>
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
        'auth'
    ],
    data() {
        return {
            user: this.auth ? JSON.parse(this.auth) : '',
            links: ['All', 'Movies', 'Series']
        }
    },
    mounted() {
        this.$store.commit('SET_SELECT', 'all');
        this.$store.dispatch('fetchData');

    },
    methods: {
        categoryHandler(select) {
            this.$store.commit('SET_SELECT', select);
            this.$store.dispatch('fetchData');
        },
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
        .category-sorting {
            display: flex;
            height: 100%;
            align-items: center;
            margin-top: 6px;
            a {
                font-size: 1.36em;
                color: $gray;
                margin: 0 10px;
                text-decoration: none;
                transition: all 0.4s;
                &.active {
                    color: $lightgray;
                }
                &:hover {
                    color: white;
                }
            }
        }
    }
}
@media screen and (min-width: 375px) {
    main {
        display: flex;
        .site {
            width: 100%;
        }
        .sorting {
            .category-sorting {
                margin-top: 10px;
                a {
                    font-size: 1.5em;
                }
            }
        }
    }
}
@media screen and (min-width: 1366px) {

}
</style>