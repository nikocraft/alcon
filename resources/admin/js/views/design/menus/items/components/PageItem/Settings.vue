<template>
    <div slot="block-settings">
        <div class="form-group">
            <label>Block Title</label>
            <input type="text" class="form-control" v-model="settings.blockTitle">
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: {
            uniqueId: {type: Number, default: 0},
        },
        customSettings: {
            blockTitle: {type: String, default: 'Standard'},
            textClass: {type: String, default: ''},
            showToolbar: {type: Boolean, default: true},
        },
        computed: {
            settings() {
                return this.$store.getters['menus/editor/itemSettings'](this.uniqueId)
            },
        },
        watch: {
            settings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        methods: {
            ...mapActions('menus/editor', [
                'updateItemSettings',
            ]),
        },
    }
</script>
