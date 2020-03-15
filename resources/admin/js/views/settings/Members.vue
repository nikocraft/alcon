<template>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Members Settings</h3>
                <div class="header-tools">
                    <button @click="save" name="save" class="btn btn-block btn-primary btn-header">Save</button>
                </div>
            </div>
            <div v-if="settings" class="box-body noselect">
                <div class="form-group">
                    <label>Allow Registration</label>
                    <select name="allowRegistrations" class="form-control" v-model="settings.allowRegistrations">
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                </div>
                <div v-if="settings.allowRegistrations==true">
                    <div class="form-group">
                        <label>Default Role</label>
                        <select name="defaultUserRole" class="form-control" v-model="settings.defaultUserRole">
                            <option value="member" selected="selected">Member</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Require Fullname</label>
                        <select name="requireFullname" class="form-control" v-model="settings.requireFullname">
                            <option :value ="true">Yes</option>
                            <option :value ="false">No</option>
                        </select>
                    </div>

                    <div v-if="settings.requireFullname" class="form-group">
                        <label>User Display Name</label>
                        <select name="userDisplayName" class="form-control" v-model="settings.userDisplayName">
                            <option value="fullname">Full Name</option>
                            <option value="username" selected="selected">Username</option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label>Use Recaptcha on registration</label>
                        <select name="useRecaptcha" class="form-control" v-model="settings.useRecaptcha">
                            <option :value ="true">Yes</option>
                            <option :value ="false">No</option>
                        </select>
                    </div> -->

                    <div class="form-group">
                        <label>New Registration Status</label>
                        <select name="autoApprove" class="form-control" v-model="settings.autoApprove">
                            <option :value ="true">Auto Approve</option>
                            <option :value ="false">Require Admin Review</option>
                        </select>
                    </div>
                    
                    <!-- 
                    <div class="form-group">
                        <label>Require a strong password?</label>
                        <select name="requireStrongPassword" class="form-control" v-model="settings.requireStrongPassword">
                            <option :value ="true">Yes</option>
                            <option :value ="false">No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Blacklist Username Words</label>
                        <input name="blacklistUserNameWords" type="text" v-model="settings.blacklistUserNameWords" class="form-control">
                        <small>Separate by using comma.</small>
                    </div>

                    <div class="form-group">
                        <label>New User Notification</label>
                        <select name="newUserNotification" class="form-control" v-model="settings.newUserNotification">
                            <option :value ="true">Yes</option>
                            <option :value ="false">No</option>
                        </select>
                    </div> -->
                </div>
             </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                settings: null
            }
        },
        methods: {
            save() {
                let formData = {
                    settings: this.settings,
                }

                return axios.post(route('api.settings.members.store'), formData)
                    .then((response) => {

                    })
                    .catch((error) => {

                    })
            },
        },
        mounted() {
            return axios.get(route('api.settings.members.index'))
                .then(({data}) => {
                    this.settings = data.data
                })
                .catch((error) => {
                    console.error('API error', error)
                })
        }
    }
</script>
