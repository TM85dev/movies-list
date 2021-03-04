<template>
    <div v-if="$store.state.isLoading">
        <loader />
    </div>
    <div v-else id="profile-list-items" :class="$store.state.isListBlocks ? 'blocks-list' : 'lines-list'">
        <template v-for="(item, index) in $store.state.data">
            <profile-item-result 
                v-if="toggler==='list' 
                    & item.settings.visible" 
                :key="index" :item="item" 
            />
            <profile-item-result
                v-else-if="toggler==='watched' 
                    & item.settings.visible 
                    & item.settings.watched" 
                :key="index" 
                :item="item" 
            />
            <profile-item-result
                v-else-if="toggler==='toWatch' 
                    & item.settings.visible 
                    & item.settings.to_watch" 
                :key="index" 
                :item="item" 
            />
            <profile-item-result
                v-else-if="toggler==='rated' 
                    & item.settings.rated" 
                :key="index" 
                :item="item" 
            />
        </template>
        <div v-if="$store.state.isChunkLoading">
            <loader />
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'toggler'
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
                let element = document.querySelector('#profile-list-items');
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
        margin: 20px auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        align-items: center;
        width: 90%;
    }
    .lines-list {
        margin: 20px auto 0 auto;
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        align-items: center;
        width: 95%;
    }
</style>