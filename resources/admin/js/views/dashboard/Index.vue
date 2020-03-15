<template>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Dashboard</h3>
            </div>
            <div class="box-body dashboard">
                <h3>This is a Dashboard, not much to see here currently.</h3>
                <h3>We'll be improving Dashboard in the coming releases.</h3>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
            }
        },
        computed: {
        },
        methods: {
        },
        mounted() {
        }
    }
</script>

<style lang="scss" scoped>
    .dashboard {
        display: flex;
        align-items: center;
        flex-direction: column;
        padding: 200px;
    }
</style>
