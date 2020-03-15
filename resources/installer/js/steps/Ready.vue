
<template>
    <div class="step">
        <h1 class="step-title">Ready</h1>
        <div class="welcome-text">
            Installation has completed successfully. You can proceed to the login page. 
            This wizard will be disabled for security reasons. 
            To be able to access it again you have to reset the database.
        </div>
        <div style="display: flex; align-items: flex-end; flex-direction: column;">
            <button class="btn btn-primary" style="margin-top: 12px; padding: 10px 20px;" @click="gotoLogin">Go To Login</button>
        </div>
        <!-- <el-button class="btn btn-primary" style="margin-top: 12px;" @click="nextTrigger">Next</el-button> -->
    </div>
</template>

<script>
    export default {
        methods: {
            gotoLogin() {
                window.location.href = route('login')
            }
        }
    }
</script>
