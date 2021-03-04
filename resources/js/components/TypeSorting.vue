<template>
    <div class="type-sorting">
        <div 
            :class="$store.state.isListBlocks ? 'blocks active' : 'blocks'" 
            @click="typeHandler('blocks')"
            @mouseenter.self="hoverHandler"
            @mouseleave.self="leaveHandler"
        >
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div 
            :class="$store.state.isListBlocks ? 'lines' : 'lines active'"
            @click="typeHandler('lines')"
            @mouseenter="hoverHandler"
            @mouseleave="leaveHandler"
        >
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>
</template>

<script>
export default {
    methods: {
        typeHandler(value) {
            this.$store.commit('IS_LIST_BLOCKS', value==='blocks' ? true : false);
        },
        hoverHandler(e) {
            e.target.style = "filter: brightness(1.6)"
        },
        leaveHandler(e) {
            e.target.style = "filter: ''"
        }
    }
}
</script>

<style lang="scss" scoped>
$gray: #727271;
$lightgray: #C5C6C6;
.type-sorting {
    * {
        transition: all 0.4s;
    }
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
</style>