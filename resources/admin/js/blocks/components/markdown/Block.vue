<template>
    <markdown
        v-model="blockContent"
        :uniqueId="'markdown-'+this.uniqueId"
        :show-toolbar="settings.showToolbar">
    </markdown>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    // Editors
    import Markdown from '~/components/ui/Markdown.vue'

    export default {
        components: {
            Markdown,
        },
        mixins: [GeneralMixin],
        data() {
            return {
                markdownPreview: false,
                blockContent: this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) ? this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) : '',
            }
        },
        computed: {
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
                }
            },
            compiledMarkdown() {
                return marked(this.content, { sanitize: true })
            },
            settings() {
                return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
            },
            content() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            }
        },
        watch: {
            blockContent(val, old) {
                this.updateItemContent({content:  val, uniqueId: this.uniqueId})
            },
            settings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
                },
                deep: true
            }
        },
        methods: {
            updateBlockContent(operation) {
                this.blockContent = operation.event.srcElement.innerHTML
            }
        }
    }
</script>
