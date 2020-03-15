<template>
    <v-modal ref="roleModal"
        name="role-modal"
        :classes="['v--modal', 'modal-content']"
        :overlay="true"
        transition="nice-modal-fade"
        :clickToClose="true"
        :width="700"
        :height="650"
        :delay="100"
        :draggable="true"
        :adaptive="true"
        :resizable="false">

        <div class="modal-header">
            <h4 class="modal-title">{{ (mode == 'create') ? 'Create' : 'Edit' }} Role</h4>
        </div>

        <VuePerfectScrollbar class="modal-body">
            <div class="form-group">
                <label>Name</label>
                <input v-validate.persist="'required|alpha_dash|unique_role_name'" data-vv-as="Name" name="name" type="text" class="form-control" v-model="form.name" :disabled="mode=='update'">
                <span>{{ errors.first('name') }}</span>
            </div>
            <div class="form-group">
                <label>Display Name</label>
                <input v-validate.persist="'required|alpha_spaces'" data-vv-as="Display Name" name="display_name" type="text" class="form-control" v-model="form.display_name">
                <span>{{ errors.first('display_name') }}</span>
            </div>

            <div class="permissions">
                <div @click="handlePermission(permission)" :style="selectedPermissions(permission)" v-for="permission in allPermissions" class="permission selectable-permission">
                    {{ permission.display_name }}
                </div>
            </div>
        </VuePerfectScrollbar>

        <div class="modal-footer">
            <!-- <button type="button" class="btn btn-default pull-right">Cancel</button> -->
            <button type="button" class="btn btn-primary pull-right"
                @click="saveData"
                v-text="(mode == 'create') ? 'Create' : 'Update'"
            />
        </div>
    </v-modal>
</template>

<script>
    import VuePerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'

    export default {
        components: {
            VuePerfectScrollbar
        },
        data() {
            return {
                mode: 'create',
                form: {
                    id: '',
                    name: '',
                    display_name: '',
                    permissions: [],
                },
                allPermissions: []
            }
        },
        methods: {
            saveData() {
                this.$validator.validateAll().then( result => {
                    if(result) {
                        let id = this.form.id

                        return axios({
                            method: (id) ? 'patch' : 'post',
                            url: (id)
                                ? route('api.roles.update', id)
                                : route('api.roles.store'),
                            data: this.form
                        })
                            .then((response) => {
                                this.$modal.hide('role-modal')
                                this.resetForm()
                                this.$bus.$emit('role-manager:refresh')
                            })
                            .catch((error) => {
                                //
                            })
                    }
               })
            },
            resetForm() {
                this.form = {
                    id: '',
                    name: '',
                    display_name: '',
                    permissions: []
                }
            },
            fillForm(data = {}) {
                data.permissions = _.map(data.permissions || [], 'name')
                this.form = data
            },
            selectedPermissions(permission) {
                let styleObj = null
                if(this.form.permissions.includes(permission.name)) {
                    styleObj = {
                        border: '1px solid #3c8dbc',
                    }
                } else {
                    styleObj = {
                        border: '1px solid transparent',
                    }
                }
                return styleObj
            },
            handlePermission(permission) {
                if(!this.form.permissions.includes(permission.name))
                    this.form.permissions.push(permission.name)
                else
                    this.form.permissions.splice(this.form.permissions.indexOf(permission.name), 1)
            },
            closeModal() {
                this.$modal.hide('role-modal')
            }
        },
        created() {
            this.$bus.$on('open-modal:role-form', (params) => {
                let {role, permissions} = params
                this.mode = role ? 'update' : 'create'
                this.fillForm(role)
                this.allPermissions = permissions
                this.$modal.show('role-modal')
            })
            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('open-modal:role-form')
            })
        }
    }
</script>

<style scoped lang="scss">
    .selectable-permission {
        cursor: pointer;
    }
</style>
