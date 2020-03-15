<template>
    <div>
        <div class="auth-data">
            <template v-if="!responseMessage">
                <div class="form-title">Reset Your Password</div>

                <div class="form-group form-field">
                    <input v-validate="'required|min:8'" name="password" ref="password" type="password" v-model="password" class="form-control" placeholder="Password">
                    <div class="field-error">{{ errors.first('password') }}</div>
                </div>

                <div class="form-group form-field">
                    <input v-validate="'required|confirmed:password'" name="passwordConfirmation" type="password" v-model="passwordConfirmation" class="form-control" placeholder="Confirm Password">
                    <div class="field-error">{{ errors.first('passwordConfirmation') }}</div>
                </div>

                <div v-if="authErrorMessage" class="form-group form-error">
                    {{ authErrorMessage }}
                </div>
            </template>

            <template v-else>
                <div class="form-group" style="font-weight: bold; min-height: 50px;">
                    {{ responseMessage }}
                </div>
            </template>
        </div>

        <div class="auth-button">
            <button v-if="!responseMessage" @click="resetPassword" class="btn btn-auth">Reset Password</button>
            <a v-else :href="redirectTo" class="btn btn-auth">Go to Admin</a>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                password: '',
                passwordConfirmation: '',
                redirectTo: '',
                authError: false,
                authErrorMessage: '',
                responseMessage: ''
            }
        },
        props: {
            token: {type: String, default: null},
            email: {type: String, default: null},
        },
        computed: {
        },
        methods: {
            resetForm() {
                this.email = ''
                this.remember = false
                this.authError = false
                this.authErrorMessage = ''
            },
            resetPassword() {
                this.authError = false
                this.authErrorMessage = ''
                this.authError = false
                let formData = {
                    email: this.email,
                    token: this.token,
                    password: this.password,
                    password_confirmation: this.passwordConfirmation
                }
                return axios.post(route('password.reset.post'), formData)
                    .then((response) => {
                        this.responseMessage = response.data.status
                        this.redirectTo = response.data.redirectTo
                    })
                    .catch((error) => {
                        this.authError = true
                        this.authErrorMessage = error.response.data.message ? error.response.data.message : error.response.data.error ? error.response.data.error : error.response.data.email
                    })
            }
        },
        created () {
            // this.token = window.location.pathname.split("/").pop()
        }
    }
</script>
