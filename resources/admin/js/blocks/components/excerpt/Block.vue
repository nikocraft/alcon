<template>
    <div class="form-group">
        <textarea v-model="blockContent" class="form-control"></textarea>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        data() {
            return {
                blockContent: this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) ? this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) : '',
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
            blockContent: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            }
        },
        methods: {
            updateBlockContent: function (operation) {
                this.blockContent = operation.event.target.innerHTML
            }
        }
    }
</script>