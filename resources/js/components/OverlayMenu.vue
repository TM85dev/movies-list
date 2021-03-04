<template>
    <nav class='overlay-menu'>
        <div class="close" @click="closeOverlayMenu"></div>
        <div class="logo"></div>
        <ul>
            <li><a href="/movies-list/" :class="activeLink('/movies-list/')">Home</a></li>
            <li><a href="/movies-list/movies" :class="activeLink('/movies-list/movies')">Movies</a></li>
            <li><a href="/movies-list/series" :class="activeLink('/movies-list/series')">Series</a></li>
            <li><a href="/movies-list/movies-series" :class="activeLink('/movies-list/movies-series')">Movies & Series</a></li>
            <li><a href="/movies-list/incomings" :class="activeLink('/movies-list/incomings')">Incomings</a></li>
            <li>
                <a v-if="user" :href="`/movies-list/profile/${user.id}`" :class="activeLink(`/movies-list/profile/${user.id}`)">Profile</a>
                <a v-else href="/movies-list/sign-in" :class="activeLink('/movies-list/sign-in')" @click.prevent="$store.commit('SET_SIGN_IN_PAGE_VISIBLE')">Sign In</a>
            </li>
        </ul>
    </nav>    
</template>

<script>
export default {
    props: [
        'user'
    ],
    methods: {
        activeLink(path) {
            return window.location.pathname === path ? 'active' : ''
        },
        closeOverlayMenu() {
            this.$anime.timeline().add({
                targets: '.overlay-menu ul li, .overlay-menu .logo, .overlay-menu .close',
                translateY: -20,
                opacity: 0,
                easing: 'easeOutExpo',
                duration: 400,
                delay: this.$anime.stagger(100, {direction: 'reverse'})
            }).add({
                targets: '.overlay-menu',
                maxWidth: 0,
                minWidth: 0,
                easing: 'easeOutExpo',
                duration: 600
            })
            setTimeout(() => {
                document.querySelector('.overlay-menu').style.display = 'none';
            }, 1100)
        }
    }
}
</script>

<style lang="scss" scoped>
$menu-bg: #1A1A1A;
$green: #009846;
$logo: url('/movies-list/img/logo.png');
    nav.overlay-menu {
        position: fixed;
        display: none;
        flex-direction: column;
        top: 0;
        left: 0;
        height: 100%;
        background-color: $menu-bg;
        z-index: 99;
        .close {
            position: absolute;
            top: 5%;
            right: 10%;
            color: white;
            font-size: 2em;
            cursor: pointer;
            background-image: url('/movies-list/img/close.svg');
            background-size: contain;
            background-repeat: no-repeat;
            width: 20px;
            height: 20px;
            filter: brightness(0.8);
            transition: all 0.4s;
            &:hover {
                filter: brightness(1);
            }
        }
        .logo {
            margin: 100px auto 0 auto;
            background-image: $logo;
            width: 220px;
            height: 60px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
        }
        ul {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0;
            padding: 0;
            li {
                a {
                    color: white;
                    font-size: 1.9em;
                    line-height: 1.6em;
                    text-decoration: none;
                    transition: all 0.4s;
                    &:hover {
                        color: $green;
                    }
                    &.active {
                        color: $green;
                    }
                }
            }
        }
    }
</style>