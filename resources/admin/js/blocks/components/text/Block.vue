<template>
    <div :style="textStyles">
        <vue-medium-editor
            :ref="'text-editor-' + uniqueId"
            :class="settings.contentClass"
            :text='blockContent'
            custom-tag='div'
            :options="mediumEditorOptions"
            v-on:edit='updateBlockContent' />
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    // Editors
    import VueMediumEditor from '~/components/ui/vue-medium-editor-extended/medium-editor'
    import Colorpicker from '~/utils/medium-editor-plugins/colorpicker'

    export default {
        components: {
            VueMediumEditor,
        },
        mixins: [GeneralMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Text'},
            textClass: {type: String, default: ''},
            showEditorToolbar: {type: Boolean, default: false},
        },
        data() {
            return {
                mediumEditorOptions: {
                    toolbar: {
                        buttons: [
                            {
                                name: 'bold',
                                contentDefault: '<i class="lo-icon lo-icon-bold"></i>'
                            }, 
                            {
                                name: 'italic',
                                contentDefault: '<i class="lo-icon lo-icon-italic"></i>'
                            }, 
                            {
                                name: 'colorPicker'
                            },
                            {
                                name: 'strikethrough',
                                contentDefault: '<i class="lo-icon lo-icon-strike"></i>'
                            },
                            {
                                name: 'anchor',
                                contentDefault: '<i class="lo-icon lo-icon-link"></i>'
                            },
                            {
                                name: 'h1',
                                contentDefault: '<i class="lo-icon lo-icon-header"></i><sup>1</sup>'
                            },
                            {
                                name: 'h2',
                                contentDefault: '<i class="lo-icon lo-icon-header"></i><sup>2</sup>'
                            },
                            {
                                name: 'h3',
                                contentDefault: '<i class="lo-icon lo-icon-header"></i><sup>3</sup>'
                            },
                            {
                                name: 'h4',
                                contentDefault: '<i class="lo-icon lo-icon-header"></i><sup>4</sup>'
                            },
                            {
                                name: 'indent',
                                contentDefault: '<i class="lo-icon lo-icon-indent-right"></i>'
                            },
                            {
                                name: 'outdent',
                                contentDefault: '<i class="lo-icon lo-icon-indent-left"></i>'
                            },
                            {
                                name: 'orderedlist',
                                contentDefault: '<i class="lo-icon lo-icon-list-numbered"></i>'
                            },
                            {
                                name: 'unorderedlist',
                                contentDefault: '<i class="lo-icon lo-icon-list-bullet"></i>'
                            },
                            {
                                name: 'justifyLeft',
                                contentDefault: '<i class="lo-icon lo-icon-align-left"></i>'
                            },
                            {
                                name: 'justifyCenter',
                                contentDefault: '<i class="lo-icon lo-icon-align-center"></i>'
                            },
                            {
                                name: 'justifyRight',
                                contentDefault: '<i class="lo-icon lo-icon-align-right"></i>'
                            }
                        ]
                    },
                    buttonLabels: false,
                    placeholder: {
                        text: '',
                        hideOnClick: true
                    },
                    extensions: {
                        colorPicker: this.colorpickerInstance.pickerExtension,
                        'imageDragging': {}
                    }
                },
                blockContent: this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) || ''
            }
        },
        computed: {
            textStyles() {
                let styleObj = {
                    color: this.settings.textColor,
                    fontSize: this.settings.fontSize,
                    lineHeight: this.settings.fontLineHeight,
                    padding: this.settings.padding,
                    textShadow: this.settings.textShadow,
                    backgroundColor: this.settings.backgroundColor
                }

                return styleObj
            },
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
            }
        },
        watch: {
            blockContent(val, old) {
                this.updateItemContent({content:  val, uniqueId: this.uniqueId})
            }
        },
        methods: {
            updateBlockContent(operation) {
                // this.blockContent = operation.event.srcElement.innerHTML
                this.blockContent = operation.api.origElements.innerHTML
            }
        },
        mounted(){
            this.$refs['text-editor-' + this.uniqueId].api.elements[0].focus()
        },
        beforeCreate() {
            this.colorpickerInstance = new Colorpicker()
        }
    }
</script>

<style lang="scss" scoped>
    .medium-editor-element p {
        margin: 0;
    }

    .medium-editor-element {
        word-wrap: break-word;
        min-height: 3em;
    }
</style>
