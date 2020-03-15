<template>
    <vue-medium-editor
        v-bind:style="headlineStyles"
        :class="settings.customClass"
        :text='blockContent'
        :custom-tag='settings.headlineTag'
        :options="mediumEditorOptions"
        v-on:edit='updateBlockContent' />
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    // Editor
    import VueMediumEditor from '~/components/ui/vue-medium-editor-extended/medium-editor'
    import Colorpicker from '~/utils/medium-editor-plugins/colorpicker'

    export default {
        components: {
            VueMediumEditor,
        },
        mixins: [GeneralMixin],
        data() {
            return {
                mediumEditorOptions: {
                    toolbar: {
                        buttons: [{
                            name: 'bold',
                            contentDefault: '<i class="lo-icon lo-icon-bold"></i>'
                        }, {
                            name: 'italic',
                            contentDefault: '<i class="lo-icon lo-icon-italic"></i>'
                        }, {
                            name: 'colorPicker'
                        }, {
                            name: 'justifyLeft',
                            contentDefault: '<i class="lo-icon lo-icon-align-left"></i>'
                        }, {
                            name: 'justifyCenter',
                            contentDefault: '<i class="lo-icon lo-icon-align-center"></i>'
                        }, {
                            name: 'justifyRight',
                            contentDefault: '<i class="lo-icon lo-icon-align-right"></i>'
                        }]
                    },
                    buttonLabels: false,
                    placeholder: {
                        text: 'Headline',
                        hideOnClick: true
                    },
                    extensions: {
                        colorPicker: this.colorpickerInstance.pickerExtension,
                        'imageDragging': {}
                    }
                },
                blockContent: this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) ? this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) : '',
            }
        },
        computed: {
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
                }
            },
            headlineStyles() {
                let styleObj = {
                    fontFamily: this.settings.fontFamily,
                    fontSize: (this.settings.headlineTag == 'div') ? this.settings.fontSize : null,
                    color: this.settings.textColor,
                    fontWeight: (this.settings.fontWeight != 'default') ? this.settings.fontWeight : null,
                    padding: this.settings.padding,
                    textShadow: this.settings.textShadow,
                    backgroundColor: this.settings.backgroundColor
                }

                return styleObj
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
        },
        beforeCreate() {
            this.colorpickerInstance = new Colorpicker()
        }
    }
</script>

<style lang="scss">
    //
</style>
