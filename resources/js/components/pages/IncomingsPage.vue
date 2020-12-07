<template>
    <div class="incomings">
        <div class="header">
            <h1>Incomings</h1>
        </div>
        <div class="list">
            <loader v-if="isLoading" />
            <div v-else v-for="(incoming, index) in incomings" :key="index" class="incoming">
                <div>
                    <img src="/img/rashomon.png" />
                    <p>{{ incoming.title }}</p>
                </div>
                <div>
                    <p>{{ estimatedTimeDisplay[index] }}</p>
                    <p>{{ releaseDataDisplay[index] }}</p>
                    <!-- <p>30 days 05:21:30</p>
                    <p>30 January 2021</p> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLoading: true,
            incomings: [],
            estimatedTimeDisplay: [],
            releaseDataDisplay: []
        }
    },
    mounted() {
        axios.get('/api/incomings')
            .then(res => this.incomings = res.data)
            .finally(() => {
                this.isLoading = false
                this.releaseDataDisplay = this.incomings.map(incoming => this.releaseDate(incoming.release_date));
            });
        setInterval(() => {
            this.estimatedTimeDisplay = this.incomings.map(incoming => this.estimatedTime(incoming.release_date));
        }, 1000)
    },
    methods: {
        estimatedTime(time) {
            const current = Date.now();
            const release = new Date(time).getTime();
            const estimated = release - current;
            const formatDate = (value) => value < 10 ? `0${value}` : value;
            const days = Math.floor(estimated / (1000 * 60 * 60 * 24));
            const hours = Math.floor((estimated % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((estimated % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((estimated % (1000 * 60)) / 1000);
            const timer = `${days}days ${formatDate(hours)}:${formatDate(minutes)}:${formatDate(seconds)}`;
            return seconds < 0 ? '0 days 0:00:00' : timer;
        },
        releaseDate(date) {
            const year = new Date(date).getUTCFullYear();
            const day = new Date(date).getUTCDate();
            const dif = new Date(date).getTime() - Date.now();
            let month = new Date(date).getUTCMonth();
            const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            for(let i = 0; i < 12; i ++) {
                if(month === i) month = months[i];
            }
            return dif <= 0 ? 'released' : `${day} ${month} ${year}`
        }
    }
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
                div:first-of-type {
                    display: flex;
                    width: 70%;
                    img {
                        width: 54%;
                        margin: 8px;
                    }
                    p {
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
                        font-size: 0.65em;
                        margin: 0;
                        font-weight: bold;
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
</style>