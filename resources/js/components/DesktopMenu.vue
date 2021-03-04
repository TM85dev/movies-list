<template>
    <div class="desktop-menu">
        <ul>
            <li ref="link">
                <a href="/movies-list/">Home Page</a>
                <img src="/movies-list/img/home.svg" alt="home">
            </li>
            <li>
                <a v-if="user" :href="`/movies-list/profile/${user.id}`">My Lists</a>
                <a v-else href="/movies-list/profile/" @click.prevent="loginHandler">My Lists</a>
                <img src="/movies-list/img/list.svg" alt="list">
            </li>
            <li>
                <a href="/movies-list/movies">All Movies</a>
                <img src="/movies-list/img/movies.svg" alt="movies">
            </li>
            <li>
                <a href="/movies-list/series">All Series</a>
                <img src="/movies-list/img/series.svg" alt="series">
            </li>
            <li>
                <a href="/movies-list/movies-series">Movies & Series</a>
                <img src="/movies-list/img/all.svg" alt="movies & series">
            </li>
            <li>
                <a href="/movies-list/incomings">Incomings</a>
                <img src="/movies-list/img/incomings.svg" alt="incomings">
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: [
        'user'
    ],
    methods: {
        loginHandler() {
            this.$store.commit('SET_SIGN_IN_PAGE_VISIBLE');
        }
    }
}
</script>

<style lang="scss" scoped>
.desktop-menu {
    display: none;
    width: 0%;
    min-height: 100vh;
    max-width: 300px;
    position: relative;
    z-index: 2;
    transition: all 0.4s;
}
@media screen and (min-width: 1024px) {
.desktop-menu {
    display: flex;
    width: 24%;
    background-color: #1a1a1a;
    border: 1px solid;
    border-left: 0;
    border-bottom: 0;
    border-color: #009846;
    ul {
        position: relative;
        margin-top: 26px;
        text-align: right;
        line-height: 3em;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        width: 100%;
        &:after {
            content: "";
            position: absolute;
            z-index: 3;
            width: 0;
            height: 100%;
            left: 0;
            box-shadow: -20px 20px 16px 38px #1a1a1a;
            background-color: #1a1a1a;
        }
        li {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            border-bottom: 1px solid #009846;
            border-right: 0px solid #009846;
            width: 100%;
            padding: 0 18px;
            margin-top: 22px;
            transition: all 0.3s;
            a {
                color: lightgray;
                font-size: 1.2em;
                margin-right: 10px;
                text-decoration: none;
            }
            img {
                vertical-align: middle;
                border-style: none;
                filter: invert(1) brightness(0.7);
                width: 18px;
            }
            &:hover {
                border-right: 6px solid #009846;
                filter: brightness(1.2);
            }
        }
    }
}
}
@media screen and (min-width: 1366px) {
    .desktop-menu ul::after {
        width: 5%;
        box-shadow: -20px 20px 70px 50px #1a1a1a;
    }
    .desktop-menu ul li a {
        font-size: 1.3em;
    }
}
</style>