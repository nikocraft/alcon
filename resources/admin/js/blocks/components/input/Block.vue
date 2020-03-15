<template>
    <div class="form-group">
        <input v-model="inputContent.value" class="form-control" :class="settings.inputClass" type="text" :placeholder="'Enter '+settings.blockTitle">
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        data() {
            return {
                inputContent: {
                    label: '',
                    value: '',
                },
            }
        },
        computed: {
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
                }
            },
            settings() {
                return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
            },
            content() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            },
        },
        watch: {
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            inputContent: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        mounted() {
            if(this.content) {
                this.inputContent = this.content
            }
        },
    }
</script>

<style lang="scss">
    //
</style>
