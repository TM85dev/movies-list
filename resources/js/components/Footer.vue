<template>
    <footer>
        <div class="top" @click="toTop">
            <img src="/movies-list/img/arrow.png" alt="top">
            TO TOP
        </div>
    </footer>    
</template>

<script>
export default {
    methods: {
        toTop() {
            window.scrollTo({top: 0, behavior: 'smooth'})
        }
    }
}
</script>

<style lang="scss" scoped>
footer {
    * {
        transition: all 0.4s;
    }
    width: 100%;
    height: 60px;
    border-top: 1px solid #00000061;
    background-color: #1a1a1a;
    box-shadow: -1px 0px 16px 11px #0000006b;
    position: relative;
    z-index: 2;
    .top {
        width: fit-content;
        margin-right: 40px;
        margin-left: auto;
        margin-top: 20px;
        color: gray;
        font-size: 0.8em;
        cursor: pointer;
        &:hover {
            filter: brightness(1.2);
        }
        img {
            transform: rotate(90deg);
            width: 9px;
            margin-right: 6px;
        }
    }
}
@media screen and (min-width: 1366px) {
    footer {
        height: 66px;
        .top {
            font-size: 1em;
            margin-top: 16px;
            img {
                width: 12px;
                margin-right: 10px;
            }
        }
    }
}
</style>