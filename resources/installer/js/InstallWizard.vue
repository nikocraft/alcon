<template>
    <div class="install-page">
        <div class="box install">
            <div class="box-body">
                <div class="steps">
                    <welcome v-if="active==0" @go-next="goNext"></welcome>
                    <database-settings v-if="active==1" @go-next="goNext" />
                    <website-settings v-else-if="active==2" @go-next="goNext" />
                    <ready v-else-if="active==3"/>
                </div>
            </div>
        </div>
            <div class="login-box-footer">
                <a href="https://laraone.com"><small style="color: white; text-shadow: 1px 1px 1px #616161;">powered by Laraone</small></a>
            </div>
    </div>
</template>

<script>
    import Welcome from './steps/Welcome'
    import DatabaseSettings from './steps/DatabaseSettings'
    import WebsiteSettings from './steps/WebsiteSettings'
    import Ready from './steps/Ready'

    export default {
        components: {
            Welcome,
            DatabaseSettings,
            WebsiteSettings,
            Ready
        },
        data() {
            return {
                step: 1,
                active: 0
            }
        },
        props: {
            initData: {type: Object, default: null}
        },
        methods: {
            goNext() {
                this.active++
            },
            goPrev() {
                this.active--
            }
        }
    }
</script>
