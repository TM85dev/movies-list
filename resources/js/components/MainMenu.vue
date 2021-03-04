<template>
    <div>
        <overlay-menu :user="user" />
        <nav class="main-menu">
            <div class="mobile-menu" @click="openOverlayMenu">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="logo"></div>
            <div class="search" @click="$store.commit('SET_SEARCH_PAGE_VISIBLE')"></div>
            <div class="profile" v-if="user">
                <a :href="`/movies-list/profile/${user.id}`">PROFILE</a>
            </div>
            <div class="logout" v-if="user">
                <form method="POST" action="/movies-list/logout" @submit.prevent="logoutHandler">
                    <button type="submit"><img src="/movies-list/img/logout.png" alt="logout" /></button>
                </form>
            </div>
            <div class="sign-in" v-else>
                <a href="#" @click.prevent="$store.commit('SET_SIGN_IN_PAGE_VISIBLE')">SIGN IN</a>    
            </div>
        </nav>
        <search-page v-if="$store.state.isSearchPageVisible" />
        <sign-in-form v-if="$store.state.isSignInPageVisible" />
    </div>
</template>

<script>
    export default {
        props: [
            'auth'
        ],
        data() {
            return {
                user: this.auth ? JSON.parse(this.auth) : ''
            }
        },
        methods: {
            logoutHandler() {
                axios.post('/movies-list/logout')
                    .then(res => window.location.assign('/movies-list/'));
            },
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
$logo: url('/movies-list/img/logo.png');
$search: url('/movies-list/img/search.png');
nav.main-menu {
    * {
        transition: all 0.4s;
    }
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
        filter: brightness(0.9);
        cursor: pointer;
        &:hover {
            filter: brightness(1);
        }
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
        filter: brightness(0.9);
        &:hover {
            filter: brightness(1);
        }
    }
    .sign-in, .profile {
        a {
            color: white;
            filter: brightness(0.9);
            text-decoration: none;
            &:hover {
                filter: brightness(1);
            }
        }
        white-space: nowrap;
        margin: 0 0 0 5%;
    }
    .logout {
        button {
            margin-top: -10px;
            background-color: transparent;
            border: 0;
            outline: none;
            img {
                transition: all 0.4s;
                width: 14px;
                filter: brightness(0.8);
                &:hover {
                    filter: brightness(1);
                }
            }
        }
    }
}
@media screen and (min-width: 375px) {
    nav.main-menu {
        .mobile-menu {
            height: 31px;
        }
        .logo {
            width: 146px;
        }
        .sign-in,
        .profile {
            font-size: 1.3em;
            margin-left: 4%;
        }
        .logout {
            img {
                width: 16px;
                margin-top: -3px;
            }
        }
    }
}
@media screen and (min-width: 768px) {
    nav.main-menu {
        .logo {
            width: 160px;
        }
        .search {
            width: 26px;
            height: 26px;
        }
        .profile {
            font-size: 1.4em;
        }
        .logout button img {
            width: 19px;
        }
    }
}
@media screen and (min-width: 1024px) {
    nav.main-menu .mobile-menu {
        display: none;
    }
    nav.main-menu .logo {
        margin: 0 0 0 -3%;
        width: 18%;
    }
}
@media screen and (min-width: 1366px) {
    nav.main-menu .logo {
        height: 56px;
        width: 24%;
    }
}
</style>