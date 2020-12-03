<template>
    <div v-if="$store.state.isLoading">
        <loader />
    </div>
    <div v-else id="list-items" :class="isListBlocks ? 'blocks-list' : 'lines-list'">
        <div class="movie" v-for="(movie, index) in $store.state.data" :key="index">
            <img src="/img/rashomon.png" :alt="movie.title">
            <div class="info">
                <div class="describe">
                    <p>{{ movie.title }}</p>
                    <p>{{ movie.genre }} | {{ movie.time }}min | {{ movie.release_date }}</p>
                </div>
                <div class="stars">
                    <div v-for="star in 5" :key="star"></div>
                </div>
            </div>
        </div>
        <div v-if="$store.state.isChunkLoading">
            <loader />
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'isListBlocks'
    ],
    data() {
        return {
        }
    },
    mounted() {
        this.$store.dispatch('fetchData');
        this.$nextTick(function() {
            window.addEventListener('scroll', this.onScroll);
        })
    },
    beforeDestroy() {
        window.removeEventListener(this.onScroll);
    },
    methods: {
        onScroll() {
            if(this.$store.state.part < this.$store.state.parts) {
                let element = document.querySelector('#list-items');
                if(element) {
                    let marginTopElement = element.getBoundingClientRect().bottom;
                    let innerHeight = window.innerHeight;
                    if((marginTopElement - innerHeight) < 100) {
                        this.$store.dispatch('fetchChunkData');
                    }
                }
            }
        },
    }
}
</script>

<style lang="scss" scoped>
    $bg: #1A1A1A;
    $lightgray: #C5C6C6;
    .blocks-list {
        margin: 20px auto;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-evenly;
        align-items: center;
        width: 90%;
        .movie {
            background-color: $bg;
            width: 100%;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            img {
                margin: 5%;
                width: 90%;
            }
            .info {
                display: flex;
                width: 100%;
                .describe {
                    width: 50%;
                    p:first-of-type {
                        color: white;
                        margin: 0 0 2px 10%;
                        padding-right: 20px;
                        font-size: 1em;
                        font-weight: bold;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        width: 100%;
                    }
                    p:last-of-type {
                        color: $lightgray;
                        font-size: 0.6em;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        width: 100%;
                        margin: 0 0 5% 10%;
                    }
                }
                .stars {
                    width: 50%;
                    display: flex;
                    justify-content: space-evenly;
                    align-items: center;
                    div {
                        clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
                        background-color: #C5C6C6;
                        width: 16px;
                        height: 16px;
                    }
                }
            }
        }
    }
    .lines-list {
        margin: 20px 0;
        display: flex;
        flex-direction: column;
        justify-content: space-evenly;
        align-items: center;
        width: 100%;
        .movie {
            background-color: $bg;
            width: 100%;
            margin-bottom: 10px;
            display: flex;
            flex-direction: row;
            img {
                margin: 3%;
                width: 40%;
            }
            .info {
                display: flex;
                flex-direction: column;
                width: 60%;
                .describe {
                    margin-top: 5%;
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    p:first-of-type {
                        color: white;
                        margin: 0 0 2px 10%;
                        font-size: 0.8em;
                        font-weight: bold;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        width: 100%;
                    }
                    p:last-of-type {
                        color: $lightgray;
                        font-size: 0.56em;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        overflow: hidden;
                        width: 100%;
                        margin: 0 0 5% 10%;
                    }
                }
                .stars {
                    width: 80%;
                    display: flex;
                    justify-content: space-evenly;
                    align-items: flex-start;
                    margin-left: 5%;
                    div {
                        clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
                        background-color: #C5C6C6;
                        width: 16px;
                        height: 16px;
                    }
                }
            }
        }
    }
</style>