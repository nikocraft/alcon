<template>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Roles</h3>
                <div class="header-tools">
                    <button @click="handleAdd()" class="btn btn-block btn-primary btn-header" type="button">Create</button>
                </div>
            </div>
            <div class="box-body">
                <table v-if="auth.hasPermission('manage-roles')" class="table table-content">
                    <thead>
                        <tr>
                            <th>Display Name</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="role in roles" :key="role.id">
                            <td style="width: 170px;">
                                {{role.display_name}}
                            </td>

                            <td>
                                <div class="permissions">
                                    <div class="permission" v-for="permission in role.permissions" :key="permission.id">{{ permission.display_name }}</div>
                                </div>
                            </td>

                            <td style="width: 100px;">
                                <button @click="handleEdit(role)" type="button" class="btn btn-primary edit-btn"><i class="lo-icon lo-icon-edit"></i></button>
                                <button @click="handleDelete(role)" type="button" class="btn btn-xs btn-danger delete-btn"><i class="lo-icon lo-icon-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Display Name</th>
                            <th>Permissions</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
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
            roles: [],
            permissions: [],
        }),
        methods: {
            fetchRoles() {
                axios.get(route('api.roles.index'))
                .then(({data: responseData}) => {
                    this.permissions = responseData.permissions
                    this.roles = responseData.data
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            handleAdd() {
                this.$bus.$emit('open-modal:role-form', {
                    permissions: this.permissions
                })
            },
            handleEdit(role) {
                this.$bus.$emit('open-modal:role-form', {
                    role: {...role},
                    permissions: this.permissions
                })
            },
            handleDelete(role) {
                let isImportantRole = !!([1, 2].indexOf(role.id)+1)

                if (isImportantRole) {
                    this.$alert('This is a system important role and cannot be deleted in this way!', 'Delete Role', {
                        dangerouslyUseHTMLString: true,
                        confirmButtonText: 'Close',
                        type: 'error'
                    });
                } else {
                    this.$confirm('Are you sure you want to delete this role? This action cannot be reversed!', 'Delete Role', {
                        confirmButtonText: 'Delete',
                        showCancelButton: false,
                        confirmButtonClass: 'el-button--danger',
                        type: 'warning'
                    }).then(() => {
                        return axios.delete(route('api.roles.destroy', role.id))
                            .then((response) => {
                                this.$bus.$emit('role-manager:refresh')
                            })
                            .catch((error) => {
                            })
                    }).catch(() => {})
                }
            }
        },
        created() {
            this.fetchRoles()
            this.$bus.$on('role-manager:refresh', (params) => {
                this.fetchRoles()
            })
            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('role-manager:refresh')
            })
        }
    }
</script>

<style lang="scss">
    .permissions {
        display: flex;
        flex-wrap: wrap;
    }
    .permission {
        padding: 5px 12px;
        background-color: rgba(0,0,0, 0.1);
        margin: 2px;
    }
</style>
