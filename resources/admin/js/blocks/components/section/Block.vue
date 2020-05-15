<template>
    <div :style="sectionStyles">
        <draggable
            v-model="subItems"
            :style="itemsWrapperStyles"
            group="sub"
            handle=".lo-icon-move"
            chosenClass="dragging1">
            <template v-for="block in subItems">
                <component-wrapper
                    :type="block.type"
                    :freshComponent="freshComponent"
                    :uniqueId="block.uniqueId"
                    :settings="block.settings"
                    :display="settings.display"
                    :show-headers="showBlockHeaders"
                    :show-labels="showBlockLabels"
                    v-on:remove="removeBlock(block.uniqueId)"
                    :key="block.uniqueId"
                    :storePath="storePath"
                    :ancestorSettings="propagatedSettings"/>
            </template>
        </draggable>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import ComponentWrapper from '../../ComponentWrapper'
    import { mapGetters, mapActions } from 'vuex'
    import draggable from 'vuedraggable'
    import tinycolor from 'tinycolor2'
    import { getComponentByName, processSettingsConfig } from '~/utils/helpers.js'

    export default {
        mixins: [GeneralMixin],
        components: {
            draggable,
            ComponentWrapper
        },
        data() {
            return {
                enablePreviewMode: true,
                showBlockHeaders: this.showHeaders,
                showBlockLabels: this.showLabels,
            }
        },
        computed: {
            settings: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
                }
            },
            showPreview() {
                if(this.settings.showPreview && this.settings.enablePreviewMode)
                    return true
                else
                    return false
            },
            itemStyles() {
                // let styles = ""
                // if(this.settings.display == 'flex' && this.settings.flexDirection == 'row') {
                //     styles = styles + 'flex: ' + this.settings.itemFlex+';'
                //     styles = styles + 'min-width: ' + this.settings.itemMinWidth+';'
                //     styles = styles + 'margin-right: ' + this.settings.itemSpacing+';'
                // }
                //
                // return styles
            },
            itemsWrapperStyles() {
                let styles = { 
                    minHeight: '110px',
                    height: '100%',
                    display: 'block',
                }

                return styles
            },
            sectionStyles() {
                let styles = ""
                if(this.showPreview) {
                    if(this.settings.backgroundImage) {
                        styles = styles + 'background-image: url('+this.settings.backgroundImage+');'
                        styles = styles + 'background-attachment: '+this.settings.backgroundStyle+';'
                        styles = styles + 'background-position: '+this.settings.backgroundPosition+';'
                        styles = styles + 'background-repeat: '+this.settings.backgroundRepeat+';'
                        // styles = styles + 'box-shadow: inset 0 0 0 2000px '+this.settings.backgroundColor+';'
                        styles = styles + 'background-size: '+this.settings.backgroundSize+';'
                    }
                    else {
                        styles = styles + 'background-color: '+this.settings.backgroundColor+';'
                    }

                    styles = styles + 'padding: '+this.settings.padding+';'
                    styles = styles + 'minHeight: '+this.settings.minHeight+';'
                    styles = styles + 'height: '+this.settings.height+';'
                }
                return styles
            },
            subItems: {
                get() {
                    return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            }
        },
        watch: {
            'settings.backgroundColor'(val, old) {
                var color1 = tinycolor(val)
            },
            showPreview(val, old) {
                //
            },
            showHeaders(val, old) {
                this.showBlockHeaders = val
            },
            showLabels(val, old) {
                this.showBlockLabels = val
            }
        },
        methods: {
            removeBlock(blockId) {
                this.removeItem({
                    id: blockId,
                    parentId: this.uniqueId
                })
            }
        }
    }
</script>
