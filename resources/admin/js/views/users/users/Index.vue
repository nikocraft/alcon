<template>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Users</h3>
                <div class="header-tools">
                    <button @click="handleAdd()" class="btn btn-block btn-primary btn-header" type="button">Create</button>
                </div>
            </div>
            <div class="box-body">
                <table v-if="auth.hasPermission('manage-users')" class="table table-content">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Approved</th>
                            <th>Activated</th>
                            <th>Activated At</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users" :key="user.id">
                            <td style="width: 170px;">
                                {{ user.firstname }} {{ user.lastname }}
                            </td>
                            <td>
                                {{ user.username }}
                            </td>
                            <td>
                                {{ user.email }}
                            </td>
                            <td>
                                <div class="roles">
                                    <span class="role" v-for="role in user.roles" :key="role.id">{{ role.display_name }}</span>
                                </div>
                            </td>
                            <td>
                                {{ user.approved }}
                            </td>
                            <td>
                                {{ user.activated }}
                            </td>
                            <td>
                                {{ user.activated_at }}
                            </td>
                            <td>
                                {{ user.created_at }}
                            </td>
                            <td style="width: 100px;">
                                <button @click="handleEdit(user)" type="button" class="btn btn-primary edit-btn"><i class="lo-icon lo-icon-edit"></i></button>
                                <button @click="handleDelete(user)" type="button" class="btn btn-xs btn-danger delete-btn"><i class="lo-icon lo-icon-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th>Approved</th>
                            <th>Activated</th>
                            <th>Activated At</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
                <div style="display: flex; justify-content: flex-end; margin-top: 7px; margin-bottom: 7px;">
                    <vue-pagination v-show="pagination.last_page > 1"  v-bind:pagination="pagination"
                        v-on:page-updated="pageChange"
                        :offset="4">
                    </vue-pagination>
                </div>
            </div>
        </div>
        <form-modal/>
    </div>
</template>
<script>
    import FormModal from './FormModal'

    export default {
        components: {
            FormModal
        },
        data: () => ({
            users: [],
            pagination: {
                total: 0,
                current_page: 1
            }
        }),
        watch: {
            $route(route) {
                this.updateQueryFromRouter()
                this.getUsers()
            }
        },
        methods: {
            getUsers() {
                axios.get(route('api.users.index') + '?page='+this.pagination.current_page)
                .then(({data: responseData}) => {
                    this.users = responseData.data
                    this.pagination = responseData.meta
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            handleAdd() {
                this.$bus.$emit('open-modal:user-form', {
                    cb: () => {
                        this.getUsers()
                    }
                })
            },
            handleEdit(user) {
                this.$bus.$emit('open-modal:user-form', {
                    user: user,
                    cb: () => {
                        this.getUsers()
                    }
                })
            },
            handleDelete(user) {
                this.$confirm('Are you sure you want to delete this user? This action cannot be undone!', 'Delete User', {
                    confirmButtonText: 'Delete',
                    showCancelButton: false,
                    confirmButtonClass: 'el-button--danger',
                    type: 'warning'
                }).then(() => {
                    return axios.delete(route('api.users.destroy', user.id))
                        .then((response) => {
                            this.$bus.$emit('role-manager:refresh')
                        })
                        .catch((error) => {
                        })
                }).catch(() => {})
            },
            updateQueryFromRouter() {
                let query = this.$route.query
                this.pagination = {
                    ...this.pagination,
                    ...query
                }
            },
            pageChange(page) {
                this.$router.push({query: { page }})
            }
        },
        created() {
            this.updateQueryFromRouter()
            this.getUsers()

            this.$bus.$on('role-manager:refresh', (params) => {
                this.getUsers()
            })
            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('role-manager:refresh')
            })
        }
    }
</script>

<style lang="scss">
    .role {
        padding: 4px 8px;
        background: rgba(0,0,0, 0.11);
        margin: 1px;
    }

    .b-roles-selector,
    .permissions {
        .el-checkbox, .el-checkbox+.el-checkbox {
            margin-left: 0;
            margin-right: 1px;
            padding: 4px 8px;
            background: rgba(0,0,0, 0.2);
        }
        .el-checkbox__label {
            color: #b8c7ce;
            font-size: 13px;
            font-weight: normal;
        }
    }
</style>
