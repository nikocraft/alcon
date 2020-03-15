<template>
    <v-modal ref="userModal"
        name="user-modal"
        :classes="['v--modal', 'modal-content']"
        :overlay="true"
        transition="nice-modal-fade"
        :clickToClose="false"
        :width="800"
        :height="800"
        :delay="100"
        :draggable="'.modal-header'"
        :adaptive="false"
        :resizable="true">
        <!-- <div v-loading="!Object.keys(form).length"> -->
            <div class="modal-header">
                <h4 class="modal-title" v-text="(mode == 'create') ? 'Create User' : 'Update User'"/>
            </div>

            <VuePerfectScrollbar class="modal-body">
                <div>
                    <div class="form-group firstname-group" style="width: 50%; float:left; padding-right: 5px;">
                        <label for="firstname">First Name</label>
                        <input v-validate.persist="'alpha|min:2|max:25'" name="firstname" type="text" placeholder="Enter first name" class="form-control firstname" v-model="user.firstname" key="firstname-input">
                        <span v-if="errors.has('firstname')">{{ errors.first('firstname') }}</span>
                    </div>
                    <div class="form-group lastname-group" style="width: 50%; float:left;">
                        <label for="lastname">Last Name</label>
                        <input v-validate.persist="'alpha|min:2|max:25'" name="lastname" type="text" placeholder="Enter last name" class="form-control lastname" v-model="user.lastname" key="lastname-input">
                        <span v-if="errors.has('lastname')">{{ errors.first('lastname') }}</span>
                    </div>
                    <div style="clear: both;"></div>
                </div>

                <div>
                    <div class="form-group username-group" style="width: 50%; float:left; padding-right: 5px;">
                        <label for="username">Username</label>
                        <input v-validate.persist="'required|alpha_dash'" name="username" type="text" placeholder="Enter username" class="form-control username" v-model="user.username" key="username-input">
                        <span v-if="errors.has('username')">{{ errors.first('username') }}</span>
                    </div>

                    <div class="form-group email-group" style="width: 50%; float:left;">
                        <label for="email">Email</label>
                        <input v-validate.persist="'required|email'" name="email" type="text" placeholder="Enter email" class="form-control email" v-model="user.email" key="email-input">
                        <span v-if="errors.has('email')">{{ errors.first('email') }}</span>
                    </div>

                    <div style="clear: both;"></div>
                </div>

                <div v-if="mode == 'update'" class="form-group approved-group">
                    <label for="approved">Account Status</label>
                    <select class="form-control" v-model="user.approved">
                        <option :value="true">Approved</option>
                        <option :value="false">Not Approved</option>
                    </select>
                </div>

                <div v-if="mode == 'create'" class="form-group bio-group">
                    <label for="bio">Password</label>
                    <input v-validate="'required|min:8'" name="password" type="text" placeholder="Enter password" class="form-control password" v-model="user.password" key="password-input">
                    <span v-if="errors.has('password')">{{ errors.first('password') }}</span>
                </div>

                <div class="form-group bio-group">
                    <label for="bio">Bio</label>
                    <textarea rows="3" name="bio" id="bio" class="form-control bio" style="resize: vertical;" v-model="user.bio"></textarea>
                </div>

                <div class="form-group">
                    <label for="bio">Roles</label>
                    <div class="b-roles-selector">
                        <el-checkbox-group v-model="checkedRoles" @change="handleRolesChange" v-validate.persist="'required'" name="roles">
                            <el-checkbox v-for="role in roles" :label="role.id" :key="role.id">{{role.display_name}}</el-checkbox>
                        </el-checkbox-group>
                        <span v-if="errors.has('roles')">{{ errors.first('roles') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="roles">Permissions</label>
                    <div class="permissions">
                        <el-checkbox v-for="item in filteredPermissions" :label="item.id" :key="item.id" :value="true" disabled>{{item.display_name}}</el-checkbox>
                    </div>
                </div>

                <div class="form-group">
                    <label for="roles">Extra Permissions</label>
                    <div class="permissions">
                        <el-checkbox-group v-model="checkedExtraPermissions">
                            <el-checkbox v-for="item in filteredExtraPermissions" :label="item.id" :key="item.id">{{item.display_name}}</el-checkbox>
                        </el-checkbox-group>
                    </div>
                </div>
            </VuePerfectScrollbar>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary pull-right"
                    @click="saveUser"
                    v-text="(mode == 'create') ? 'Create' : 'Update'"
                />
                <button type="button" class="btn btn-default pull-right" @click="closeModal">Cancel</button>
            </div>
        <!-- </div> -->
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
                user: {
                    id: '',
                    approved: false,
                    firstname: '',
                    lastname: '',
                    username: '',
                    password: '',
                    email: '',
                    roles: [],
                    extrapermissions: []
                },
                roles: [],
                permissions: [],
                checkedRoles: [],
                checkedExtraPermissions: [],
                callback: null
            }
        },
        computed: {
            filteredPermissions() {
                let output = _
                    .chain(this.roles)
                    .filter((o) => _.includes(this.checkedRoles, o.id))
                    .map('permissions')
                    .flatten()
                    .uniqBy('id')
                    .value()
                return output
            },
            filteredExtraPermissions () {
                let filteredPermissionsIds = _.map(this.filteredPermissions, 'id') || []
                let output = _.reject(this.permissions, o => filteredPermissionsIds.includes(o.id))

                return output
            }
        },
        watch: {
            user: {
                deep: true,
                immediate: true,
                handler(val, old) {
                    this.checkedRoles = _.cloneDeep(val.roles)
                    this.checkedExtraPermissions = _.cloneDeep(val.extrapermissions)
                }
            },
            checkedRoles(roles, old) {
                if(roles.length != (old || []).length) {
                    this.user.roles = roles
                }
            },
            checkedExtraPermissions(extrapermissions, old) {
                if(extrapermissions.length != (old || []).length) {
                    this.user.extrapermissions = extrapermissions
                }
            }
        },
        methods: {
            handleRolesChange(value) {
                this.correctForCheckedExtra()
            },
            correctForCheckedExtra() {
                let filteredExtraIds = _.map(this.filteredExtraPermissions, 'id') || []
                this.checkedExtraPermissions = _.filter(this.checkedExtraPermissions, v => filteredExtraIds.includes(v))
            },
            closeModal() {
                this.$modal.hide('user-modal')
            },
            saveUser() {
                this.$validator.validate().then(result => {
                    if(result) {
                        (this.mode == 'create') && this.$notify({
                            title: 'Info',
                            message: 'Creating user, please wait a second.',
                            type: 'info'
                        })
                        let id = this.user.id
                        let method = (id) ? 'patch' : 'post'
                        let url = (id) ? route('api.users.update', id) : route('api.users.store')
                        let data = _.pick(this.user, ['id', 'firstname', 'lastname', 'username', 'email', 'approved', 'password', 'bio', 'roles', 'extrapermissions'])

                        return axios({ method: method, url: url, data: data })
                            .then((response) => {
                                this.$modal.hide('user-modal')
                                this.makeReset()
                                this.$notify({
                                    position: 'top-right',
                                    offset: 80,
                                    title: 'Success',
                                    message: 'User saved successfully!',
                                    type: 'success'
                                })
                                if (this.callback) this.callback()
                            })
                            .catch((error) => {
                                this.$notify({
                                    position: 'top-right',
                                    offset: 80,
                                    title: 'Error',
                                    message: 'Error while trying to save user.',
                                    type: 'error'
                                })
                                console.error('UPDATE error', error)
                            })
                    } else {
                        this.$notify({
                            position: 'top-right',
                            offset: 80,
                            title: 'Error',
                            message: 'All required fields must be entered. User must have at least one role assigned.',
                            type: 'error'
                        })
                    }
                })
            },
            makeReset() {
                this.$validator.reset()
                this.user = {
                    id: '',
                    firstname: '',
                    lastname: '',
                    username: '',
                    email: '',
                    approved: '',
                    password: '',
                    roles: [],
                    extrapermissions: [],
                },
                this.roles = []
                this.checkedRoles = []
                this.checkedExtraPermissions = []
                this.permissions = []
            },
            fetchData(id) {
                this.makeReset()

                let request = (id) ?
                    axios.get(route('api.users.show', id))
                        .then(({data: responseData}) => {
                            let {data, roles, permissions} = responseData
                            this.user = data
                            this.user.roles = _.map(data.roles, 'id') || []
                            this.user.extrapermissions = _.map(data.permissions, 'id') || []
                            this.roles = roles
                            this.permissions = permissions
                        })
                        .catch((error) => {
                            this.$notify({
                                title: 'Error',
                                message: 'Error while loading user data',
                                type: 'error'
                            })
                            console.error('API error', error)
                        })
                    : axios.get(route('api.users.getallroles'))
                        .then(({data: responseData}) => {
                            let {data, permissions} = responseData
                            this.roles = data
                            this.permissions = permissions
                        })
                        .catch((error) => {
                            console.error('API error', error)
                        })

                return request
            },
        },
        created() {
            this.$bus.$on('open-modal:user-form', (params) => {
                let {user, cb = null} = params
                this.mode = user ? 'update' : 'create'
                this.fetchData(user && user.id)
                this.callback = cb
                this.$modal.show('user-modal')
            })
            this.$once('hook:beforeDestroy', () => {
                this.$validator.pause()
                this.$bus.$off('open-modal:user-form')
            })
        },
    }
</script>
