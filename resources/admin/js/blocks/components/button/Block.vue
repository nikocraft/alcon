<template>
    <div :style="buttonPosition">
        <button type="button"
            style="position: relative"
            class="btn"
            :style="buttonStyles"
            name="button">
            <vue-medium-editor
                :text='blockContent'
                :style="{'font-weight': settings.fontWeight}"
                :custom-tag='settings.textTag'
                :options="mediumEditorOptions"
                v-on:edit='updateBlockContent' />
        </button>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    import VueMediumEditor from '~/components/ui/vue-medium-editor-extended/medium-editor'

    export default {
        components: {
            VueMediumEditor
        },
        mixins: [GeneralMixin],
        data() {
            return {
                blockContent: this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) ? this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) : 'Button',

                mediumEditorOptions: {
                    toolbar: {buttons: []},
                    buttonLabels: 'fontawesome',
                    placeholder: {
                        text: 'Button',
                        hideOnClick: true
                    },
                }
            }
        },
        computed: {
            buttonStyles() {
                let styleObj = {
                    width: '100%',
                    padding: this.settings.padding,
                    // margin: this.settings.margin,
                    color: this.settings.textColor,
                    'font-size': (this.settings.textTag == 'div' ? this.settings.textSize : '' ),
                    'background-color': this.settings.backgroundColor,
                    'box-shadow': this.settings.boxShadow,
                    'text-shadow': this.settings.textShadow,
                    'border-radius': this.settings.borderRadius,
                }

                return styleObj
            },
            buttonPosition() {
                let styleObj = {
                    display: 'flex',
                    justifyContent: this.settings.alignButton
                }
                return styleObj
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
            }
        },
        methods: {
            updateBlockContent(operation) {
                this.blockContent = operation.event.target.innerHTML
            }
        }
    }
</script>

<style lang="scss" scoped>
    .medium-editor-element {
        word-wrap: break-word;
        min-height: 1em;
        p {
            margin: 0;
        }
    }
    button {
        > h1,
        > h2,
        > h3,
        > h4,
        > h5,
        > h6 {
            line-height: 1em;
        }
    }
</style>
