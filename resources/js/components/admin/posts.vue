<template>
    <div class="admin-controlls-posts">
        <desktop-menu :user="user" />
        <div class="site">
            <admin-panel />
            <table>
                <thead>
                    <tr>
                        <th>title</th>
                        <th>description</th>
                        <th>visits</th>
                        <th v-if="isMobile">created</th>
                        <th v-if="isMobile">updated</th>
                        <th>edit</th>
                        <th>delete</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="post in posts" :key="post.id">
                        <td>{{ post.title }}</td>
                        <td>{{ post.description }}</td>
                        <td>{{ post.visits }}</td>
                        <td v-if="isMobile">{{ post.created_at }}</td>
                        <td v-if="isMobile">{{ post.updated_at }}</td>
                        <td class="button edit" @click="editHandler(post)"><button>edit</button></td>
                        <td class="button delete" @click="deleteHandler(post.id)"><button>delete</button></td>
                    </tr>
                </tbody>
            </table>
            <div v-if="isModalEnabled" class="edit-post">
                <form>
                    <div class="close" @click="closeEditItem">x</div>
                    <h1 v-if="buttonName === 'edit'">Edit Post</h1>
                    <h1 v-else>Create Post</h1>
                    <label>Title:<br/>
                        <input v-model="post.title" autocomplete="off">
                    </label>
                    <label>Description:<br/>
                        <textarea v-model="post.description"></textarea>
                    </label>
                    <label>Image<br/>
                        <input type="file" ref="file" @change="handleFileUpload" />
                    </label>
                    <label>Video<br/>
                        <input v-model="post.video" autocomplete="off">
                    </label>
                    <button v-if="buttonName==='edit'" class="button-green" @click.prevent="editItem">{{ buttonName }}</button>
                    <button v-else class="button-green" @click.prevent="createItem">{{ buttonName }}</button>
                    <p>Multiple items separate by comma.</p>
                </form>
            </div>
            <button class="button-green" @click="createHandler">create post</button>
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
            posts: JSON.parse(this.data),
            isMobile: false,
            isModalEnabled: false,
            default: {
                title: '', description: '', image: '', video: '', visits: ''
            },
            post: {},
            buttonName: '',
            file: ''
        }
    },
    methods: {
        handleFileUpload() {
            this.file = this.$refs.file.files[0];
        },
        editHandler(post) {
            this.post = post;
            this.buttonName = 'edit';
            this.isModalEnabled = true;
        },
        createHandler() {
            this.post = {...this.default};
            this.buttonName = 'create';
            this.isModalEnabled = true;
        },
        deleteHandler(id) {
            let accept = confirm("Are you sure to delete this post?");
            if(accept) {
                axios.delete(`/movies-list/controlls/admin/posts/${id}`)
                .then(res => {
                    this.posts = res.data.posts;
                    alert(res.data.message);
                }).catch(err => {})
            }
        },
        closeEditItem() {
            this.isModalEnabled = false;
            this.file = '';
        },
        editItem() {
            let formData = new FormData();
            const data = {
                title: this.post.title,
                description: this.post.description,
                image: this.post.image,
                video: this.post.video
            };
            formData.append('file', this.file);
            formData.append('data', JSON.stringify(data));
            axios.post(`/movies-list/controlls/admin/posts/${this.post.id}`, formData, { headers: {
                'Accept': 'application/json',
                'Content-Type': 'multipart/form-data'
            }}).then(res => {
                this.posts = res.data.posts;
                this.isModalEnabled = false;
                alert(res.data.message);
            }).catch(err => {});
        },
        createItem() {
            let formData = new FormData();
            const data = {
                title: this.post.title,
                description: this.post.description,
                image: this.post.image,
                video: this.post.video
            };
            formData.append('file', this.file);
            formData.append('data', JSON.stringify(data));
            axios.post('/movies-list/controlls/admin/posts', formData, { headers: {
                'Accept': 'application/json',
                'Content-Type': 'multipart/form-data'
            }}).then(res => {
                this.posts = res.data.posts;
                this.isModalEnabled = false;
                alert(res.data.message);
            }).catch(err => {});
        },
    }
}
</script>

<style lang="scss" scoped>
$lightgray: #C5C6C6;
$green: #009846;
$bg: #1A1A1A;
.admin-controlls-posts {
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
    .edit-post {
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
            transform: translateY(0) translateX(-50%);
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
    .admin-controlls-posts {
        font-size: 0.9em;
        table tbody tr td {
            height: 26px;
            &.button button {
                height: 22px;
            }
        }
        .edit-post form {
            transform: translateY(-50%) translateX(-50%) scale(1.2);
        }
    }
}
@media screen and (min-width: 1024px) {
    .site {
        position: relative;
        width: 100%;
    }
    .admin-controlls-posts {
        display: flex;
        flex-direction: row;
        .edit-post form {
            top: 35%;
        }
    }
}
</style>