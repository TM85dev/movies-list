<template>
    <div class="incoming-slider">
        <div class="left-arrow" @click="arrowHandler('left')"></div>
        <p>{{ current.name }}</p>
        <p>{{ current.estimatedTime }}</p>
        <p>{{ current.releasedDate }}</p>
        <div class="right-arrow" @click="arrowHandler('right')"></div>
    </div>    
</template>

<script>
export default {
    data() {
        return {
            list: [
                {name: 'Rashomon', estimatedTime: '30 days 05:21:30', releasedDate: '30 January 2021'},
                {name: 'Dragon Ball', estimatedTime: '30 days 05:21:30', releasedDate: '30 January 2021'},
                {name: 'Digimon', estimatedTime: '30 days 05:21:30', releasedDate: '30 January 2021'},
                {name: 'Gintama', estimatedTime: '30 days 05:21:30', releasedDate: '30 January 2021'},
                {name: 'Naruto', estimatedTime: '30 days 05:21:30', releasedDate: '30 January 2021'},
            ],
            current: {name: 'Rashomon', estimatedTime: '30 days 05:21:30', releasedDate: '30 January 2021'},
            count: 0,
            timer: ''
        }
    },
    beforeMount() {
        this.currentIncoming;
    },
    computed: {
        currentIncoming: {
            get: function() {
                this.timer = setInterval(() => {
                    this.count += 1;
                    if(this.count > 4) this.count = 0;
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
                if(this.count > 4) this.count = 0;
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
                if(this.count < -4) this.count = 0;
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
$arrow: url('/img/arrow.png');
.incoming-slider {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-around;
    width: 100%;
    height: 60px;
    padding: 0 14px;
    background-color: $bg-color;
    border-top: 1px solid $green;
    border-bottom: 1px solid $green;
    p {
        font-size: 0.8em;
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
        width: 18px;
        height: 18px;
        background-image: $arrow;
        &:first-of-type {
            left: 3px;
        }
        &:last-of-type {
            right: 3px;
            transform: rotateY(180deg);
        }
    }
}
</style>