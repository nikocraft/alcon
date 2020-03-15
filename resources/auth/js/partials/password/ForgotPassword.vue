<template>
    <div>
        <template v-if="!responseMessage"> 
            <div class="form-title">Forgot your password?</div>

            <p>Please enter the email you signed up with. An email will be sent with instructions about reseting the password.</p>

            <div class="auth-form">
                <div class="form-group form-field">
                    <input v-validate="'required|email'" type="email" name="email" v-model="email" class="form-control" placeholder="Email">
                    <div class="field-error">{{ errors.first('email') }}</div>
                </div>

                <div v-if="authErrorMessage" class="form-group form-error">
                    {{ authErrorMessage }}
                </div>
            </div>

            <div class="auth-button">
                <button v-if="!formSubmitted" @click="sendResetPasswordEmail" class="btn btn-auth">Send Email</button>
                <button v-else class="btn btn-auth"><div class="lds-dual-ring"></div></button>
            </div>

            <div class="goto-sign-in">Remembered your password already? <a href="/auth/login">Sign In</a></div>
        </template>

        <template v-else>
            <div class="form-title">Email Sent</div>
            <div class="form-group">
                An email with instructions on how to reset your password should be arriving soon. Please check your SPAM folder just in case reset email does not show up in inbox folder.
            </div>
        </template>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                formSubmitted: false,
                email: '',
                authError: false,
                authErrorMessage: '',
                responseMessage: null
            }
        },
        methods: {
            resetForm() {
                this.email = ''
                this.authError = false
                this.authErrorMessage = ''
            },
            sendResetPasswordEmail() {
                this.$validator.validate().then(result => {
                    if(result & !this.formSubmitted) {
                        this.formSubmitted = true
                        this.authError = false
                        this.authErrorMessage = ''
                        let formData = {
                            email: this.email
                        }
                        return axios.post(route('password.email'), formData)
                            .then((response) => {
                                this.responseMessage = response.data.response
                            })
                            .catch((error) => {
                                this.formSubmitted = false
                                this.authError = true
                                this.authErrorMessage = error.response.data.message ? error.response.data.message : error.response.data.email
                            })
                    }
                })
            }
        }
    }
</script>
