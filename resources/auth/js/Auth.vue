<template>
    <div class="box">
        <div class="box-header">
            <img v-if="logoType == 'image'" :src="logoImage" class="logo-image img-responsive">
            <div v-else class="logo-text">
                <span>{{ logoText }}</span>
            </div>
        </div>

        <div class="box-body">
            <register v-if="action == 'register'" :require-fullname="requireFullname"></register>
            <login v-if="action == 'login'" :allow-registrations="allowRegistrations"></login>
            <activate v-if="action == 'activate'" :auto-approve="autoApprove" :activated="activated" :signature-valid="signatureValid"></activate>
            <forgot-password v-if="action == 'forgot-password'"></forgot-password>
            <reset-password v-if="action == 'reset-password'" :token="token" :email="email"></reset-password>
        </div>

        <div class="box-footer">
            <div class="powered-by">powered by <a href="https://laraone.com">LaraOne</a></div>
        </div>
    </div>
</template>

<script>
    import Login from './partials/Login'
    import Activate from './partials/Activate'
    import Register from './partials/Register'
    import ForgotPassword from './partials/password/ForgotPassword'
    import ResetPassword from './partials/password/ResetPassword'

    export default {
        components: {
            Login,
            Activate,
            Register,
            ForgotPassword,
            ResetPassword
        },
        data() {
            return {
                logoType: window.authPage.logoType,
                logoImage: window.authPage.logoImage,
                logoText: window.authPage.logoText
            }
        },
        props: {
            action: {type: String, default: 'login' },
            userId: {type: Number, default: 0},
            activated: {type: Boolean, default: false},
            autoApprove: {type: Boolean, default: false},
            allowRegistrations: {type: Boolean, default: false},
            requireFullname: {type: Boolean, default: true},
            signatureValid: {type: Boolean, default: false},
            token: {type: String, default: null},
            email: {type: String, default: null}
        }
    }
</script>
