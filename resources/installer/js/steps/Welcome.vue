
<template>
    <div class="step">
        <h1 class="step-title">Welcome</h1>
        <div class="welcome-text">
            Welcome to Laraone CMS installation wizard. We've made the install process fast and easy.
            Just make sure that you have have correct database information so you can complete next steps.
            In case you are missing the necessary database information, please get in touch with your hosting provider.
        </div>
        <div style="display: flex; align-items: flex-end; flex-direction: column;">
            <button class="btn btn-primary" style="margin-top: 12px; padding: 10px 20px;" @click="nextTrigger">Continue</button>
        </div>
        <!-- <el-button class="btn btn-primary" style="margin-top: 12px;" @click="nextTrigger">Next</el-button> -->
    </div>
</template>

<script>
    export default {
        methods: {
            nextTrigger() {
                this.$emit('go-next')
            }
        }
    }
</script>
