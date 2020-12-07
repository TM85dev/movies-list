<template>
    <main>
        <incoming-slider />
        <div class="sorting">
            <div class="category-sorting">
                <a v-for="link in links" :key="link" 
                    :href="link.toLowerCase()" 
                    :class="$store.state.select===link.toLowerCase() ? 'active' : ''" 
                    @click.prevent="categoryHandler(link.toLowerCase())
                ">{{ link }}</a>
            </div>
            <div class="type-sorting">
                <div :class="$store.state.isListBlocks ? 'blocks active' : 'blocks'" @click="typeHandler('blocks')">
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
        <filter-menu />
        <result-list />
    </main>    
</template>

<script>
export default {
    data() {
        return {
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
        typeHandler(value) {
            this.$store.commit('IS_LIST_BLOCKS', value==='blocks' ? true : false);
        }
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
</style>