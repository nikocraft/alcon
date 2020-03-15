<template>
    <div>
        <template v-if="!registered">
            <div class="form-title">Sign up</div>
            <div class="auth-form">
                <template v-if="requireFullname">
                    <div class="form-group form-field">
                        <input v-validate="'required|alpha|min:2|max:25'" type="text" name="firstname" v-model="firstname" class="form-control" placeholder="Firstname">
                        <div class="field-error">{{ errors.first('firstname') }}</div>
                    </div>
                    <div class="form-group form-field">
                        <input v-validate="'required|alpha|min:2|max:25'" type="text" name="lastname" v-model="lastname" class="form-control" placeholder="Lastname">
                        <div class="field-error">{{ errors.first('lastname') }}</div>
                    </div>
                </template>

                <div class="form-group form-field">
                    <input v-validate="'required|alpha_dash|min:3'" type="text" name="username" v-model="username" class="form-control" placeholder="Username">
                    <div class="field-error">{{ errors.first('username') }}</div>
                </div>

                <div class="form-group form-field">
                    <input v-validate="'required|email'" type="email" name="email" v-model="email" class="form-control" placeholder="Email">
                    <div class="field-error">{{ errors.first('email') }}</div>
                </div>

                <div class="form-group form-field">
                    <input v-validate="'required|min:8'" ref="password" type="password" name="password" v-model="password" class="form-control" placeholder="Password">
                    <div class="field-error">{{ errors.first('password') }}</div>
                </div>

                <!-- <div class="form-group">
                    <input v-validate="'required|confirmed:password'" type="password" name="passwordConfirmation" v-model="passwordConfirmation" class="form-control" placeholder="Confirm Password" data-vv-as="password">
                    <span>{{ errors.first('passwordConfirmation') }}</span>
                </div> -->

                <div v-if="authError" class="form-group form-error">
                    {{ authErrorMessage }}
                </div>
            </div>

            <div class="auth-button">
                <button v-if="!formSubmitted" @click="register" class="btn btn-auth">Create Account</button>
                <button v-else class="btn btn-auth"><div class="lds-dual-ring"></div></button>
            </div>

            <div class="goto-sign-in" style="text-align: center;">Have an account already? <b><a href="/auth/login">Sign In</a></b></div>
        </template>
        <template v-else>
            <p>You have successfully registered an account! We've sent you an email to activate your account. Please check your SPAM folder just in case activation email does not show up in inbox folder.</p>
        </template>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                formSubmitted: false,
                registered: false,
                firstname: null,
                lastname: null,
                username: '',
                email: '',
                password: '',
                passwordConfirmation: '',
                authError: false,
                authErrorMessage: '',
                responseMessage: ''
            }
        },
        props: {
            requireFullname: {type: Boolean, default: true}
        },
        create() {
            this.$validator.localize('en', dict);
        },
        methods: {
            resetForm() {
                this.firstname = ''
                this.lastname = ''
                this.username = ''
                this.email = ''
                this.password = ''
                this.authError = false
                this.authErrorMessage = ''
            },
            register() {
                this.$validator.validate().then(result => {
                    if(result & !this.formSubmitted) {
                        this.formSubmitted = true
                        this.authError = false
                        this.authErrorMessage = ''
                        let formData = {
                            firstname: this.firstname,
                            lastname: this.lastname,
                            username: this.username,
                            email: this.email,
                            password: this.password
                        }
                        return axios.post(route('register'), formData)
                            .then((response) => {
                                this.registered = true
                            })
                            .catch((error) => {
                                this.formSubmitted = false
                                this.authError = true
                                if(error.response.data.error) {
                                    this.authErrorMessage = error.response.data.error
                                }
                                else if (error.response.data.errors && error.response.data.errors.username) {
                                    this.authErrorMessage = error.response.data.errors.username[0]
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
