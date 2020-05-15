<template>
    <div :name="type + 'Component'" :class="containerClassList" ref="wrapper-container" :style="blockStyle">
        <div v-if="showHeaders" class="content-block-header -full">
            <i v-if="allowDraggable" class="lo-icon lo-icon-move" title="Reorder"></i>
            <i v-if="blockType == 'normal'" class="lo-icon lo-icon-cog" title="Settings" name="openSettingsModal" @click="openSettingsModal"></i>
            {{ settings.blockTitle }}
            <div class="pull-right">
                <template v-if="topToolbar.includes('addComponents')">
                    <i @click="openComponentsLibModal" class="lo-icon lo-icon-plus" name="addComponent" title="Add"></i>
                </template>
                <template v-if="topToolbar.includes('showPreview')">
                    <i v-if="settings.showPreview" class="lo-icon lo-icon-eye" @click="settings.showPreview=false" title="Hide Preview"></i>
                    <i v-if="!settings.showPreview" class="lo-icon lo-icon-eye-off" @click="settings.showPreview=true" title="Show Preview"></i>
                </template>
                <i @click="moveUp" class="lo-icon lo-icon-angle-circled-up"></i>
                <i @click="moveDown" class="lo-icon lo-icon-angle-circled-down"></i>
                <!-- <i @click="saveAsTemplate" class="lo-icon lo-icon-floppy" title="Save as Template"></i> -->
                <i @click="removeBlock" class="lo-icon lo-icon-trash-empty" name="deleteBlock" title="Delete"></i>
            </div>
        </div>

        <div v-else-if="showLabels" class="content-block-header -label">
            <div class="content-block-label">{{ settings.blockTitle }}</div>
            <div class="content-block-tools">
                <i v-if="allowDraggable" class="lo-icon lo-icon-move" title="Reorder"></i>
                <i v-if="blockType == 'normal'" class="lo-icon lo-icon-cog" title="Settings" name="openSettingsModal" @click="openSettingsModal()"></i>
            </div>
            <div class="pull-right content-block-tools-right">
                <template v-if="topToolbar.includes('addComponents')">
                    <i @click="openComponentsLibModal" class="lo-icon lo-icon-plus" name="addComponent" title="Add"></i>
                </template>
                <template v-if="topToolbar.includes('showPreview')">
                    <i v-if="settings.showPreview" class="lo-icon lo-icon-eye" @click="settings.showPreview=false" title="Hide Preview"></i>
                    <i v-if="!settings.showPreview" class="lo-icon lo-icon-eye-off" @click="settings.showPreview=true" title="Show Preview"></i>
                </template>
                <i @click="moveUp" class="lo-icon lo-icon-angle-circled-up"></i>
                <i @click="moveDown" class="lo-icon lo-icon-angle-circled-down"></i>
                <!-- <i @click="saveAsTemplate" class="lo-icon lo-icon-floppy" title="Save as Template"></i> -->
                <i @click="removeBlock" name="deleteBlock" class="lo-icon lo-icon-trash-empty" title="Delete"></i>
            </div>
        </div>

        <div class="content-block-body" :style="contentBodyStyle">
            <component
                v-bind:is="component"
                :show-headers="showHeaders"
                :show-labels="showLabels"
                v-bind:content.sync="realContent"
                :uniqueId="uniqueId"
                :type="type"
                :freshComponent="freshComponent"
                :config="config"
                v-on="$listeners"
                :ancestorSettings="ancestorSettings"
                :storePath="storePath"
            />
        </div>
    </div>
</template>

<script>
    import GeneralMixin from './mixins/GeneralMixin'
    import { getComponentByName, processSettingsConfig, getComponentSetting } from '~/utils/helpers.js'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        props: {
            containerClass: {type: String, default: 'content-block'},
            overlayed: {type: Boolean, default: false}
        },
        data() {
            return {
                component: undefined,
                realContent: this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId) || undefined,
            }
        },
        computed: {
            contentBodyStyle() {
                let styleObj = {
                    height: '100%',
                    width: '100%',
                }
                return styleObj
            },
            blockStyle() {
                let width = this.settings.width ? this.settings.width : ''
                width = (this.type == 'Image') ? '' : width

                let styleObj = {
                    display: 'block',
                    margin: this.settings.margin,
                    width: width,
                }
                return styleObj
            },
            containerClassList() {
                return [
                    this.containerClass,
                    this.containerClass + `_${this.type.toLowerCase()}`,
                    this.overlayed ? 'overlayed' : ''
                ]
            },
            blockType() {
                return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
            },
            settings() {
                return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
            },
            content() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            },
            showFooter() {
                return getComponentSetting(this.type, 'showFooter')
            },
            topToolbar() {
                return getComponentSetting(this.type, 'topToolbar') || []
            }
        },
        watch: {
            settings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
            realContent: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        methods: {
            ...mapActions({
                updateItemSettings(dispatch, payload) {
                    return dispatch(`${this.storePath}/updateItemSettings`, payload)
                },
                updateItemContent(dispatch, payload) {
                    return dispatch(`${this.storePath}/updateItemContent`, payload)
                },
                addItem(dispatch, payload) {
                    return dispatch(`${this.storePath}/addItem`, payload)
                },
                addTemplateItem(dispatch, payload) {
                    return dispatch(`${this.storePath}/addTemplateItem`, payload)
                },
                updateItemsList(dispatch, payload) {
                    return dispatch(`${this.storePath}/updateItemsList`, payload)
                },
                moveItemUp(dispatch, payload) {
                    return dispatch(`${this.storePath}/moveItemUp`, payload)
                },
                moveItemDown(dispatch, payload) {
                    return dispatch(`${this.storePath}/moveItemDown`, payload)
                }
            }),

            removeBlock() {
                this.$emit('remove', this.uniqueId)
            },
            moveUp() {
                this.moveItemUp({id: this.uniqueId})
            },
            moveDown() {
                this.moveItemDown({id: this.uniqueId})
            },
            addBlock(compName = undefined) {
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
            openComponentsLibModal() {
                let params = {
                    mode: 'standard',
                    componentName: this.type,
                    cb: (data = {}) => {
                        let {type, identifier} = data
                        switch (type) {
                            case 'block':
                                this.addBlock(identifier)
                                break;
                            case 'template':
                                this.addTemplateItem({ id: identifier, parentId: this.uniqueId })
                                break;
                        }
                    }
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            saveAsTemplate() {
                let params = {
                    id: this.uniqueId
                }

                this.$bus.$emit('open-template-form-modal', params)
            }
        },
        created() {
            let comp = getComponentByName(this.type)
            if (!comp) {
                console.log('Wrong component\'s name')
                return false
            }
            this.component = comp
        }
    }
</script>

<style lang="scss">
    .content-block {
        position: relative;
        &.overlayed::after {
            content: "";
            display: block;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: rgba(200,200,200,.01);
        }
    }
</style>
