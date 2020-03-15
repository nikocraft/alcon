<template>
    <div class="step">
        <div class="step-content">
            <h1 class="step-title">Website</h1>
            <h5>Enter website title and create first user.</h5>
            <div class="form-group">
                <label>Website Title</label>
                <input type="text" name="sitetitle" class="form-control" placeholder="title" v-model="form.siteTitle" v-validate="'required'"/>
                <span v-if="errors.has('sitetitle')">{{ errors.first('sitetitle') }}</span>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" placeholder="username" v-model="form.username" v-validate="'required|alpha_dash'" />
                <span v-if="errors.has('username')">{{ errors.first('username') }}</span>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control" placeholder="email" v-model="form.email" v-validate="'required|email'" />
                <span v-if="errors.has('email')">{{ errors.first('email') }}</span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="password" v-model="form.password" v-validate="'required|min:8'" />
                <span v-if="errors.has('password')">{{ errors.first('password') }}</span>
            </div>
            <div v-if="loading" style="color: #474e52; font-weight: bold; display: flex; align-items: center; flex-direction: column;">Installation has started. Please wait a moment.</div>
            <div v-if="error" style="color: #e03434; font-weight: bold; display: flex; align-items: center; flex-direction: column;">Installation failed for some reason. Please report this problem.</div>
        </div>

        <div style="display: flex; align-items: flex-end; flex-direction: column;">
            <button v-if="!loading" class="btn btn-primary" style="width: 120px; margin-top: 12px; padding: 10px 20px;" @click="submitTrigger">Install</button>
            <button v-if="loading" class="btn btn-primary" style="width: 120px; margin-top: 12px; padding: 0px 20px;"><div class="install-loader"></div></button>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    siteTitle: '',
                    username: '',
                    email: '',
                    password: ''
                },
                loading: false,
                installed: false,
                error: false,
                serverError: null
            }
        },
        methods: {
            submitTrigger() {
                this.$validator.validateAll().then( result => {
                    if(result) {
                        this.install()
                    }
                })
            },
            install() {
                this.loading = true
                let formData = {
                    ...this.form
                }
                return axios.post(route('install'), formData)
                    .then((response) => {
                        this.loading = false
                        this.installed = true
                        this.nextTrigger()
                    })
                    .catch((error) => {
                        this.loading = false
                        this.installed = false
                        this.error = true
                    })
            },
            nextTrigger() {
                this.$emit('go-next')
            },
            gotoLogin() {
                window.location.href = route('login')
            }
        }
    }
</script>


<style scoped>
    .install-loader {
        display: flex;
        height: 40px;
        justify-content: center;
        align-items: center;
        flex-direction: row;
    }
    .install-loader:after {
        content: " ";
        display: block;
        width: 32px;
        height: 32px;
        margin: 1px;
        border-radius: 50%;
        border: 5px solid #fff;
        border-color: #fff transparent #fff transparent;
        animation: install-loader 1.2s linear infinite;
    }
    @keyframes install-loader {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
