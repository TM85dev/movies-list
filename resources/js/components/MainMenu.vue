<template>
    <div>
        <overlay-menu />
        <nav class="main-menu">
            <div class="mobile-menu" @click="openOverlayMenu">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <slot></slot>
        </nav>
    </div>
</template>

<script>
    export default {
        methods: {
            openOverlayMenu() {
                document.querySelector('.overlay-menu').style.display = 'flex';
                this.$anime.timeline().add({
                    targets: '.overlay-menu',
                    minWidth: ['0px', '320px'],
                    maxWidth: ['0px', '360px'],
                    opacity: [0, 1],
                    easing: 'easeOutExpo',
                    duration: 600
                }).add({
                    targets: '.overlay-menu .close, .overlay-menu .logo, .overlay-menu ul li',
                    translateY: [-20, 0],
                    opacity: [0, 1],
                    easing: 'easeOutExpo',
                    duration: 400,
                    delay: this.$anime.stagger(100)
                })
            }
        }
    }
</script>

<style lang="scss" scoped>
$menu-bg: #1A1A1A;
$logo: url('/img/logo.png');
$search: url('/img/search.png');
nav.main-menu {
    display: flex;
    align-items: center;
    background-color: $menu-bg;
    height: 100px;
    padding: 16px 5%;
    .mobile-menu {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        width: 34px;
        height: 26px;
        cursor: pointer;
        div {
            background-color: white;
            width: 100%;
            height: 2px;
        }
    }
    .logo {
        margin: 0 5%;
        background-image: $logo;
        width: 130px;
        height: 50px;
        background-size: contain;
        background-repeat: no-repeat;
        background-position: center;
    }
    .search {
        margin: 0 0 0 auto;
        background-image: $search;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        width: 22px;
        height: 22px;
        cursor: pointer;
    }
    .sign-in {
        a {
            color: white;
        }
        white-space: nowrap;
        margin: 0 0 0 5%;
    }
}
</style>