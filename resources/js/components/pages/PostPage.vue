<template>
    <main class="post">
        <desktop-menu :user="auth ? user : null" />
        <div class="main">
            <div class="title">
                <h1>{{ post.title }}</h1>
            </div>
            <div class="image">
                <img v-if="post.image" :src="`/movies-list/storage/posts/${post.image}`" :alt="post.title">
            </div>
            <div class="bottom-shadow"></div>
            <div class="description">
                <div ref="text" class="text">
                    <h2>Description</h2>
                    <p ref="description">{{ post.description }}</p>
                </div>
                <div ref="trailer" class="trailer">
                    <template v-if="post.video">
                        <h2>Video</h2>
                        <iframe :src="`https://${post.video}?showinfo=0`" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </template>
                </div>
                <div class="show-hide">
                    <div class="toggle" @click="dropDown">
                        <span>{{ isShowMore ? 'show less' : 'show more' }}</span>
                        <span class="arrow"></span>
                    </div>
                    <div class="social">
                        <div class="heart" @click="likePost">
                            <img src="/movies-list/img/heart.png" alt="heart" 
                                    @mouseenter="hoverSocial"
                                    @mouseleave="leaveSocial"
                                >
                            <span>{{ post.like }}</span>
                            <span v-if="isLikeInfoVisible" class="title-heart">Login first</span>
                        </div>
                        <div class="eye">
                            <img src="/movies-list/img/eye.png" alt="eye">
                            <span>{{ post.visits }}</span>
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
                <div class="posts blocks-list">
                    <item-result v-for="post in post.related_posts" :key="post.id" :item="post" :isHomePage="true" />
                </div>
                <div class="toggle" @click="dropDownScimilars">
                    <span class="arrow"></span>
                    <span>Last posts</span>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
export default {
    props: [
        'id',
        'auth',
    ],
    data() {
        return {
            isStarsHovered: false,
            isShowMore: true,
            isShowScimilars: true,
            isLikeInfoVisible: false,
            post: '',
            rate: '',
            trailerHeight: '',
            moreHeight: '',
            user: {post: {rated: true, liked: true}},
            message: ''
        }
    },
    mounted() {
        axios.get(`/movies-list/api/posts/${this.id}`)
            .then(res => this.post = res.data)
            .finally(() => {
                axios.put(`/movies-list/posts/${this.id}`, {
                    visits: this.post.visits + 1
                }).then(res => this.post.visits = res.data.visits);
            }).catch(err => {});
        if(!this.post.video) {
            this.$refs.description.style.paddingBottom = '20px';
        }
        if(this.auth) {
            const user = JSON.parse(this.auth);
            const postLength = Object.keys(user.post ? user.post : this.user.post).length
            if(postLength > 2) {
                this.user = JSON.parse(this.auth);
            } else {
                this.addUserPost();
            }
        }
    },
    methods: {
        description(post) {
            return post.description.slice(0, 150);
        },
        createdAt(post) {
            const date = new Date(post.created_at).toLocaleDateString();
            const arrDate = date.split('.');
            const months = ['Jan,', 'Feb.', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Dec.'];
            return `${months[arrDate[1] - 1]} ${arrDate[0]}. ${arrDate[2]}`
        },
        addUserPost() {
            axios.post(`/movies-list/posts/add-to-user/${this.id}`)
                .then(res => {
                    this.user = res.data
                }).catch(err => {})
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
        likePost() {
            if(this.auth) {
                if(!this.user.post.liked) {
                    axios.put(`/movies-list/posts/${this.post.id}`, {
                        like: this.post.like + 1
                    }).then(res => {
                        this.post.like = res.data.like;
                        this.user.post.liked = true;
                    });
                }
            } else {
                this.isLikeInfoVisible = true
                setTimeout(() => this.isLikeInfoVisible = false, 2000)
            }
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
                document.querySelector('.post .show-hide .arrow').style.transform = this.isShowMore ? 'rotate(90deg)' : 'rotate(-90deg)';
            }, 600)
        },
        dropDownScimilars() {
            if(this.isShowScimilars) {
                this.moreHeight = document.querySelector('.post .more .posts').getBoundingClientRect().height;
            }
            this.isShowScimilars = !this.isShowScimilars;
            this.$anime({
                targets: '.post .more .posts',
                opacity: this.isShowScimilars ? 1 : 0,
                easing: 'easeOutExpo',
                duration: 600,
                delay: this.isShowScimilars ? 300 : 0
            })
            this.$anime({
                targets: '.post .more .posts',
                height: this.isShowScimilars ? `${this.moreHeight}px` : '0px',
                scaleY: this.isShowScimilars ? 1 : 0,
                margin: this.isShowScimilars ? '16px' : '0px',
                easing: 'easeOutExpo',
                duration: 600,
                delay: this.isShowScimilars ? 0 : 300
            })
            this.$anime({
                targets: '.post .more .toggle',
                margin: this.isShowScimilars ? '0 0 16px 0' : '16px 0',
                easing: 'easeOutExpo',
                duration: 600
            })
            setTimeout(() => {
                document.querySelector('.post .more .toggle .arrow').style.transform = this.isShowScimilars ? 'rotate(90deg)' : 'rotate(-90deg)';
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
.post {
    * {
        transition: all 0.4s;
    }
    .main {
        .title {
            display: flex;
            height: 20px;
            align-items: center;
            justify-content: space-around;
            box-shadow: 0 10px 20px 13px #1A1A1A;
            background-color: #1A1A1A;
            h1 {
                color: $lightgray;
                font-size: 1.9em;
                margin: 0 0 -7vw 0;  
                text-transform: uppercase;
                text-shadow: 6px 6px 6px #000000;
                max-width: 90%;
                text-align: center;
                color: lightgray;
                font-weight: bold;
            }
        }
        .image {
            width: 100%;
            img {
                width: 100%;
                opacity: 0.6;
                z-index: -1;
                position: relative;
            }
        }
        .bottom-shadow {
            width: 100%;
            background-color: #1A1A1A;
            box-shadow: 0px -2px 15px 20px #252525;
        }
        .description {
            background-color: $bg;
            width: 90%;
            margin: -20vw auto 16px auto;
            box-shadow: 0 0 16px 4px #1a1a1a;
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
                    span {
                        cursor: pointer;
                        &:first-of-type {
                            color: $lightgray;
                            font-size: 0.7em;
                        }
                        &:last-of-type {
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
                }
                .social {
                    display: flex;
                    .heart span.title-heart {
                        position: absolute;
                        bottom: 0;
                        background-color: black;
                        height: fit-content;
                    }
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
                    }
                    .eye img {
                        cursor: default;
                    }
                }
            }
        }
        .more {
            background-color: $bg;
            border-top: 1px solid $green;
            border-bottom: 1px solid $green;
            margin-bottom: 30px;
            .posts {
                display: flex;
                // flex-direction: column;
                flex-wrap: wrap;
                justify-content: space-evenly;
                margin: 16px;
                transition: all 0.4s;
                &>div {
                    border: 1px solid #727271;
                    margin: 16px 0;
                    &:hover {
                        border: 1px solid $green;
                    }
                    img {
                        width: 100%;
                        cursor: pointer;
                    }
                    &>div {
                        display: flex;
                        justify-content: space-between;
                        margin-top: 6px;
                        // .info {
                        //     // width: 100%;
                        //     // display: flex;
                        //     // flex-direction: row;
                        //     // flex-wrap: wrap;
                        //     // justify-content: space-between;
                        //     // align-items: center;
                        //     // a {
                        //     //     width: 68%;
                        //     //     color:#EBECEC;
                        //     //     font-weight: bold;
                        //     //     font-size: 1em;
                        //     //     margin-bottom: 2px;
                        //     //     white-space: nowrap;
                        //     //     text-overflow: ellipsis;
                        //     //     overflow: hidden;
                        //     //     text-decoration: none;
                        //     // }
                        //     // p {
                        //     //     color: $gray;
                        //     //     font-size: 0.6em;
                        //     //     margin: 0;
                        //     //     &:first-of-type {
                        //     //         width: 32%;
                        //     //         text-align: right;
                        //     //     }
                        //     //     &:last-of-type {
                        //     //         color: $lightgray;
                        //     //         width: 100%;
                        //     //     }
                        //     // }
                        // }
                    }
                }
            }
            .toggle {
                display: flex;
                align-items: center;
                margin: 0 0 16px;
                span {
                    cursor: pointer;
                    &:first-of-type {
                        background-image: url('/img/arrow.png');
                        background-position: center;
                        background-repeat: no-repeat;
                        background-size: contain;
                        display: block;
                        width: 16px;
                        height: 16px;
                        transform: rotate(90deg);
                        margin: 4px 6px 4px 16px;
                    }
                    &:last-of-type {
                        color: lightgray;
                        font-size: 0.8em;
                    }
                }
            }
        }
    }
}
@media screen and (min-width: 375px) {
    .post {
        .main {
            .title {
                h1 {
                    font-size: 2.4em;
                }
            }
            .description {
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
@media screen and (min-width: 558px) {
    .post .main .title {
        margin-top: -3vw;
    }
}
@media screen and (min-width: 768px) {
    .post .main {
        .title h1 {
            font-size: 3em;
        }
        .description {
            .text h2,
            .trailer h2 {
                font-size: 1.3em;
                padding: 20px 6px 6px 20px;
            }
            .text p {
                font-size: 1em;
                line-height: 1.7em;
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
            .posts {
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
    .post {
        display: flex;
        .main {
            width: 100%;
            .title {
                margin-top: 0;
                h1 {
                    margin: -16px 0 0 0;
                    font-size: 2.8em;
                }
            }
        }
    }
    .post .main .more .posts {
        flex-flow: row wrap;
        &>div {
            transform: scale(0.94);
            width:33%;
        }
    }
}
@media screen and (min-width: 1366px) {
    .post {
        // display: flex;
        .main {
            // width: 100%;
            .title {
                // margin-top: 0;
                height: 30px;
                h1 {
                    // margin: -16px 0 0 0;
                    font-size: 3em;
                }
            }
            .description {
                background-color: #1a1a1ae8;
                .text {
                    h2 {
                       font-size: 1.56em;
                    }
                    p {
                        font-size: 1.16em;
                        line-height: 1.8em;
                    }
                }
                .show-hide {
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
            }
            .more {
                .posts>div {
                    margin: 60px auto;
                    transform: scale(1.08);
                }
                .toggle {
                    span:first-of-type {
                        width: 26px;
                        height: 18px;
                    }
                    span:last-of-type {
                        font-size: 1.2em;
                    }
                }
            }
        }
    }
}
</style>