<template>
    <div class="admin-controlls-items">
        <desktop-menu :user="user" />
        <div class="site">
            <admin-panel />
            <table>
                <thead>
                    <tr>
                        <th>title</th>
                        <th>genre</th>
                        <th>release date</th>
                        <th v-if="isMobile">time</th>
                        <th v-if="isMobile">created</th>
                        <th v-if="isMobile">updated</th>
                        <th>edit</th>
                        <th>delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items" :key="item.id">
                        <td>{{ item.title }}</td>
                        <td>{{ item.genre }}</td>
                        <td>{{ item.release_date }}</td>
                        <td v-if="isMobile">{{ item.time }}</td>
                        <td v-if="isMobile">{{ item.created_at }}</td>
                        <td v-if="isMobile">{{ item.updated_at }}</td>
                        <td class="button edit" @click="editHandler(item)"><button>edit</button></td>
                        <td class="button delete" @click="deleteHandler(item.id)"><button>delete</button></td>
                    </tr>
                </tbody>
            </table>
            <div v-if="isModalEnabled" class="edit-item">
                <form>
                    <div class="close" @click="closeEditItem">x</div>
                    <h1>{{ buttonName }} {{ type.slice(0, -1) }}</h1>
                    <label>Title:<br/>
                        <input v-model="item.title" autocomplete="off">
                    </label>
                    <label>Japan Title:<br/>
                        <input v-model="item.j_title" autocomplete="off">
                    </label>
                    <label>Romaji Title:<br/>
                        <input v-model="item.romaji" autocomplete="off">
                    </label>
                    <label>Genre:<br/>
                        <input v-model="item.genre" autocomplete="off">
                    </label>
                    <label>Description:<br/>
                        <textarea v-model="item.description"></textarea>
                    </label>
                    <label>Date:<br/>
                        <input type="date" v-model="item.release_date" autocomplete="off">
                    </label>
                    <label>Year:<br/>
                        <input v-model="item.year" autocomplete="off">
                    </label>
                    <label>Time:<br/>
                        <input v-model="item.time" autocomplete="off">
                    </label>
                    <label>Cast:<br/>
                        <input v-model="item.cast" autocomplete="off">
                    </label>
                    <label>Diractor<br/>
                        <input v-model="item.director" autocomplete="off">
                    </label>
                    <label>Producer<br/>
                        <input v-model="item.producer" autocomplete="off">
                    </label>
                    <label>Images<br/>
                        <input type="file" ref="file" @change="handleFileUpload">
                    </label>
                    <label>Trailer<br/>
                        <input v-model="item.trailer_link" autocomplete="off">
                    </label>
                    <button v-if="buttonName==='Edit'" class="button-green" @click.prevent="editItem">{{ buttonName }}</button>
                    <button v-else class="button-green" @click.prevent="createItem">{{ buttonName }}</button>
                    <p>Multiple items separate by comma.</p>
                </form>
            </div>
            <button class="button-green" @click="createHandler">create {{ type.slice(0, -1) }}</button>
            <button class="button-green" @click="ratingReset">Reset Rated</button>
            <p>Turn rated to be active if someone already rated {{ type.slice(0, -1) }}.</p>
        </div>
    </div>
</template>

<script>
export default {
    props: [
        'data',
        'type',
        'auth'
    ],
    data() {
        return {
            user: this.auth ? JSON.parse(this.auth) : '',
            items: JSON.parse(this.data),
            isMobile: false,
            isModalEnabled: false,
            default: {
                title: '', j_title: '', romaji: '', genre: '', description: '', release_date: '', year: '', time: '', cast: '', director: '', producer: '', images: '', trailer_link: '', social: {
                }
            },
            item: {},
            buttonName: '',
            file: ''
        }
    },
    methods: {
        handleFileUpload() {
            this.file = this.$refs.file.files[0];
        },
        editHandler(item) {
            this.item = item;
            this.buttonName = 'Edit';
            this.isModalEnabled = true;
        },
        createHandler() {
            this.item = this.default;
            this.buttonName = 'Create';
            this.isModalEnabled = true;
        },
        deleteHandler(id) {
            let accept = confirm("Are you sure to delete?");
            if(accept) {
                axios.delete(`/movies-list/controlls/admin/${this.type}/${id}`)
                .then(res => {
                    this.items = res.data[this.type];
                    alert(res.data.message);
                })
                .catch(err => {})
            }
        },
        closeEditItem() {
            this.isModalEnabled = false;
            this.file = '';
        },
        editItem() {
            let formData = new FormData();
            const data = {
                title: this.item.title,
                j_title: this.item.j_title,
                romaji: this.item.romaji,
                genre: this.item.genre,
                description: this.item.description,
                release_date: this.item.release_date,
                year: this.item.year,
                time: this.item.time,
                cast: this.item.cast,
                director: this.item.director,
                producer: this.item.producer,
                trailer_link: this.item.trailer_link
            };
            formData.append('file', this.file);
            formData.append('data', JSON.stringify(data));
            axios.post(`/movies-list/controlls/admin/${this.type}/${this.item.id}`, formData, { headers: {
                'Accept': 'application/json',
                'Content-Type': 'multipart/form-data'
            }}).then(res => {
                this.items = res.data[this.type];
                this.isModalEnabled = false;
                alert(res.data.message);
            }).catch(err => {});
        },
        createItem() {
            let formData = new FormData();
            const data = {
                title: this.item.title,
                j_title: this.item.j_title,
                romaji: this.item.romaji,
                genre: this.item.genre,
                description: this.item.description,
                release_date: this.item.release_date,
                year: this.item.year,
                time: this.item.time,
                cast: this.item.cast,
                director: this.item.director,
                producer: this.item.producer,
                trailer_link: this.item.trailer_link
            }
            formData.append('file', this.file);
            formData.append('data', JSON.stringify(data));
            axios.post(`/movies-list/controlls/admin/${this.type}`, formData, { headers: {
                'Accept': 'application/json',
                'Content-Type': 'multipart/form-data'
            }}).then(res => {
                this.items = res.data[this.type];
                this.isModalEnabled = false;
                alert(res.data.message);
            }).catch(err => {});
        },
        ratingReset() {
            axios.patch(`/movies-list/controlls/admin/${this.type}/reset`)
            .then(res => alert(res.data.message))
            .catch(err => {});
        }
    }
}
</script>

<style lang="scss" scoped>
$lightgray: #C5C6C6;
$green: #009846;
$bg: #1A1A1A;
.admin-controlls-items {
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
    .edit-item {
        position: absolute;
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
            max-height: 80vh;
            overflow: auto;
            scrollbar-width: none;
            &::-webkit-scrollbar {
                display: none;
            }
            h1 {
                margin-top: 16px;
                color: lightgray;
                font-size: 1.3em;
            }
            label {
                margin: 16px 0;
                input, textarea {
                    background-color: $bg;
                    border: 1px solid $green;
                    outline: none;
                    transition: all 0.2s;
                    color: lightgray;
                    font-size: 0.8em;
                    padding: 6px 8px;
                    min-width: 200px;
                }
                textarea {
                    min-height: 100px;
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
    p {
        color: lightgray;
        text-align: center;
    }
    .button-green {
        background-color: $green;
        margin: 20px 0 16px 0;
        border: 0;
        text-transform: uppercase;
        color: lightgray;
        padding: 4px 0;
        min-width: 200px;
        outline: none;
        align-self: center;
    }
}
@media screen and (min-width: 375px) {
    .admin-controlls-items {
        font-size: 0.9em;
        table tbody tr td {
            height: 26px;
            &.button button {
                height: 22px;
            }
        }
        .edit-item form {
            transform: translateY(-50%) translateX(-50%) scale(1.2);
            max-height: 76vh;
        }
    }
}
@media screen and (min-width: 768px) {
    .admin-controlls-items .edit-item form {
        width: 400px;
        label input,
        label textarea {
            min-width: 300px;
        }
    }
}
@media screen and (min-width: 1024px) {
    .site {
        width: 100%;
        position: relative;
    }
    .admin-controlls-items {
        display: flex;
        flex-direction: row;
        .edit-item form {
            top: 40%;
        }
    }
}
</style>