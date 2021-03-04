<template>
    <div ref="item" class="item pending" @mouseenter="hoverHandler" @mouseleave="leaveHandler">
        <template v-if="isHomePage">
            <div class="img">
                <a :href="`/movies-list/posts/${item.id}`">
                    <img ref="image" :src="`/movies-list/storage/posts/${item.image}`" :alt="item.title">
                </a>
            </div>
            <div class="info">
                <div class="describe post">
                    <a :href="`/movies-list/posts/${item.id}`">{{ item.title }}</a>
                    <p>{{ createdAt }}</p>
                    <p>{{ description }}...</p>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="img">
                <a :href="`/movies-list/${item.type}/${item.id}`">
                    <img ref="image" :src="`/movies-list/storage/${item.type}/${item.images}`" :alt="item.title">
                </a>
            </div>
            <div class="info">
                <div class="describe">
                    <a :href="`/movies-list/${item.type}/${item.id}`">{{ item.title }}</a>
                    <p>{{ item.genre }} | {{ item.time }}min | {{ item.year }}</p>
                </div>
                <div ref="stars" class="stars">
                    <div :class="item.rate.rating >= star ? 'voted' : ''" v-for="star in 5" :key="star"></div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
export default {
    props: [
        'item',
        'isHomePage'
    ],
    mounted() {
        const items = document.querySelectorAll('.pending');
        items.forEach((item, index) => {
            setTimeout(() => {
                item.classList.remove('pending');
                item.classList.add('active');
            }, index * 200)
        })
    },
    computed: {
        description() {
            return this.item.description.slice(0, 120);
        },
        createdAt() {
            const date = new Date(this.item.created_at).toLocaleDateString();
            const arrDate = date.split('.');
            const months = ['Jan,', 'Feb.', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Dec.'];
            return `${months[arrDate[1] - 1]} ${arrDate[0]}. ${arrDate[2]}`
        }
    },
    methods: {
        hoverHandler() {
            this.$anime({
                targets: this.$refs.image,
                rotate: '10deg',
                scale: '1.2',
                filter: 'grayscale(0)'
            });
        this.$refs.item.classList.add('hovered');
        },
        leaveHandler() {
            this.$anime({
                targets: this.$refs.image,
                rotate: '0deg',
                scale: '1',
                filter: 'grayscale(0.5)'
            });
        this.$refs.item.classList.remove('hovered');
        }
    }
}
</script>

<style lang="scss" scoped>
$bg: #1A1A1A;
$lightgray: #C5C6C6;
$green: #009846;
.blocks-list .item {
    background-color: $bg;
    max-width: 320px;
    min-width: 250px;
    width: 100%;
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
    opacity: 0;
    transition: all 0.4s;
    &.active {
        opacity: 1;
    }
    .img {
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 0 0);
        margin: 5%;
        width: 90%;
        img {
            width:100%;
            filter: grayscale(0.5);
            cursor: pointer;
        }
    }
    .info {
        display: flex;
        width: 100%;
        .describe {
            width: 50%;
            transition: all 0.6s ease-in-out;
            a {
                display: block;
                color: rgb(240, 240, 240);
                margin: 0 0 2px 16px;
                padding-right: 20px;
                font-size: 1em;
                font-weight: bold;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                width: 100%;
                text-decoration: none;
                transition: all 0.6s ease-in-out;
            }
            p {
                color: $lightgray;
                font-size: 0.6em;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                width: 100%;
                margin: 0 0 16px 16px;
            }
        }
        .post {
            margin-top: -6px;
            width: 100%;
            padding: 0 5%;
            a {
                margin: 0;
                width: 49%;
                display: inline-block;
            }
            p {
                margin: 0 0 10px 0;
                white-space: normal;
                &:first-of-type {
                    color: gray;
                    width: 49%;
                    display: inline-block;
                    margin: 0 0 3px 0;
                    text-align: right;
                    white-space: nowrap;
                    transition-delay: 0.6s;
                }
            }
        }
        .stars {
            width: 50%;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            transition-delay: 0.4s;
            div {
                clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
                background-color: #C5C6C6;
                width: 16px;
                height: 16px;
                &.voted {
                    background-color: $green;
                }
            }
        }
    }
    &.hovered {
        transform: scale(1.02);
        box-shadow: 6px 8px 8px 0px #0000008f;
        .info .describe {
            width: 90%;
            p {
                width: 0%;
            }
            &.post {
                width: 100%;
                a {
                    width: 90%;
                }
                p:first-of-type {
                    width: 0%;
                    transition-delay: 0s;
                }
                p:last-of-type {
                    width: 100%;
                }
            }
        }
        .info .stars {
            width: 0%;
            transition: all 0.4s;
        }
    }
}
.lines-list .item {
    background-color: $bg;
    width: 100%;
    max-height: 116px;
    margin-bottom: 10px;
    display: flex;
    opacity: 0;
    flex-direction: row;
    transform: scale(1);
    &.active {
        opacity: 1;
    }
    .img {
        clip-path: polygon(100% 0, 100% 100%, 0 100%, 0 0);
        margin: 10px;
        max-width: 90px;
        img {
            width: 100%;
        }
    }
    .info {
        display: flex;
        flex-direction: column;
        max-width: 100%;
        min-width: 60%;
        .describe {
            margin-top: 10px;   
            margin-left: 1%;
            width: 98%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            a {
                display: block;
                color: white;
                margin: 0 0 2px 0;
                font-size: 1em;
                font-weight: bold;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                width: 95%;
                text-decoration: none;
                transition: all 0.4s;
            }
            p {
                color: $lightgray;
                font-size: 0.6em;
                white-space: nowrap;
                text-overflow: ellipsis;
                overflow: hidden;
                width: 98%;
                margin: 0;
                transition: all 0.4s;
                &:first-of-type {
                    color: gray;
                    margin-bottom: 6px;
                }
            }
        }
        .stars {
            width: 80%;
            max-width: 112px;
            display: flex;
            justify-content: space-evenly;
            align-items: flex-start;
            div {
                clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
                background-color: #C5C6C6;
                width: 16px;
                height: 16px;
                &.voted {
                    background-color: $green;
                }
            }
        }
    }
    &.hovered {
        transform: scale(1.02);
        box-shadow: rgb(0 0 0 / 30%) 2px 7px 20px 3px;
        .info .describe {
            a {
                width: 100%;
            }
            p {
                width: 0%;
            }
            &.post {
                a, p:first-of-type, p:last-of-type {
                    width: 100%;
                }
            }
        }
    }
}
@media screen and (min-width: 375px) {
    .blocks-list .item {
        .info {
            justify-content: space-between;
            .describe {
                width: 55%;
                a {
                    font-size: 1.2em;
                    padding-right: 0px;
                }
                p {
                    font-size: 0.8em;
                    text-overflow: unset;
                    overflow: unset;
                }
            }
            .stars {
                width: 35%;
                margin-top: -30px;
                margin-right: 2%;
                div {
                    width: 20px;
                    height: 20px;
                }
            }
            .post {
                width: 100%;
                a {
                    font-size: 1.5em;
                    width: 58%;
                }
                p {
                    &:first-of-type {
                        font-size: 0.9em;
                        width: 40%;
                        overflow: hidden;
                        margin: 0 0 6px 0;
                    }
                    &:last-of-type {
                        font-size: 0.9em;
                    }
                }
            }
        }
    }
    .lines-list .item {
        max-height: 116px;
        .img {
            margin: 16px;
            max-width: 120px;
        }
        .info {
            justify-content: center;
            .describe {
                a {
                    font-size: 1.2em;
                }
                p {
                    font-size: 0.8em;
                    margin-bottom: 10px;
                    padding-right: 10px;
                }
            }
            .stars {
                max-width: 150px;
                div {
                    width: 20px;
                    height: 20px;
                }
            } 
        }
    }
}
@media screen and (min-width: 568px) {
    .lines-list .item .info {
        flex-direction: row;
        align-items: center;
        width: 100%;
        justify-content: space-evenly;
        .describe {
            width: 60%;
        }
        .post {
            width: 98%;
        }
        .stars {
            max-width: 150px;
        }
    }
}
@media screen and (min-width: 667px) {
    .blocks-list .item {
        width: 40%;
    }
}
@media screen and (min-width: 768px) {
    .lines-list .item .info {
        .describe {
            flex-direction: row;
            margin: 0;
            a {
                margin: 0 0 0 0;
            }
            p:first-of-type {
                padding-right: 0;
                margin-bottom: 0;
                align-self: center;
                text-align: center;
            }
        }
        .post {
            flex-direction:column;
            margin-top: 10px;
            margin-left: 1%;
            a {
                margin: 0 0 2px 0;
            }
            p:first-of-type {
                margin-bottom: 6px;
                align-self: flex-start;
                text-align: left;
            }
        }
    }
}
@media screen and (min-width: 1024px) {
    .blocks-list .item {
        margin-left: 0.5%;
        margin-right: 0.5%;
    } 
    .lines-list .item {
        max-height: 150px;
        .img {
            max-width: 180px;
        }
        .info .describe a {
            font-size: 1.4em;
        }
        .info .describe p:first-of-type {
            font-size: 0.88em;
        }
        .info .post p {
            font-size: 0.9em;
            max-height: 40px;
            white-space: normal;
        }
    }
}
</style>