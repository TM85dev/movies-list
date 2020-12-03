<template>
    <main>
        <div class="sorting">
            <div class="category-sorting">
                <a href="all" class="active" @click="e => categoryHandler(e, 'all')">All</a>
                <a href="movies" @click="e => categoryHandler(e, 'movies')">Movies</a>
                <a href="series" @click="e => categoryHandler(e, 'series')">Series</a>
            </div>
            <div class="type-sorting">
                <div ref='blocks' :class="isListBlocks ? 'blocks active' : 'blocks'" @click="typeHandler('blocks')">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    
                </div>
                <div :class="isListBlocks ? 'lines' : 'lines active'" @click="typeHandler('lines')">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
        </div>
        <filter-menu />
        <result-list :isListBlocks="isListBlocks" />
    </main>    
</template>

<script>
export default {
    data() {
        return {
            isListBlocks: true,
        }
    },
    methods: {
        categoryHandler(e, select) {
            e.preventDefault();
            this.$store.commit('SET_SELECT', select);
            this.$store.dispatch('fetchData');
            document.querySelectorAll('.category-sorting a').forEach(el => el.classList.remove('active'));
            e.target.classList = 'active';
        },
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