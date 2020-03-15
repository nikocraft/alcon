<template>
    <div>
        <div class="form-title">Sign In</div>

        <div class="auth-form">
            <div class="form-group form-field">
                <input v-validate="'required|email'" type="email" name="email" v-model="email" class="form-control" placeholder="Email">
                <div class="field-error">{{ errors.first('email') }}</div>
            </div>

            <div class="form-group form-field">
                <input v-validate="'required|min:8'" type="password" name="password" v-model="password" class="form-control" placeholder="Password">
                <div class="field-error">{{ errors.first('password') }}</div>
            </div>

            <!-- <div class="form-info"> 
                <div v-if="errors.items.length == 0">Fill in both fields to sign in.</div>
                <template v-else> 

                </template>
            </div> -->

            <div v-if="authError" class="form-group form-error">
                {{ authErrorMessage }}
            </div>
        </div>

        <div class="form-group auth-options">
            <div class="remember-login">
                <el-checkbox v-model="remember">Remember Me</el-checkbox>
            </div>
            <div class="forgot-password">
                <a href="/auth/password/reset">Forgot your password</a>
            </div>
        </div>

        <div class="auth-button">
            <button v-if="!formSubmitted" @click="login" class="btn btn-auth">Sign In</button>
            <button v-else class="btn btn-auth"><div class="lds-dual-ring"></div></button>
        </div>

        <div v-if="allowRegistrations" class="goto-sign-up">Don't have an account? <a href="/auth/register">Sign Up</a></div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                formSubmitted: false,
                email: '',
                password: '',
                remember: false,
                authError: false,
                authErrorMessage: '',
                responseMessage: ''
            }
        },
        props: {
            allowRegistrations: {type: Boolean, default: false},
        },
        computed: {
            isFormValid() {
                return this.$validator.errors.items.length == 0
            },
            isFormUntouched() {
                return Object.keys(this.fields).some(key => this.fields[key].pristine);
            },
            isFormReady() {
                return this.isFormValid && !this.isFormUntouched
            }
        },
        methods: {
            resetForm() {
                this.email = ''
                this.password = ''
                this.remember = false
                this.authError = false
                this.authErrorMessage = ''
            },
            login() {
                this.$validator.validate().then(result => {
                    if(result & !this.formSubmitted) {
                        this.formSubmitted = true
                        this.authError = false
                        this.authErrorMessage = ''
                        let formData = {
                            email: this.email,
                            password: this.password,
                            remember: this.remember
                        }
                        return axios.post(route('login'), formData)
                            .then((response) => {
                                window.location = response.data.redirectTo
                            })
                            .catch((error) => {
                                this.formSubmitted = false
                                this.authError = true
                                if(error.response.data.error) {
                                    this.authErrorMessage = error.response.data.error
                                }
                                else if (error.response.data.errors && error.response.data.errors.email) {
                                    this.authErrorMessage = error.response.data.errors.email[0]
                                }
                                else if (error.response.data.errors && error.response.data.errors.password) {
                                    this.authErrorMessage = error.response.data.errors.password[0]
                                }
                            })
                    }
                })
            }
        }
    }
</script>
