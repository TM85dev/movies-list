<template>
    <main>
        <desktop-menu :user="user" />
        <div class="site">
            <incoming-slider />
            <div class="sorting">
                <div class="category-sorting">
                    <span class='active'>News</span>
                </div>
                <type-sorting />
            </div>
            <filter-menu :isHomePage="true" />
            <result-list :isHomePage="true" />
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
            user: JSON.parse(this.auth)
        }
    },
    beforeMount() {
        this.$store.commit('SET_SELECT', 'news');
        this.$store.commit('SET_DISPLAY_SORT', '0 TO 9');
        this.$store.commit('SET_SORT', 'asort-year');
    },
    mounted() {
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
        .category-sorting {
            display: flex;
            height: 100%;
            align-items: center;
            margin-top: 6px;
            span {
                font-size: 1.36em;
                color: $lightgray;
                margin: 0 10px;
                text-decoration: none;
                transition: all 0.4s;
            }
        }
    }
}
@media screen and (min-width: 375px) {
    main {
        .sorting {
            .category-sorting {
                margin-top: 10px;
                span {
                    font-size: 1.5em;
                }
            }
            .type-sorting {
                width: 80px;
                .blocks,
                .lines {
                    transform: scale(1.13);
                }
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
        .category-sorting {
            margin-top: 16px;
            span {
                font-size: 1.6em;
            }
        } 
    }
}
@media screen and (min-width: 1366px) {

}
</style>