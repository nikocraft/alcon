<template>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Mail Settings</h3>
                <div class="header-tools">
                    <button @click="save" name="save" class="btn btn-block btn-primary btn-header">Save</button>
                </div>
            </div>
            <div v-if="settings" class="box-body noselect">
                <div class="form-group">
                    <label>From Email Address</label>
                    <input name="fromAddress" type="text" class="form-control" v-model="settings.fromaddress">
                </div>

                <div class="form-group">
                    <label>Driver</label>
                    <select name="driver" class="form-control" v-model="settings.maildriver">
                        <option value="sendmail">Sendmail</option>
                        <option value="smtp">SMTP</option>
                        <option value="mailgun">Mailgun</option>
                    </select>
                </div>

                <template v-if="settings.maildriver == 'sendmail'"> 
                    <div class="form-group">
                        <label>Sendmail Path</label>
                        <input name="sendmail" type="text" class="form-control" v-model="settings.sendmail">
                    </div>
                </template>

                <template v-if="settings.maildriver == 'smtp'"> 
                    <div class="form-group">
                        <label>Host</label>
                        <input name="host" type="text" class="form-control" v-model="settings.host">
                    </div>

                    <div class="form-group">
                        <label>Port</label>
                        <input name="port" type="text" class="form-control" v-model="settings.port">
                    </div>

                    <div class="form-group">
                        <label>Username</label>
                        <input name="username" type="text" class="form-control" v-model="settings.username">
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input name="password" type="text" class="form-control" v-model="settings.password">
                    </div>

                    <div class="form-group">
                        <label>Encryption</label>
                        <input name="encryption" type="text" class="form-control" v-model="settings.encryption">
                    </div>
                </template>

                <template v-if="settings.maildriver == 'mailgun'"> 
                    <div class="form-group">
                        <label>Domain</label>
                        <input name="mailgunDomain" type="text" class="form-control" v-model="settings.mailgundomain">
                    </div>

                    <div class="form-group">
                        <label>Secret</label>
                        <input name="mailgunSecret" type="text" class="form-control" v-model="settings.mailgunsecret">
                    </div>

                    <div class="form-group">
                        <label>Endpoint</label>
                        <input name="mailgunEndpoint" type="text" class="form-control" v-model="settings.mailgunendpoint">
                    </div>
                </template>
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
        computed: {
        },
        methods: {
            save() {
                return axios.post(route('api.settings.mail.store'), this.settings)
                    .then((response) => {
                        //
                    })
                    .catch((error) => {
                        //
                    })
            },
            getSettings() {
                return axios.get(route('api.settings.mail.index'))
                    .then(({data}) => {
                        this.settings = data.data
                    })
                    .catch((error) => {
                        console.error('API error', error)
                    })
            }
        },
        created() {
            this.getSettings()
        }
    }
</script>
