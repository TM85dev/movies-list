<template>
    <main>
        <!-- <incoming-slider /> -->
        <div class="sorting">
            <div class="category">Movies</div>
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
        <filter-menu />
        <result-list />
    </main>    
</template>

<script>
export default {
    mounted() {
        this.$store.commit('SET_SELECT', 'movies');
        this.$store.dispatch('fetchData');
    },
    methods: {
        typeHandler(value) {
            this.isListBlocks = value==='blocks' ? true : false;
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
        .category {
            align-self: center;
            font-size: 1.36em;
            color: $lightgray;
            margin: 6px 10px 0 10px;
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