<template>
    <div class="step">
        <div class="step-content">
            <h1 class="step-title">Database</h1>
            <h5>Fill in the required fields and we will take care of the rest.</h5>
            <div class="form-group">
                <label for="">Database Name</label>
                <input type="text" name="database" class="form-control" placeholder="db name" v-model="form.database" v-validate="'required'" />
                <span v-if="errors.has('database')">{{ errors.first('database') }}</span>
            </div>
            <div class="form-group">
                <label for="">Database Username</label>
                <input type="text" name="username" class="form-control" placeholder="db username" v-model="form.username" v-validate="'required'" />
                <span v-if="errors.has('username')">{{ errors.first('username') }}</span>
            </div>
            <div class="form-group">
                <label for="">Database Password</label>
                <input type="text" name="password" class="form-control" placeholder="db password" v-model="form.password" />
                <!-- <span v-if="errors.has('password')">{{ errors.first('password') }}</span> -->
            </div>
            <div class="form-group">
                <label for="">Database Host</label>
                <input type="text" name="host" class="form-control" placeholder="db host" v-model="form.host" v-validate="'required'" />
                <span v-if="errors.has('host')">{{ errors.first('host') }}</span>
            </div>

            <p v-if="dbTest == false">
                We could not connect to the "{{ form.database }}" database, please verify that you have entered correct settings. 
            </p>
        </div>
        <div style="display: flex; justify-content: flex-end; flex-direction: row;">
            <button class="btn btn-primary" style="margin-top: 12px; padding: 10px 20px;" @click="submitTrigger">Continue</button>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    database: 'laraone',
                    username: 'root',
                    password: '',
                    host: 'localhost'
                },
                loading: false,
                serverError: null,
                dbTest: null,
            }
        },
        watch: {
            'form.database'(val, old) {
                this.dbTest = null
            }
        },
        methods: {
            submitTrigger() {
                this.$validator.validateAll().then( result => {
                    if(result) {
                        let formData = {
                            ...this.form
                        }

                        this.loading = true
                        return axios.post(route('install.save-environment-settings'), formData)
                            .then(
                                ({data}) => {
                                    let {dbTest} = data
                                    this.dbTest = dbTest
                                    if(dbTest) {
                                        this.nextTrigger()
                                    }
                                },
                                (error) => {
                                    this.serverError = true
                                }
                            )
                            .then(() => {
                                this.loading = false
                            })

                    }
                })
            },
            nextTrigger() {
                this.$emit('go-next')
            }
        }
    }
</script>
