<template>
    <div class="movie pending" >
        <img src="/img/rashomon.png" :alt="movie.title">
        <div class="info">
            <div class="describe">
                <p>{{ movie.title }}</p>
                <p>{{ movie.genre }} | {{ movie.time }}min | {{ movie.year }}</p>
            </div>
            <div class="stars">
                <div v-for="star in 5" :key="star"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'movie'
    ],
    mounted() {
        const movies = document.querySelectorAll('.pending');
        movies.forEach((movie, index) => {
            setTimeout(() => {
                movie.classList.remove('pending');
                movie.classList.add('active')
            }, index * 200)
        })
    }
}
</script>

<style lang="scss" scoped>
    .active {
        opacity: 1;
    }
</style>