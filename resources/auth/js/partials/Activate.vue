<template>
    <div>
        <div v-if="signatureValid">
            <template v-if="activated">
                <p v-if="autoApprove">
                    Your account has now been activated. <a href="/auth/login">Click here to Login!</a>
                </p>
                <p v-else>
                    Your account has now been activated. However before you can sign in, account needs to be approved by an Administrator.
                </p>
            </template>
            <p v-else>Account can't be activated for some reason.</p>
        </div>
        <div v-else>
            <p>Activation link has expired, account can't be activated.</p>
        </div>
    </div>
</template>

<script>
    import qs from 'qs'

    export default {
        data() {
            return {
                error: false,
                errorMessage: 'Activating the account has failed for some reason.'
            }
        },
        props: {
            activated: {type: Boolean, default: false},
            signatureValid: {type: Boolean, default: false},
            autoApprove: {type: Boolean, default: false},
        }
    }
</script>
