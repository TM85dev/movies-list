<template>
    <div class="incoming-slider">
        <div class="left-arrow" @click="arrowHandler('left')"></div>
        <p>{{ current.title }}</p>
        <p>{{ current.estimatedTime }}</p>
        <p>{{ current.releaseDate }}</p>
        <div class="right-arrow" @click="arrowHandler('right')"></div>
    </div>    
</template>

<script>
export default {
    data() {
        return {
            list: [],
            current: {name: '', estimatedTime: '', releaseDate: '', image: ''},
            count: 0,
            timer: '',
            data: []
        }
    },
    beforeMount() {
        this.currentIncoming;
    },
    mounted() {
        axios.get('/movies-list/api/incomings')
            .then(res => this.data = res.data).finally(() => {
                this.list = this.data.map((item, index) => {
                    this.$store.commit('ESTIMATED_TIME', item.release_date);
                    this.$store.commit('RELEASE_DATE', item.release_date);
                    return {
                        title: item.title,
                        estimatedTime: this.$store.state.estimatedTimeDisplay,
                        releaseDate: this.$store.state.releaseDateDisplay,
                    }
            });
            this.current = this.list[0]
        });
        setInterval(() => {
            this.list.forEach((item, index) => {
                this.$store.commit('ESTIMATED_TIME', this.data[index].release_date);
                this.$store.commit('RELEASE_DATE', this.data[index].release_date);
                item.estimatedTime = this.$store.state.estimatedTimeDisplay;
                item.releaseDate = this.$store.state.releaseDateDisplay
            })
        }, 1000)
    },
    computed: {
        currentIncoming: {
            get: function() {
                this.timer = setInterval(() => {
                    this.count += 1;
                    if(this.count >= this.list.length) this.count = 0;
                    setTimeout(() => {
                        this.current = this.list[this.count];
                    }, 400)
                    this.animation('right');
                }, 10000)
            },
            set: function(direction) {
                this.timer = setInterval(() => {
                    this.sliderMechanic(direction);
                    this.animation(direction);
                }, 10000)
            }
        }
    },
    methods: {
        sliderMechanic(direction) {
            if(direction==='right') {
                this.count += 1;
                if(this.count >= this.list.length) this.count = 0;
                if(this.count >= 0) setTimeout(() => {
                    this.current = this.list[this.count];
                }, 400); 
                else {
                    const reversed = this.list.length + this.count;
                    setTimeout(() => {
                        this.current = this.list[reversed];
                    }, 400)
                }
            } else {
                this.count -= 1;
                if(this.count < -this.list.length) this.count = 0;
                if(this.count >= 0) setTimeout(() => {
                    this.current = this.list[this.count];
                }, 400); 
                else {
                    const reversed = this.list.length + this.count;
                    setTimeout(() => {
                        this.current = this.list[reversed];
                    }, 400)
                }
            }
        },
        animation(direction) {
            this.$anime.timeline().add({
                targets: '.incoming-slider p',
                translateX: direction === 'left' ? [0, -20] : [0, 20],
                opacity: 0,
                easing: 'easeOutExpo'
            }).add({
                targets: '.incoming-slider p',
                translateX: direction === 'left' ? [20, 0] : [-20, 0],
                opacity: 1,
                easing: 'easeOutExpo'
            })
        },
        arrowHandler(direction) {
            clearInterval(this.timer);
            this.sliderMechanic(direction);
            this.animation(direction);
            this.currentIncoming = direction;
        },
    }
}
</script>

<style lang="scss" scoped>
$green: #009846;
$bg-color: #1A1A1A;
$arrow: url('/movies-list/img/arrow.png');
.incoming-slider {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-around;
    width: 100%;
    height: 50px;
    padding: 0 14px;
    background-color: $bg-color;
    border-top: 1px solid $green;
    border-bottom: 1px solid $green;
    p {
        font-size: 0.7em;
        margin: 0;
        &:first-of-type {
            color: white;
            font-weight: 600;
        }
        &:nth-of-type(2) {
            color: red;
        }
        &:last-of-type {
            color: orange;
        }
    }
    div {
        position: absolute;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        width: 16px;
        height: 16px;
        background-image: $arrow;
        transition: all 0.4s;
        cursor: pointer;
        &:hover {
            filter: saturate(2);
        }
        &.left-arrow {
            left: 3px;
        }
        &.right-arrow {
            right: 3px;
            transform: rotateY(180deg);
        }
    }
}
@media screen and (min-width: 375px) {
    .incoming-slider {
        div.left-arrow {
            left: 6px;
        }
        div.right-arrow {
            right: 6px;
        }
        p {
            font-size: 0.9em;
        }
    }
}
@media screen and (min-width: 768px) {
        .incoming-slider .left-arrow {
            margin-left: 4%;
            width: 20px;
            height: 20px;
        }
        .incoming-slider .right-arrow {
            margin-right: 4%;
            width: 20px;
            height: 20px;
        }
        .incoming-slider p:first-of-type {
            margin-left: 7%;
        }
        .incoming-slider p:last-of-type {
            margin-right: 7%;
        }
}
@media screen and (min-width: 1024px) {
    .incoming-slider {
        height: 60px;
        p {
            font-size: 1em;
        }
    }
}
</style>