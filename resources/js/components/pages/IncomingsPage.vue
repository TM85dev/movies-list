<template>
    <div class="incomings">
        <desktop-menu :user="user" />
        <div class="site">
            <div class="header">
                <h1>Incomings</h1>
            </div>
            <div class="list">
                <loader v-if="isLoading" />
                <div v-else v-for="(incoming, index) in incomings" :key="index" class="incoming">
                    <div>
                        <img v-if="incoming.image" :style="`background-image: url(/movies-list/storage/incomings/${incoming.image})`" />
                        <p>{{ incoming.title }}</p>
                    </div>
                    <div>
                        <p>{{ estimatedTimeDisplay[index] }}</p>
                        <p>{{ releaseDateDisplay[index] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'auth'
    ],
    data() {
        return {
            user: this.auth ? JSON.parse(this.auth) : '',
            isLoading: true,
            incomings: [],
            estimatedTimeDisplay: [],
            releaseDateDisplay: []
        }
    },
    mounted() {
        axios.get('/movies-list/api/incomings')
            .then(res => this.incomings = res.data)
            .finally(() => {
                this.isLoading = false;
                this.releaseDateDisplay = this.incomings.map(incoming => {
                    this.$store.commit('RELEASE_DATE', incoming.release_date);
                    return this.$store.state.releaseDateDisplay;
                })
            });
        setInterval(() => {
                this.estimatedTimeDisplay = this.incomings.map(incoming => {
                    this.$store.commit('ESTIMATED_TIME', incoming.release_date)
                    return this.$store.state.estimatedTimeDisplay
                })
        }, 1000)
    },
}
</script>

<style lang="scss" scoped>
$lightgray: #C5C6C6;
$gray: #5B5B5B;
$green: #009846;
$bg: #1A1A1A;
.incomings {
    .header {
        display: flex;
        align-items: center;
        height: 60px;
        width: 95%;
        margin: 6px 0 6px 10px;
        h1 {
            color: #C5C6C6;
            font-size: 1.36em;
        }
    }
    .list {
        background-color: $bg;
        border-top: 1px solid $green;
        border-bottom: 1px solid $green;
        padding: 16px 3%;
        width: 100%;
        .incoming {
            display: flex;
            border: 1px solid $gray;
            margin: 26px 0;
            height: 60px;
            div:first-of-type {
                display: flex;
                width: 70%;
                img {
                    max-width: 140px;
                    width: 48%;
                    margin: 8px;
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center;
                }
                p {
                    padding-left: 2%;
                    color: $lightgray;
                    align-self: center;
                    font-weight: bold;
                    font-size: 0.8em;
                    margin: 0;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }
            }
            div:last-of-type {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-evenly;
                width: 35%;
                p {
                    width:50%;
                    font-size: 0.65em;
                    margin: 0;
                    font-weight: bold;
                    text-align: center;
                    &:first-of-type {
                        color: #E31E24;
                    }
                    &:last-of-type {
                        color: #EF7F1A;
                    }
                }
            }
        }
    }
}
@media screen and (min-width: 375px) {
    .incomings {
        .header {
            h1 {
                font-size: 1.6em;
            }
        }
        .list {
            .incoming {
                height: 80px;
                div:first-of-type p {
                    font-size: 1em;
                }
                div:last-of-type p {
                    font-size: 0.9em;
                }
            }
        }
    }
}
@media screen and (min-width: 568px) {
    .incomings .list .incoming div:first-of-type p {
        padding-left: 10%;
    }
}
@media screen and (min-width: 667px) {
    .incomings .list .incoming div {
        &:first-of-type {
            width: 45%;
        }
        &:last-of-type {
            flex-direction: row;
            width: 55%;
        }
    }
}
@media screen and (min-width: 1024px) {
    .site {
        width: 100%;
    }
    .incomings {
        display: flex;
    }
}
</style>