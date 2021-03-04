<template>
    <div class="admin-controlls-incomings">
        <desktop-menu :user="user" />
        <div class="site">
            <admin-panel />
            <table>
                <thead>
                    <tr>
                        <th>title</th>
                        <th>release date</th>
                        <th>created</th>
                        <th>updated</th>
                        <th>delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="incoming in incomings" :key="incoming.id">
                        <td>{{ incoming.title }}</td>
                        <td>{{ incoming.release_date }}</td>
                        <td>{{ incoming.created_at }}</td>
                        <td>{{ incoming.updated_at }}</td>
                        <td class="button delete" @click="deleteHandler(incoming.id)"><button>delete</button></td>
                    </tr>
                </tbody>
            </table>
            <div v-if="isCreateEnabled" class="edit-incoming">
                <form>
                    <div class="close" @click="closeHandler">x</div>
                    <h1>Create Incoming</h1>
                    <label>
                        <input placeholder="title" v-model="title" autocomplete="off">
                    </label>
                    <label>
                        <input type="date" placeholder="release date" v-model="release_date" autocomplete="off">
                    </label>
                    <label>
                        <input type="file" ref="file" @change="handleFileUpload" />
                    </label>
                    <button class="button-green" @click.prevent="createHandler">Create</button>
                </form>
            </div>
            <button class="button-green" @click="openHandler">Create Incoming</button>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'data',
        'auth'
    ],
    data() {
        return {
            user: this.auth ? JSON.parse(this.auth) : '',
            incomings: JSON.parse(this.data),
            isMobile: false,
            isCreateEnabled: false,
            title: '',
            release_date: '',
            file: ''
        }
    },
    methods: {
        handleFileUpload() {
            this.file = this.$refs.file.files[0];
        },
        openHandler() {
            this.isCreateEnabled = true;
        },
        closeHandler() {
            this.isCreateEnabled = false;
            this.title =  '';
            this.release_date = '';
            this.file = '';
        },
        deleteHandler(id) {
            let accept = confirm("Are you sure to delete this incoming?");
            if(accept) {
                axios.delete(`/movies-list/controlls/admin/incomings/${id}`)
                .then(res => {
                    this.incomings = res.data.incomings;
                    alert(res.data.message);
                }).catch(err => {})
            }
        },
        createHandler() {
            let formData = new FormData();
            const data = {
                title: this.title,
                release_date: this.release_date,
                image: this.image
            }
            formData.append('file', this.file);
            formData.append('data', JSON.stringify(data));
            axios.post('/movies-list/controlls/admin/incomings', formData, { handlers: {
                'Accept': 'application/json',
                'Content-Type': 'multipart/form-data'
            }}).then(res => {
                this.incomings = res.data.incomings;
                alert(res.data.message);
                this.closeHandler();
            }).catch(err => {});
        }
    }
}
</script>

<style lang="scss" scoped>
$lightgray: #C5C6C6;
$green: #009846;
$bg: #1A1A1A;
.admin-controlls-incomings {
    color: lightgray;
    font-size: 0.7em;
    display: flex;
    flex-direction: column;
    .site {
        display: flex;
        flex-direction: column;
    }
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
    .edit-incoming {
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
    .button-green {
        background-color: $green;
        margin: 20px auto 16px auto;
        border: 0;
        text-transform: uppercase;
        color: lightgray;
        padding: 4px 0;
        min-width: 200px;
        outline: none;
    }
}
@media screen and (min-width: 375px) {
    .admin-controlls-incomings {
        font-size: 0.9em;
        table tbody tr td {
            height: 26px;
            &.button button {
                height: 22px;
            }
        }
        .edit-incoming form {
            transform: translateY(-50%) translateX(-50%) scale(1.2);
        }
    }
}
@media screen and (min-width: 1024px) {
    .site {
        width: 100%;
        position: relative;
    }
    .admin-controlls-incomings {
        display: flex;
        flex-direction: row;
        .edit-incoming {
            position: absolute;
            form {
                top: 30%;
            }
        }
    }
}
</style>