<template>
    <main class="item">
        <desktop-menu :user="auth ? user : null" />
        <div class="main">
            <div class="title">
                <h1>{{ item.title }} ({{ item.year }})</h1>
                <div class="stars" @mouseenter="starsHover" @mouseleave="starsLeave">
                    <template v-if="user[type].rated">
                        <div v-for="star in 5" :key="star" :class="rate.rating >= star ? 'voted' : ''"></div>
                    </template>
                    <template v-else>
                        <div v-for="star in 5" 
                            :key="star"
                            :class="rate.rating >= star ? 'voted' : ''"
                            @mouseenter="starHover(star)"
                            @mouseleave="starLeave"
                            @click="voteItem(star)">
                        </div>
                    </template>
                    <span v-if="isStarsHovered">
                        <p>Voted times: {{ rate.count }}</p>
                        <template v-if="auth">
                            <p>{{ user[type].rating===0 ? "You haven't voted yet" : `Your vote: ${user[type].rating}` }}</p>
                        </template>
                        <template v-else>
                            <p>Login to vote</p>
                        </template>
                    </span>
                </div>
            </div>
            <div class="j-title">
                <h2>
                    <span>{{ item.j_title }}</span>
                    <span>{{ item.romaji }}</span>
                </h2>
            </div>
            <div class="info-center">
                <div class="menu-desktop"></div>
                <div>
                    <img :src="`/movies-list/storage/${item.type}/${item.images}`" :alt="item.title">
                </div>
                <div>
                    <div>
                        <h3>Cast</h3>
                        <p ref="cast">{{ item.cast }}</p>
                    </div>
                    <div>
                        <h3>Director</h3>
                        <p ref="director">{{ item.director }}</p>
                    </div>
                    <div>
                        <h3>Producer</h3>
                        <p ref="producer">{{ item.producer }}</p>
                    </div>
                </div>
            </div>
            <div class="info-bottom">
                <div>
                    <h3>Release date</h3>
                    <p>{{ item.release_date }}</p>
                </div>
                <div>
                    <h3>Time</h3>
                    <p>{{ item.time }}min</p>
                </div>
                <div class="genre">
                    <h3>Genre</h3>
                    <p>{{ item.genre }}</p>
                </div>
                <div v-if="auth" class="add-list">
                    <div class="add-item" @click="showInProfile">
                        <i></i>
                        <p ref="message">{{ message }}</p>
                    </div>
                    <p>Add to my list</p>
                </div>
                <div class="margin-right"></div>
            </div>
            <div class="info-bottom-shadow"></div>
            <div class="description">
                <div ref="text" class="text">
                    <h2>Description</h2>
                    <p ref="description"></p>
                </div>
                <div ref="trailer" class="trailer">
                    <template v-if="item.trailer_link">
                        <h2>Trailer</h2>
                        <iframe :src="`https://${item.trailer_link}?showinfo=0`" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </template>
                </div>
                <div class="show-hide">
                    <div class="toggle" @click="dropDown">
                        <span>{{ isShowMore ? 'show less' : 'show more' }}</span>
                        <span class="arrow"></span>
                    </div>
                    <div class="social">
                        <div class="heart" @click="likeItem">
                            <img src="/movies-list/img/heart.png" alt="heart"
                                @mouseenter="hoverSocial"
                                @mouseleave="leaveSocial"
                            >
                            <span>{{ item.social.like }}</span>
                            <span v-if="isLikeInfoVisible" class="title">Login first</span>
                        </div>
                        <div class="eye">
                            <img src="/movies-list/img/eye.png" alt="eye">
                            <span>{{ item.social.visited }}</span>
                        </div>
                        <div class="facebook" @click="shareSocial('facebook')">
                            <img src="/movies-list/img/facebook.svg" alt="facebook"
                                @mouseenter="hoverSocial"
                                @mouseleave="leaveSocial"
                            >
                            <span>share</span>
                        </div>
                        <div class="twitter" @click="shareSocial('twitter')">
                            <img src="/movies-list/img/twitter.svg" alt="twitter"
                                @mouseenter="hoverSocial"
                                @mouseleave="leaveSocial"
                            >
                            <span>share</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="more">
                <div class="items blocks-list">
                    <item-result v-for="item in relatedItems" :key="item.id" :item="item" />
                </div>
                <div class="toggle" @click="dropDownScimilars">
                    <span class="arrow"></span>
                    <span>You might also like</span>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    props: [
        'dataMovie',
        'dataRate',
        'dataRelatedMovies',
        'auth',
        'type'
    ],
    data() {
        return {
            isStarsHovered: false,
            isShowMore: true,
            isShowScimilars: true,
            isLikeInfoVisible: false,
            item: JSON.parse(this.dataMovie),
            rate: JSON.parse(this.dataRate),
            relatedItems: JSON.parse(this.dataRelatedMovies),
            trailerHeight: '',
            textHeight: '',
            moreHeight: '',
            user: {[this.type]: {rated: true, liked: true}},
            message: ''
        }
    },
    mounted() {
        this.$refs.description.innerHTML = this.item.description;
        this.$refs.cast.innerHTML = this.item.cast;
        this.$refs.director.innerHTML = this.item.director;
        this.$refs.producer.innerHTML = this.item.producer;
        if(!this.item.trailer_link) {
            this.$refs.description.style.paddingBottom = '20px';
        }
        axios.put(`/movies-list/social/${this.item.social.id}`, {
            [this.type]: this.item.type,
            visited: this.item.social.visited + 1
        }).then(res => this.item.social.visited = res.data.visited);
        if(this.auth) {
            const user = JSON.parse(this.auth);
            const itemLength = Object.keys(user[this.type] ? user[this.type] : this.user[this.type]).length
            if(itemLength > 2) {
                this.user = JSON.parse(this.auth);
            } else {
                this.addItem();
            }
        }
    },
    methods: {
        addItem() {
            axios.post(`/movies-list/profile/add-${this.type}`, {
                    type_id: this.item.id
                }).then(res => {
                    if(Object.keys(res.data).length > 1) {
                        this.user = res.data.user
                        this.message = res.data.message
                    } else {
                        this.message = res.data.message
                    }
                }).catch(err => {})
        },
        showInProfile() {
            const type_id = `${this.type}_id`;
            axios.put(`/movies-list/profile/${this.user.id}`, {
                    [type_id]: this.item.id,
                    visible: true
                }).then(res => {
                    this.message = res.data.message
                }).catch(err => {})
                this.$refs.message.style.display = 'block';
                setTimeout(() => this.$refs.message.style.display = 'none' , 2000)
        },
        shareSocial(socialMedia) {
            const twitter = `https://twitter.com/intent/tweet?url=${window.location.href}&text=`;
            const fb = `https://www.facebook.com/sharer/sharer.php?u=${window.location.href}&title=eigasuki`;
            window.open(socialMedia==='facebook' ? fb : twitter, '_blank', 'height=240');
        },
        hoverSocial(e) {
            this.$anime({
                targets: e.target,
                scale: 1.2,
                filter: 'brightness(1.2)'
            });
        },
        leaveSocial(e) {
            this.$anime({
                targets: e.target,
                scale: 1,
                filter: 'brightness(1)'
            })
        },
        voteItem(id) {
            axios.put(`/movies-list/${this.item.type}/${this.item.id}`, {
                id: this.item.id,
                rating: id,
                user_id: this.user.id
            }).then(res => {
                this.rate = res.data[0];
                this.user[this.type].rated = true
                this.user[this.type].rating = id
            })
        },
        likeItem() {
            if(this.auth) {
                if(!this.user[this.type].liked) {
                    axios.put(`/movies-list/social/${this.item.social.id}`, {
                        [this.type]: this.item.type,
                        like: this.item.social.like + 1
                    }).then(res => {
                        this.item.social.like = res.data.like;
                        this.user[this.type].liked = true;
                    });
                }
            } else {
                this.isLikeInfoVisible = true
                setTimeout(() => this.isLikeInfoVisible = false, 2000)
            }
        },
        starsHover(e) {
            this.isStarsHovered = true
        },
        starsLeave() {
            this.isStarsHovered = false
        },
        starHover(nr) {
            const stars = document.querySelectorAll('.item .main .title .stars>div');
            this.$anime({
                targets: [...stars].slice(0, nr),
                backgroundColor: '#009846',
                easing: 'easeOutExpo',
                duration: 600,
                delay: this.$anime.stagger(50, {direction: 'reverse'})
            })
        },
        starLeave() {
            const stars = document.querySelectorAll('.item .main .title .stars>div');
            this.$anime({
                targets: [...stars].slice(this.rate.rating),
                backgroundColor: '#EBECEC',
                easing: 'easeOutExpo',
                duration: 600,
                delay: this.$anime.stagger(50)
            })
        },
        dropDown() {
            this.isShowMore = !this.isShowMore;
            this.$anime({
                targets: this.$refs.trailer,
                opacity: this.isShowMore ? 1 : 0,
                easing: 'easeOutExpo',
                duration: 600,
                delay: this.isShowMore ? 300 : 0
            })
            this.$anime({
                targets: this.$refs.trailer,
                height: this.isShowMore ? `${this.trailerHeight}px` : '0px',
                scaleY: this.isShowMore ? 1 : 0,
                easing: 'easeOutExpo',
                duration: 600,
                delay: this.isShowMore ? 0 : 300
            })
            this.$anime({
                targets: this.$refs.text,
                height: this.isShowMore ? `${this.textHeight}px` : 'calc(60px + 4vw)',
                easing: 'easeOutExpo',
                duration: 600
            })
            if(this.isShowMore) {
                this.$refs.trailer.style.display = 'block';

            } else {
                this.textHeight = this.$refs.text.getBoundingClientRect().height;
                this.trailerHeight = this.$refs.trailer.getBoundingClientRect().height;
            }
            setTimeout(() => {
                if(!this.isShowMore) {
                    this.$refs.trailer.style.display = 'none';
                }
                document.querySelector('.item .show-hide .arrow').style.transform = this.isShowMore ? 'rotate(90deg)' : 'rotate(-90deg)';
            }, 600)
        },
        dropDownScimilars() {
            if(this.isShowScimilars) {
                this.moreHeight = document.querySelector('.item .more .items').getBoundingClientRect().height;
            }
            this.isShowScimilars = !this.isShowScimilars;
            this.$anime({
                targets: '.item .more .items',
                opacity: this.isShowScimilars ? 1 : 0,
                easing: 'easeOutExpo',
                duration: 600,
                delay: this.isShowScimilars ? 300 : 0
            })
            this.$anime({
                targets: '.item .more .items',
                height: this.isShowScimilars ? `${this.moreHeight}px` : '0px',
                scaleY: this.isShowScimilars ? 1 : 0,
                margin: this.isShowScimilars ? '16px' : '0px',
                easing: 'easeOutExpo',
                duration: 600,
                delay: this.isShowScimilars ? 0 : 300
            })
            this.$anime({
                targets: '.item .more .toggle',
                margin: this.isShowScimilars ? '0 0 16px 0' : '16px 0',
                easing: 'easeOutExpo',
                duration: 600
            })
            setTimeout(() => {
                document.querySelector('.item .more .toggle .arrow').style.transform = this.isShowScimilars ? 'rotate(90deg)' : 'rotate(-90deg)';
            }, 600)
        }
    }
}
</script>

<style lang="scss" scoped>
$gray: #727271;
$lightgray: #C5C6C6;
$green: #009846;
$bg: #1A1A1A;
.item {
    * {
        transition: all 0.4s;
    }
    .main {
        .title {
            display: flex;
            height: 20px;
            align-items: center;
            justify-content: space-between;
            padding: 0 3%;
            box-shadow: 1px 20px 20px 13px #1A1A1A;
            background-color: #1A1A1A;
            margin-top: -10px;
            h1 {
                color: #EBECEC;
                font-size: 1.2em;
                font-weight: bold;
                margin: 0;  
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
                max-width: 55%;
            }
            .stars {
                position: relative;
                // width: 35%;
                width: 38%;
                margin-top: 12px;
                display: flex;
                justify-content: space-evenly;
                align-items: flex-start;
                .voted {
                    background-color: $green;
                }
                div {
                    clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
                    background-color: #EBECEC;
                    width: 18px;
                    height: 18px;
                    cursor: pointer;
                    transition:none;
                }
                span {
                    color: $lightgray;
                    background-color: $bg;
                    position: absolute;
                    top: 16px;
                    padding: 2px 4px;
                    box-shadow: 0 0 6px black;
                    z-index: 1;
                    p {
                        font-size: 0.7em;
                        margin: 0;
                    }
                }
            }
        }
        .j-title {
            color: lightgray;
            width: 100%;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow-x: hidden;
            h2 {
                color: rgb(167, 167, 167);
                margin-top: 2px;
                display: flex;
                align-items: center;
                span {
                    &:first-of-type {
                        font-size: 0.4em;
                        margin: 0 6px 0 2%;
                    }
                    &:last-of-type {
                        font-size: 0.3em;
                    }
                }
            }
        }
        .info-center {
            display: flex;
            justify-content: flex-end;
            position: relative;
            &>div:nth-of-type(2) {
                width: 100%;
                img {
                    width: 100%;
                    opacity: 0.8;
                    z-index: -1;
                    position: relative;
                }
            }
            &>div:last-of-type {
                position: absolute;
                // background-color: rgba(0, 0, 0, 0.6);
                background-color: rgb(26 26 26 / .6);
                padding: 1% 2%;
                max-width: 50%;
                max-height: 60%;
                overflow: auto;
                -ms-overflow-style: none;  /* IE and Edge */
                scrollbar-width: none;  /* Firefox */
                &::-webkit-scrollbar {
                    display: none;
                }
                &>div {
                    h3 {
                        color: #EBECEC;
                        font-weight: bold;
                        font-size: 0.8em;
                        margin: 0;
                    }
                    p {
                        color: #C5C6C6;
                        font-size: 0.7em;
                        margin: 2px 0 4px 10px;
                    }
                }
            }
        }
        .info-bottom {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            background-color: rgba(0,0,0,0.6);
            margin-top: -50px;
            height: 50px;
            // padding-left: 6%;
            &>div {
                width: 14.75%;
                &:first-of-type {
                    // width: 20.75%;
                    width: 26%;
                }
                h3 {
                    color: #EBECEC;
                    font-weight: bold;
                    font-size: 0.8em;
                    margin: 0;
                }
                p {
                    color: #C5C6C6;
                    font-size: 0.7em;
                    margin: 2px 0 0 10px;
                }
            }
            .genre {
                p {
                    color: $green;
                    font-weight: bold;
                }
            }
            .add-list {
                width: 22%;
                .add-item {
                    transform: scale(0.9);
                    position: relative;
                    border: 2px solid $green;
                    border-radius: 10px;
                    padding: 4px;
                    width: 22px;
                    margin: 0 auto;
                    cursor: pointer;
                    &:hover {
                        filter: brightness(1.2) saturate(1.5);
                    }
                    i {
                        clip-path: polygon(20% 0%, 0% 20%, 30% 50%, 0% 80%, 20% 100%, 50% 70%, 80% 100%, 100% 80%, 70% 50%, 100% 20%, 80% 0%, 50% 30%);
                        background-color: $green;
                        width: 10px;
                        height: 10px;
                        display: block;
                        transform: rotate(45deg);
                    }
                    p {
                        position: absolute;
                        color: $lightgray;
                        background-color: $bg;
                        font-size: 0.6em;
                        width: 110px;
                        white-space: break-spaces;
                        right: -30px;
                        top: 14px;
                        padding: 4px 6px;
                        box-shadow: 0 0 4px $bg;
                        display: none;
                    }
                }
                p {
                    margin: 0;
                    color: $green;
                    text-align: center;
                }
            }
            .margin-right {
                display: none;
                background-color: #2323236b;
                // margin-top: -50px;
                width: 26vw;
            }
        }
        .info-bottom-shadow {
            width: 100%;
            height: 1%;
            background-color: #1A1A1A;
            box-shadow: 0px -2px 28px 24px #252525;
        }
        .description {
            background-color: $bg;
            width: 90%;
            margin: 0 auto 16px auto;
            .text {
                overflow: hidden;
                h2 {
                    font-size: 0.9em;
                    color: #EBECEC;
                    font-weight: bold;
                    padding: 16px 6px 6px 10px;
                    margin: 0;
                }
                p {
                    color: #C5C6C6;
                    font-size: 0.7em;
                    margin: 0 10px 0 20px;
                }
            }
            .trailer {
                h2 {
                    font-size: 0.9em;
                    color: #EBECEC;
                    font-weight: bold;
                    padding: 16px 6px 6px 10px;
                    margin: 0;                    
                }
                iframe {
                    margin: 0 auto;
                    display: block;
                    width: 270px; 
                    height: 147.5px;
                    border: 1px solid $green;
                    transform: scale(0.9);
                    padding: 10px 16px;
                }
            }
            .show-hide {
                display: flex;
                box-shadow: 0px -14px 4px 0px #1a1a1a;
                position: relative;
                margin-top: 6px;
                background-color: #1a1a1a;
                justify-content: space-between;
                padding: 0 10px 10px 10px;
                .toggle {
                    display: flex;
                    align-items: center;
                    width: 90%;
                    margin: 0 auto;
                    * {
                        cursor: pointer;
                    }
                    span:first-of-type {
                        color: $lightgray;
                        font-size: 0.7em;
                    }
                    span:last-of-type {
                        background-image: url('/movies-list/img/arrow.png');
                        height: 10px;
                        width: 29px;
                        background-size: contain;
                        background-position: center;
                        background-repeat: no-repeat;
                        display: block;
                        transform: rotate(90deg);
                        filter: saturate(5) grayscale(1) contrast(2);
                    }
                }
                .social {
                    display: flex;
                    div {
                        display: flex;
                        align-items: center;
                        img {
                            width: 12px;
                            cursor: pointer;
                            transition: none;
                        }
                        span {
                            color: $lightgray;
                            font-size: 0.6em;
                            margin: 0 8px 0 2px;
                        }
                        &.heart span.title {
                            position: absolute;
                            bottom: 0;
                            background-color: black;
                            height: fit-content;
                        }
                        &.eye img  {
                            cursor: default;
                        }
                    }
                }
            }
        }
        .more {
            background-color: $bg;
            border-top: 1px solid $green;
            border-bottom: 1px solid $green;
            margin-bottom: 30px;
            .items {
                display: flex;
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: space-evenly;
                &>div {
                    border: 1px solid #727271;
                    margin: 16px auto;
                    &:hover {
                        border: 1px solid $green;
                    }
                }
            }
            .toggle {
                display: flex;
                align-items: center;
                margin: 0 0 16px;
                * {
                    cursor: pointer;
                }
                span:first-of-type {
                    background-image: url('/movies-list/img/arrow.png');
                    background-position: center;
                    background-repeat: no-repeat;
                    background-size: contain;
                    display: block;
                    width: 16px;
                    height: 16px;
                    transform: rotate(90deg);
                    margin: 4px 6px 4px 16px;
                }
                span:last-of-type {
                    color: lightgray;
                    font-size: 0.8em;
                }
            }
        }
    }
}
@media screen and (min-width: 375px) {
    .item {
        .main {
            .title {
                h1 {
                    font-size: 1.4em;
                }
                .stars {
                    div {
                        width: 24px;
                        height: 24px;
                    }
                    span p {
                        font-size: 0.9em;
                        width: 150px;
                    }
                }
            }
            .j-title h2 {
                margin-top: 3px;
                span:first-of-type {
                    font-size: 0.5em;
                }
                span:last-of-type {
                    font-size: 0.4em;
                }
            }
            .info-center > div:last-of-type {
                div {
                    h3 {
                        font-size: 1em;
                    }
                    p {
                        font-size: 0.88em;
                    }
                }
            }
            .info-bottom {
                padding-top: 3%;
                &>div {
                    h3 {
                        font-size: 1em;
                    }
                    p {
                        font-size: 0.88em;
                    }
                }
                .add-list {
                    p {
                        margin: 2px 0 0 0;
                    }
                    .add-item {
                        transform: scale(1.16);
                        p {
                            font-size: 0.78em;
                            width: 140px;
                        }
                    }
                }
            }
            .description {
                margin: 16px auto;
                .text,
                .trailer {
                    h2 {
                        font-size: 1.2em;
                    }
                    p {
                        font-size: 0.9em;
                    }
                }
                .trailer iframe {
                    width: 338px;
                    height: 200px;
                }
                .show-hide {
                    .toggle {
                        span {
                            &:first-of-type {
                                font-size: 0.9em;
                            }
                            &:last-of-type {
                                height: 12px;
                                width: 36px;
                            }
                        }
                    }
                    .social div {
                        img {
                            width: 14px;
                        }
                        span {
                            font-size: 0.9em;
                            margin-left: 3px;
                        }
                    }
                }
            }
            .more {
                .toggle {
                    span:first-of-type {
                        width: 22px;
                        height: 16px;
                    }
                    span:last-of-type {
                        font-size: 0.9em;
                    }
                }
            }
        }
    }
}
@media screen and (min-width: 568px) {
    .item .main {
        .title .stars span {
            top: 35px;
        }
        .info-bottom {
            padding-left: 6%;
        }
    }
}
@media screen and (min-width: 768px) {
    .item .main {
        .title {
            margin: 0;
            h1 {
                font-size: 1.7em;  
            }
            .stars {
                max-width: 200px;
                div {
                    width: 28px;
                    height: 28px;
                }
            } 
        }
        .j-title {
            h2 span {
                &:first-of-type {
                    font-size: 0.6em;
                    margin: 3px 0 0 3%;
                }
                &:last-of-type {
                    font-size: 0.55em;
                    margin-left: 10px;
                }
            }
        }
        .info-center {
            height: 50vw;
            &>div:nth-of-type(2) img {
                margin-top: -16px;
                height: calc(100% + 16px);
            }
            &>div:last-of-type {
                position: relative;
                background: linear-gradient(0deg, #1a1a1a 0%, #1a1a1a 93%, #1a1a1a 98%);
                box-shadow: -8px 2px 20px 0 #1a1a1a;
                margin-top: -16px;
                padding: 40px 4% 0 4%;
                height: calc(100% - 20px);
                max-width:40%;
                width: 33vw;
                max-height: 100%;
                div h3 {
                    font-size: 1.1em;
                }
                div p {
                    font-size: 0.96em;
                    margin: 3px 0 6px 14px;
                }
            }
        }
        .info-bottom {
            padding-left: 10%;
            div:first-of-type {
                width: 22%;
            }
            div h3 {
                font-size: 1.1em;
            }
            div p {
                font-size: 0.96em;
            }
            .add-list {
                width: 13%;
                .add-item {
                    transform: scale(1.26);
                }
            }
            .margin-right {
                display: block;
            }
        }
        .description {
            margin: 26px auto;
            .text h2,
            .trailer h2 {
                font-size: 1.3em;
                padding: 20px 6px 6px 20px;
            }
            .text p {
                font-size: 1em;
                margin: 0 40px;
            }
            .trailer iframe {
                width: 676px;
                height: 400px;
            }
            .show-hide {
                padding: 0 20px 16px 20px;
                .toggle span:first-of-type {
                    font-size: 1em;
                }
                .toggle span:last-of-type {
                    width: 38px;
                    height: 14px;
                }
                .social div img {
                    width: 16px;
                }
                .social div span {
                    font-size: 1em;
                    margin: 0 12px 0 4px;
                }
            }
        }
        .more {
            .items {
                flex-flow: row nowrap;
                margin: 0;
                &>div {
                    transform: scale(0.9);
                }
            }
            .toggle {
                span:first-of-type {
                    width: 26px;
                    height: 18px;   
                }
                span:last-of-type {
                    font-size: 1em;
                }
            }
        } 
    }
}
@media screen and (min-width: 1024px) {
    .item {
        display: flex;
    }
    .item .main {
        width: 100%;
        .title {
            box-shadow: 1px 20px 20px 20px #1A1A1A;
            .stars {
                max-width: 240px;
                div {
                    width: 32px;
                    height: 32px;
                }
            }
        }
        .info-center {
            height: 40vw;
            &>div:last-of-type {
                max-width: 26%;
                padding: 40px 2% 0 2%;
                &>div {
                    margin: 10px 20px;
                    h3 {
                        font-size: 1.22em;
                        line-height: 1.6em;
                    }
                    p {
                        font-size: 1.04em;
                        line-height: 1.7em;
                    }
                }
            }
        }
        .info-bottom-shadow {
            height: 0;
        }
        .description {
            margin: 30px auto;
            width: 85%;
            padding: 1% 2%;
            .text h2,
            .trailer h2 {
                font-size: 1.4em;
                line-height: 1.6em;
            }
            .text p {
                font-size: 1.06em;
                line-height: 1.7em;
            }
        }
        .more .items {
            flex-flow: row wrap;
            &>div {
                margin: 36px auto;
                transform: scale(1);
            }
        }
    }
}
@media screen and (min-width: 1366px) {
    .item {
        // display: flex;
        .main {
            width: 120%;
            .info-center {
                &>div:last-of-type {
                    // max-width: 24%;
                    &>div {
                        h3 {
                            font-size: 1.3em;
                        }
                        p {
                            font-size: 1.09em;
                        }
                    }
                }
            }
            // .info-bottom-shadow {
            //     height: 0;
            // }
        }
    }
    .item .main .title {
        // margin-bottom: -6px;
        h1 {
            font-size: 2.1em;
            // text-transform: uppercase;
            // text-shadow: 6px 6px 6px #000000;
            // font-size: 2.8em;
            // margin-bottom: -160px;
            // overflow: visible;
        }
    }
    .item .main .j-title {
        // margin-top: -16px;
        h2 {
            margin-top: 6px;
            // margin-top: 0;
            // span:first-of-type {
            //     font-size: 0.8em;
            // }
            // span:last-of-type {
            //     font-size: 0.78em;
            // }
        }
    }
    .item .main .info-bottom div {
        h3 {
            font-size: 1.2em;
        }
        p {
            font-size: 1.08em;
        }
    }
    .item .main .description {
        margin: 40px auto;
        .text {
            h2 {
                font-size: 1.56em;
            }
            p {
                font-size: 1.16em;
                line-height: 1.8em;
            }
        }
    }
    .item .main .description .show-hide {
        .toggle {
            span:first-of-type {
                font-size: 1.2em;
            }
            span:last-of-type {
                width: 40px;
                height: 16px;
            }
        }
        .social div {
            img {
                width: 20px;
            }
            span {
                font-size: 1.2em;
                margin: 0 16px 0 6px;
            }
        }
    }
    .item .main .more .items > div {
        margin: 70px auto;
        transform: scale(1.16);
    }
    .item .main .more .toggle {
        span:first-of-type {
            width: 26px;
            height: 18px;
        }
        span:last-of-type {
            font-size: 1.2em;
        }
    }
}
</style>