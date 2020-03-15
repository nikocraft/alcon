<template>
    <div class="slide" :style="slideStyles" v-on:click="chooseTrigger">
        <draggable
            v-model="subItems"
            :style="itemStyles"
            group="slide"
            handle=".lo-icon-move"
            chosenClass="dragging1">
            <template v-for="block in subItems">
                <component-wrapper
                    :type="block.type"
                    :uniqueId="block.uniqueId"
                    :settings="block.settings"
                    :container-preview="true"
                    :show-headers="false"
                    :show-labels="false"
                    v-on:remove="removeBlock(block.uniqueId)"
                    :storePath="storePath"
                    :key="block.uniqueId">
                </component-wrapper>
            </template>
        </draggable>
    </div>
</template>

<script>
    import GeneralMixin from '../../../mixins/GeneralMixin'
    import ComponentWrapper from '../../../ComponentWrapper'
    import { mapGetters, mapActions } from 'vuex'
    import draggable from 'vuedraggable'
    import { getComponentByName, processSettingsConfig } from '~/utils/helpers.js'

    export default {
        components: {
            draggable,
            ComponentWrapper
        },
        mixins: [GeneralMixin],
        data() {
            return {
                image: null,
                editingSlide: false,
                currentIndex: null,
            }
        },
        props: {
            visible: {type: Boolean, default: false},
            slider: {type: Object, default: null},
            contentRenderStyle: {type: String, default: 'boxed'},
            padding: {type: String, default: '0px'},
            config: {type: Object, default: null},
        },
        computed: {
            subItems: {
                get() {
                    return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            },
            settings: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
                }
            },
            container() {
                if(this.contentRenderStyle == 'boxed')
                    return 'container'
                else
                    return 'fluid'
            },
            itemStyles() {
                let styles = ""
                styles = styles + 'height: 100%;'
                styles = styles + 'display: '+this.settings.display+';'
                styles = styles + 'flex-direction: '+this.settings.flexDirection+';'
                styles = styles + 'justify-content: '+this.settings.justifyContent+';'
                styles = styles + 'align-items: '+this.settings.alignItems+';'

                return styles
            },
            slideStyles() {
                let styles = ""
                if(this.image) {
                    styles = styles + 'background-image: url("' +this.image+'");'
                    styles = styles + 'background-size: cover;'
                    styles = styles + 'background-position: center;'
                    styles = styles + 'background-position: center;'
                }
                styles = styles + 'padding: ' + this.config.padding

                return styles
            },
            content() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            },
        },
        watch: {
            openMediaModalSwitch(val, old) {
                if(val == true) {
                    this.openMediaModal()
                }
            },
            content(val, old) {
                this.image = val
            },
            // image: {
            //     handler(val) {
            //         this.updateItemContent({content: val, uniqueId: this.uniqueId})
            //     },
            //     deep: true
            // },
        },
        methods: {
            getComponentTitle(uniqueId) {
                let component = this.$store.getters[`${this.storePath}/item`](uniqueId)
                return component.settings.blockTitle
            },
            getComponentType(uniqueId) {
                let component = this.$store.getters[`${this.storePath}/item`](uniqueId)
                return component.type
            },
            getComponentSettings(uniqueId) {
                let component = this.$store.getters[`${this.storePath}/item`](uniqueId)
                return component.settings
            },
            responsiveSwitch(val) {
                this.settings[val] = !this.settings[val]
            },
            editSlide(val) {
                this.editingSlide = val
                this.$emit('edit-slide', this.editingSlide)
            },
            openMediaModal() {
                var mode = 'insertImage'
                var params = new Object
                // params.imageId = this.imageId
                params.id = this._uid

                params.cb = (image) => {
                    this.image = image.original
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            addLayer(compName = 'SlideLayer') {
                let comp = getComponentByName(compName)

                if (!comp) {
                    console.error('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    title: customSettings.blockTitle.default,
                    parentId: this.uniqueId,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            addLayerItem(compName = undefined) {
                let comp = getComponentByName(compName)

                if (!comp) {
                    console.error('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    title: customSettings.blockTitle.default,
                    parentId: this.uniqueId,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            removeLayer(uniqueId) {
                this.removeItem({
                    id: uniqueId,
                    parentId: this.uniqueId
                })
            },
            removeLayerItem(uniqueId, layerId) {
                this.removeItem({
                    id: uniqueId,
                    parentId: layerId
                })
            },
            removeBlock() {
                this.$emit('remove', this.uniqueId)
            },
            processContent(content) {
                this.inputContent = new Object()
            },
            chooseTrigger(e) {
                if (e.target == this.$el || e.target.parentNode == this.$el) {
                    this.$emit('openMedia')
                }
            }
        },
        mounted() {
            this.image = this.content
        }
    }
</script>

<style scoped lang="scss">
    .header {
        opacity: 1
    }

    .fa-cog {
        margin: 0
    }

    .slide {
        // position: absolute;
        // overflow: hidden;
        height: 100%;
        width: 100%;
        // display: flex;
        // align-items: center;
        // justify-content: center;
        // min-height: 150px;
    }

    .zindex10 {
    }

    .layers {
        position: absolute;
        left:0;
        top: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }

    .add-layer-container {
        top: 20px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        position: absolute;
        text-align: center;
        pointer-events: none;
    }

    .add-layer {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 5px 10px;
        margin: 0;
        cursor: pointer;
        border-radius: 0%;
        background-color: rgba(43, 92, 147, 0.65);
        color: #b8c7ce;

        pointer-events: all;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }
    }

    .slide-layers {
        font-size: 13px;
    }

    .slide-layer {
        padding: 5px 10px;
        background-color: rgba(54, 112, 176, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.25);
        cursor: pointer;
        margin-bottom: 2px;
    }

    .slide-layer-block {
        padding: 5px 10px;
        margin-left: 20px;
        margin-bottom: 2px;
        background-color: rgba(54, 112, 176, 0.1);
        border: 1px solid rgba(0, 0, 0, 0.2);
        cursor: pointer;
    }

    .layer-active {
        background-color: rgba(54, 112, 176, 0.45);
        border: 1px solid rgba(0, 0, 0, 0.45);
    }

    .slide-layer-buttons {

        position: absolute;
        right: 10px;
        width: 120px;
    }

    .slide-layer-button {
        background-color: rgba(54, 112, 176, 0.2);
        border: 1px solid rgba(0, 0, 0, 0.25);
        cursor: pointer;
        padding: 5px 22px;
        padding-left: 5px;
        margin-bottom: 2px;
    }

    .fa-file-text {
        margin-right: 5px;
        font-size: 14px;
    }


</style>
