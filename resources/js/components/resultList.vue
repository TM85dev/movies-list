<template>
    <div v-if="$store.state.isLoading">
        <loader />
    </div>
    <div v-else id="list-items" :class="$store.state.isListBlocks ? 'blocks-list' : 'lines-list'">
        <template v-if="isSearchPage">
            <item-result 
                v-for="(item, index) in $store.state.searchData" 
                :key="index" 
                :item="item" 
            />
        </template>
        <template v-else>
            <item-result 
                v-for="(item, index) in $store.state.data" 
                :key="index" 
                :item="item" 
                :isHomePage="isHomePage" 
            />
        </template>
        <div v-if="$store.state.isChunkLoading" class="chunk-loading" >
            <loader />
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'isSearchPage',
        'isHomePage'
    ], 
    mounted() {
        this.$nextTick(function() {
            window.addEventListener('scroll', this.onScroll);
        });
    },
    beforeDestroy() {
        window.removeEventListener('scroll', this.onScroll);

    },
    methods: {
        onScroll() {
            if(this.$store.state.part < this.$store.state.parts) {
                let element = document.querySelector('#list-items');
                if(element) {
                    let marginTopElement = element.getBoundingClientRect().bottom;
                    let innerHeight = window.innerHeight;
                    if((marginTopElement - innerHeight) < 100) {
                        if(this.$store.state.query === '') {
                            this.$store.dispatch('fetchChunkData');
                        } else {
                            this.$store.dispatch('fetchChunkSearchData');
                        }
                    }
                }
            }
        },
    }
}
</script>

<style lang="scss" scoped>
    .blocks-list {
        & * {
            transition: all 0.4s;
        }
        margin: 20px auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        // align-items: center;
        width: 90%;
    }
    .lines-list {
        & * {
            transition: all 0.4s;
        }
        margin: 20px auto;
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        align-items: center;
        width: 95%;
    }
    .chunk-loading {
        width: 100%;
    }
@media screen and (min-width: 1024px) {
    .blocks-list {
        width: 99%;
    }
}
</style>