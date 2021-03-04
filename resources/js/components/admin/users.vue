<template>
    <div class="admin-controlls-users">
        <desktop-menu :user="user" />
        <div class="site">
            <admin-panel />
            <table>
                <thead>
                    <tr>
                        <th>name</th>
                        <th>email</th>
                        <th>type</th>
                        <th v-if="isMobile">created</th>
                        <th v-if="isMobile">updated</th>
                        <th>edit</th>
                        <th>delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="user in users" :key="user.id">
                        <td>{{ user.name }}</td>
                        <td>{{ user.email }}</td>
                        <td>{{ user.account_type }}</td>
                        <td v-if="isMobile">{{ user.created_at }}</td>
                        <td v-if="isMobile">{{ user.updated_at }}</td>
                        <td class="button edit" @click="editHandler(user)"><button>edit</button></td>
                        <td class="button delete" @click="deleteHandler(user.id)"><button>delete</button></td>
                    </tr>
                </tbody>
            </table>
            <div v-if="isEditEnabled" class="edit-user">
                <form>
                    <div class="close" @click="closeEditUser">x</div>
                    <h1>Edit User</h1>
                    <label>
                        <input name="name" placeholder="name" v-model="name" autocomplete="off">
                    </label>
                    <label v-if="isChangePassword">
                        <input type="password" name="password" placeholder="password" v-model="password">
                    </label>
                    <button @click.prevent="toggleChangePassword">edit password</button>
                    <button @click.prevent="editUser">Edit</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'usersData',
        'auth'
    ],
    data() {
        return {
            user: this.auth ? JSON.parse(this.auth) : '',
            users: JSON.parse(this.usersData),
            isMobile: false,
            isChangePassword: false,
            isEditEnabled: false,
            name: '',
            password: '',
            currentUser: ''
        }
    },
    methods: {
        editHandler(user) {
            this.currentUser = user;
            this.name = user.name;
            this.isEditEnabled = !this.isEditEnabled;
        },
        deleteHandler(id) {
            let accept = confirm("Are you sure to delete this user?");
            if(accept) {
                axios.delete(`/movies-list/controlls/admin/${id}`)
                .then(res => {
                    this.users = res.data.users;
                    alert(res.data.message);
                }).catch(err => {})
            }
        },
        toggleChangePassword() {
            this.isChangePassword = !this.isChangePassword;
            this.password = ''
        },
        editUser() {
            let accept = confirm("Are you sure to change this user?");
            let data = this.password.match("^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})") ? {name: this.name} : {
                    name: this.name,
                    password: this.password
                }
            if(accept) {
                axios.put(`/movies-list/controlls/admin/${this.currentUser.id}`, data).then(res => {
                    this.users = res.data.users;
                    alert(res.data.message);
                }).catch(err => { alert('Invalid data') })
            }
        },
        closeEditUser() {
            this.isEditEnabled = false;
        }
    }
}
</script>

<style lang="scss" scoped>
$lightgray: #C5C6C6;
$green: #009846;
$bg: #1A1A1A;
.admin-controlls-users {
    color: lightgray;
    font-size: 0.7em;
    table {
        width: 100%;
        thead th {
            text-align: center;
            background-color:#212529;
            border: 1px solid gray;
        }
        tbody {
            tr {
                &:nth-of-type(odd) {
                    background-color: rgba(128, 128, 128, 0.16);
                }
                td {
                   border: 1px solid rgba(128, 128, 128, 0.6);
                   height: 22px;
                   &.more {
                       display: none;
                   }
                   &.button {
                       text-align: center;
                       button {
                           outline: none;
                           border-width: 1px;
                           height: 16px;
                           font-size: 0.9em;
                       }
                   }
                   &.edit {
                       button {
                           background-color: rgb(245, 173, 60);
                           border-color: orange;
                       }
                   }
                   &.delete {
                       button {
                           background-color: rgb(255, 60, 60);
                           border-color: red;
                       }
                   }
               }
            }
        }
        th, td {
            width: 20%;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
    .edit-user {
        position: fixed;
        width: 100%;
        min-height: 100vh;
        top: 0;
        background-color: #00000088;
        z-index: 1;
        form {
            background-color: black;
            border: 1px solid $green;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translateY(-50%) translateX(-50%);
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 300px;
            max-width: 100%;
            h1 {
                margin-top: 16px;
                color: lightgray;
                font-size: 1.3em;
            }
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
                }
            }
            button {
                background-color: $green;
                margin: 20px 0 16px 0;
                border: 0;
                text-transform: uppercase;
                color: lightgray;
                padding: 4px 0;
                min-width: 200px;
                outline: none;
                &:first-of-type {
                    margin: 0 0 6px 0;
                    background-color: gray;
                }
            }
        }
        .close {
            position: absolute;
            right: 16px;
            top: 10px;
            color: lightgray;
            transform: scaleX(1.2);
            cursor: pointer;
        }
    }
}
@media screen and (min-width: 375px) {
    .admin-controlls-users {
        font-size: 0.9em;
        table tbody tr td {
            height: 26px;
            &.button button {
                height: 22px;
            }
        }
        .edit-user form {
            transform: translateY(-50%) translateX(-50%) scale(1.2);
        }
    }
}
@media screen and (min-width: 1024px) {
    .site {
        width: 100%;
        position: relative;
    }
    .admin-controlls-users {
        display: flex;
    }
}
</style>