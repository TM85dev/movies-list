<template>
    <div ref="menu" class="aside-menu">
        <div class="admin-menu">
            <div v-for="item in menu" :key="item">
                <a :href="`/movies-list/controlls/admin/${item.toLowerCase()}`">{{ item }}</a>
            </div>
        </div>
        <div class="arrow-button" @click="toggleMenu">
            <img src="/movies-list/img/arrow.png">
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            menu: ['Users', 'Incomings', 'Posts', 'Movies', 'Series'],
            isMenuActive: false
        }
    },
    mounted() {
        this.$refs.menu.style.transform = 'translateX(calc(-100% + 20px))';
    },
    methods: {
        toggleMenu() {
            this.isMenuActive = !this.isMenuActive;
            this.$anime({
                targets: this.$refs.menu,
                translateX: this.isMenuActive ? 'calc(0% + 0px)' : 'calc(-100% + 20px)',
                easing: 'easeOutCirc',
                duration: 600
            })
        }
    }
}
</script>

<style lang="scss" scoped>
.aside-menu {
    margin-top: 58px;
    position: absolute;
    display: flex;
    left: 0;
    width: 100%;
    max-width: 300px;
    z-index: 1;
    transition: none;
    .admin-menu {
        display: flex;
        flex-direction: column;
        margin-top: 16px;
        width: 100%;
        div {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            width: 100%;
            height: 60px;
            background-color: #1A1A1A;
            border-top: 1px solid #009846;
            border-bottom: 1px solid #009846;
            a {
                text-decoration: none;
                color: #C5C6C6;
                outline: 0;
                border: 0;
                font-size: 1.4em;
                margin-left: 20px;
            }
        }
    }
    .arrow-button {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 16px;
        width: 26px;
        background-color: #009846;
        cursor: pointer;
        &:hover {
            filter: brightness(1.1);
        }
        img {
            filter: brightness(0.1);
            width: 10px;
            transform: rotateY(180deg);
        }
    }
}
</style>