<template>
<div class="sign-in-form">
    <div class="bg">
        <div class="close" @click="$store.commit('SET_SIGN_IN_PAGE_VISIBLE')"></div>
        <div class="header">
            <h1 :class="isLoginActive ? 'active' : ''" @click="toggleHeader('login')">
                <span>Login</span>
            </h1>
            <h1>&nbsp;/&nbsp;</h1>
            <h1 :class="isLoginActive ? '' : 'active'" @click="toggleHeader('register')">
                <span>Register</span>
            </h1>
        </div>
        <form v-if="isLoginActive" method="POST" action="/login" @submit.prevent="loginHandler">
            <label>
                <input ref="input" v-model="email" type="email" placeholder="email" autocomplete="off" />
            </label>
            <div class="error">
                {{ errors.email ? errors.email[0] : '' }}
            </div>
            <label>
                <input v-model="password" type="password" placeholder="password" autocomplete="off" />
            </label>
            <div class="error">
                {{ errors.password ? errors.password[0] : '' }}
            </div>
            <button type="submit">Login</button>
            <div class="error">{{ errMsg }}</div>
        </form>
        <form v-else method="POST" action="/register" @submit.prevent="registerHandler">
            <label>
                <input ref="input" v-model="name" type="text" placeholder="name" autocomplete="off" />
            </label>
            <div class="error">
                {{ errors.name ? errors.name[0] : '' }}
            </div>
            <label>
                <input v-model="email" type="email" placeholder="email" autocomplete="off" />
            </label>
            <div class="error">
                {{ errors.email ? errors.email[0] : '' }}
            </div>
            <label>
                <input v-model="password" type="password" placeholder="password" autocomplete="off" />
            </label>
            <div class="error">
                {{ errors.password ? errors.password[0] : '' }}
            </div>
            <label>
                <input v-model="password_confirmation" type="password" placeholder="password confirm" autocomplete="off" />
            </label>
            <div class="error">
                {{ errors.password ? errors.password[1] : '' }}
            </div>
            <button type="submit">Register</button>
            <div class="error">{{ errMsg }}</div>
        </form>
    </div>
</div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            isLoginActive: true,
            errors: {},
            errMsg: ''
        }
    },
    mounted() {
        this.$refs.input.focus();
    },
    methods: {
        toggleHeader(value) {
            this.isLoginActive = value==='login' ? true : false;
            this.errors = {};
            this.errMsg = '';
            this.$refs.input.focus();
        },
        loginHandler() {
            axios.post('/movies-list/login', {
                email: this.email,
                password: this.password
            }).then(res => {
                window.location.reload()
            }).catch(e => {
                this.errors = e.response.data.errors;
                this.errMsg = e.response.data.message;
            });
        },
        registerHandler() {
            axios.post('/movies-list/register', {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation
            }).then(res => {
                window.location.reload();
            }).catch(e => {
                this.errors = e.response.data.errors;
                this.errMsg = e.response.data.message;
            })
        }
    }
}
</script>

<style lang="scss" scoped>
$green: #009846;
$bg: #1A1A1A;
.sign-in-form {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    min-height: 100%;
    background-color: rgba(0,0,0,0.6);
    z-index: 999;
    .bg {
        width: 80%;
        min-width: 300px;
        max-width: 400px;
        position: absolute;
        top: 100px;
        transform: translateX(-50%);
        left: 50%;
        background-color: black;
        border: 1px solid $green;
        transition: all 0.4s;
        .header {
            display: flex;
            justify-content: center;
            margin: 36px 0 16px 0;
            transition: all 0.4s;
            h1 {
                color: gray;
                font-size: 1.3em;
                &.active {
                    color: lightgray;
                    span {
                        cursor: text;
                        filter: brightness(1);
                    }
                }
                span {
                    cursor: pointer;
                    filter: brightness(0.8);
                    transition: all 0.2s;
                    &:hover {
                        filter: brightness(1);
                    }
                }
            }
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            transition: all 0.4s;
            label {
                margin: 16px 0;
                input {
                    background-color: $bg;
                    border: 1px solid $green;
                    outline: none;
                    transition: all 0.2s;
                    color: lightgray;
                    font-size: 0.8em;
                    padding: 6px 8px;
                    min-width: 200px;
                    &:focus {
                        border: 1px solid #02c55d;
                    }
                }
            }
            button {
                background-color: $green;
                margin: 20px 0 6px 0;
                border: 0;
                text-transform: uppercase;
                color: lightgray;
                padding: 4px 0;
                min-width: 200px;
                outline: none;
            }
            .error {
                color: red;
                font-size: 0.67em;
            }
        }
        .close {
            position: absolute;
            right: 16px;
            top: 10px;
            color: lightgray;
            cursor: pointer;
            background-image: url('/movies-list/img/close.svg');
            background-size: contain;
            background-repeat: no-repeat;
            width: 18px;
            height: 18px;
            transition: all 0.4s;
        }
    }
}
@media screen and (min-width: 768px) {
    .sign-in-form .bg {
        transform: translateX(-50%) scale(1.2);
        max-width: 450px;
        .close {
            top: 20px;
            right: 26px;
        }
        .header {
            margin-top: 60px;
        }
        form {
            margin-bottom: 60px;
        }
    }
}
</style>