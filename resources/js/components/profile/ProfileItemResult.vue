<template>
<transition @leave="destroyItem">
    <div ref="item" :class="item.settings.visible ? 'item pending' : 'item pending disable'">
        <img :src="`/movies-list/storage/${item.type}/${item.images}`" :alt="item.title">
        <div class="info">
            <div class="describe">
                <a :href="`/movies-list/${item.type}/${item.id}`">{{ item.title }}</a>
                <p>{{ item.genre }} | {{ item.time }}min | {{ item.year }}</p>
            </div>
            <div class="stars">
                <div :class="item.rate.rating >= star ? 'voted' : ''" v-for="star in 5" :key="star"></div>
            </div>
        </div>
        <div class="profile-settings">
            <label @change="checkboxHandler('watched')">
                <input type="checkbox" name="watched" :class="item.settings.watched ? 'active' : ''">
                <p>watched</p>
            </label>
            <label @change="checkboxHandler('to_watch')">
                <input type="checkbox" name="to_watch" :class="item.settings.to_watch ? 'active' : ''">
                <p>to watch</p>
            </label>
            <button @click="checkboxHandler('visible')">
                {{ item.settings.visible ? 'Delete' : '+Add' }}
            </button>
        </div>
    </div>
</transition>
</template>

<script>
export default {
    props: [
        'item',
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
    updated() {
        if(this.item.settings.visible) {
            this.$refs.item.classList = 'item active'
        } else {
            this.$refs.item.classList = 'item disable active'
        }
    },
    methods: {
        destroyItem() {
            this.$anime({
                targets: this.$refs.item,
                opacity: 0,
                scale: 0.6,
                duration: 300,
                easing: 'easeOutExpo'
            })
        },
        checkboxHandler(value) { 
            const type = `${this.item.type.slice(0, -1)}_id`;
            axios.post(`/movies-list/profile/edit-${value}`, {
                [type]: this.item.id,
                [value]: !this.item.settings[value]
            }).then(res => this.item.settings[value] = res.data[value])
            .catch(err => {})
        },
    }
}
</script>

<style lang="scss" scoped>
$bg: #1A1A1A;
$lightgray: #C5C6C6;
$green: #009846;
.blocks-list {
    .item {
        background-color: $bg;
        max-width: 410px;
        margin-left: 6px;
        margin-right: 6px;
        min-width: 250px;
        width: 98%;
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        opacity: 0;
        transition: all 0.4s;
        &.active {
            opacity: 1;
        }
        img {
            margin: 5%;
            width: 90%;
        }
        .info {
            display: flex;
            width: 100%;
            justify-content: space-between;
            .describe {
                width: 60%;
                a {
                    display: block;
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
                p {
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
                margin-right: 4%;
                width: 50%;
                display: flex;
                justify-content: space-evenly;
                align-items: center;
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
        .profile-settings {
            background-color: #3c3c3c;
        }
    }
}
.lines-list {
    .item {
        background-color: $bg;
        width: 100%;
        margin-bottom: 10px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        &.active {
            opacity: 1;
        }
        img {
            margin: 10px;
            max-width: 90px;
        }
        .info {
            display: flex;
            flex-direction: column;
            max-width: 100%;
            min-width: 60%;
            .describe {
                margin-top: 5%;
                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                a {
                    display: block;
                    color: white;
                    margin: 0 0 2px 0;
                    padding-left: 5%;
                    font-size: 1em;
                    font-weight: bold;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                    overflow: hidden;
                    width: 95%;
                }
                p {
                    color: $lightgray;
                    font-size: 0.6em;
                    white-space: nowrap;
                    text-overflow: ellipsis;
                    overflow: hidden;
                    width: 100%;
                    margin: 0 0 5% 0;
                    padding-left: 5%;
                }
            }
            .stars {
                width: 80%;
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
        .profile-settings {
            width: 100%;
            background-color: #3c3c3c;
        }
    }
}
.item * {
    transition: all 0.4s;
}
.item {
    &.disable {
        img, .info, label {
            filter: grayscale(1) opacity(0.7);
            pointer-events: none;
        }
        button {
            background-color: $green;
            border-color: #00833d;
        }
    }
}
.profile-settings {
    display: flex;
    justify-content: space-evenly;
    height: 30px;
    align-items: center;
    margin-bottom: 6px;
    label {
        display: flex;
        margin: 0;
        align-items: center;
        input {
            position: relative;
            &:before {
                content: '';
                display: flex;
                justify-content: center;
                align-items: center;
                position: absolute;
                width: 100%;
                height: 100%;
                background-color: $bg;
                box-shadow: 0 0 2px gray;
                transition: all 0.4s;
            }
            &.active:before {
                content: '\2714';
                color: $green;
            }
        }
        p {
            color: lightgray;
            margin: 0;
            font-size: 0.8em;
            margin-left: 4px;
        }
    }
        button {
            background-color: rgba(255, 66, 66, 0.6);
            color: lightgray;
            outline: none;
            font-size: 0.7em;
            border: 1px solid rgb(255, 144, 144);
            font-weight: bold;
            padding: 0 10px;
        }
}
.active {
    opacity: 1;
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
        }
    }
    .lines-list .item {
        max-height: 164px;
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
    .profile-settings {
        margin-top: 6px;
        label p, 
        button {
            font-size: 1em;
        }
    }
}
@media screen and (min-width: 568px) {
    .lines-list .item .info {
        flex-direction: row;
        align-items: center;
        width: calc(100% - 120px);
        justify-content: space-evenly;
        .describe {
            width: 60%;
            margin-top: 0;
        }
        .stars {
            max-width: 150px;
        }
    }
    .profile-settings {
        label p, 
        button {
            font-size: 1em;
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
                margin: 0 0 0 -10%;
            }
            p:first-of-type {
                padding-right: 0;
                margin-bottom: 0;
                align-self: center;
                text-align: center;
            }
        }
    }
}
</style>