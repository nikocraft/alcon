<template>
    <div class="tree-menu" :style="indent">
        <div class="content-block-header" style="margin-bottom: 4px; border-radius: 4px;">
            <i v-if="nodes.length" @click="toggleChildren" class="lo-icon" :class="iconClasses"></i>

            <span @click="focusComponent()" style="cursor: pointer;">
                {{ title }}
            </span>

            <div class="pull-right">
                <template v-if="topToolbar.includes('addComponents')">
                    <i @click="openComponentsLibModal" class="lo-icon lo-icon-plus" title="Add Component"></i>
                </template>
                <i v-if="component != 'ColumnBlock'" class="lo-icon lo-icon-move" title="Move"></i>
                <i @click="moveUp" class="lo-icon lo-icon-angle-circled-up"></i>
                <i @click="moveDown" class="lo-icon lo-icon-angle-circled-down"></i>
                <i class="lo-icon lo-icon-cog" @click="showSettings()" title="Settings"></i>
                <i class="lo-icon lo-icon-trash-empty" @click="deleteComponent()" title="Delete"></i>
            </div>
        </div>
        <draggable
            v-model="nodes"
            :group="'components-tree-'+((componentType.toLowerCase()=='columns')?uniqueId:depth)"
            handle=".lo-icon-move"
            chosenClass="dragging1">
            <component-tree
                v-show="showChildren"
                v-for="node in nodes"
                :component="node"
                :componentType="node.type"
                :parent="uniqueId"
                :uniqueId="node.uniqueId"
                :title="node.settings.blockTitle"
                :depth="depth + 1"
                :key="node.uniqueId"
                :settings="node.settings"
                :ancestorSettings="propagatedSettings"
                :storePath="storePath">
            </component-tree>
        </draggable>
    </div>
</template>
<script>
    import draggable from 'vuedraggable'
    import { getComponentByName, processSettingsConfig, getComponentSetting } from '~/utils/helpers.js'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        components: {
            draggable
        },
        props: {
            title: {},
            component: {},
            initShowChildren: {},
            componentType: {},
            uniqueId: {},
            parent: {},
            depth: {},
            settings: {},
            ancestorSettings: {},
            storePath: {type: String, default: 'content/editor'},
        },
        name: 'component-tree',
        data() {
            return { showChildren: this.initShowChildren || false }
        },
        computed: {
            topToolbar() {
                return getComponentSetting(this.componentType, 'topToolbar') || []
            },
            nodes: {
                get() {
                    return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            },
            indent() {
                if(this.depth > 0)
                    return 'padding-left:' + '15px'
            },
            iconClasses() {
              return {
                'lo-icon-plus-squared': !this.showChildren,
                'lo-icon-minus-squared': this.showChildren
              }
            },
            propagatedSettingsList() {
                return _(processSettingsConfig(this.componentType))
                    .pickBy('childPropagation')
                    .keysIn()
                    .value()
            },
            propagatedSettings() {
                let settings = this.settings
                let propagatedSettingsList = this.propagatedSettingsList
                if (!settings) return undefined

                let output = {}
                _.forEach(propagatedSettingsList, k => {
                    output[k] = settings[k]
                })
                return output
            }
        },
        methods: {
            ...mapActions({
                addItem(dispatch, payload) {
                    return dispatch(`${this.storePath}/addItem`, payload)
                },
                addTemplateItem(dispatch, payload) {
                    return dispatch(`${this.storePath}/addTemplateItem`, payload)
                },
                removeItem(dispatch, payload) {
                    return dispatch(`${this.storePath}/removeItem`, payload)
                },
                setEditorMode(dispatch, payload) {
                    return dispatch(`${this.storePath}/setEditorMode`, payload)
                },
                setTemplateSlug(dispatch, payload) {
                    return dispatch(`${this.storePath}/setTemplateSlug`, payload)
                },
                setContentData(dispatch, payload) {
                    return dispatch(`${this.storePath}/setContentData`, payload)
                },
                updateItemsList(dispatch, payload) {
                    return dispatch(`${this.storePath}/updateItemsList`, payload)
                },
                fetchContentBlocks(dispatch, payload) {
                    return dispatch(`${this.storePath}/fetchContentBlocks`, payload)
                },
                moveItemUp(dispatch, payload) {
                    return dispatch(`${this.storePath}/moveItemUp`, payload)
                },
                moveItemDown(dispatch, payload) {
                    return dispatch(`${this.storePath}/moveItemDown`, payload)
                },
            }),
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
                    componentName: this.componentType,
                    cb: (data = {}) => {
                        let {type, identifier} = data
                        switch (type) {
                            case 'block':
                                this.addBlock(identifier)
                                break;
                            case 'template':
                                this.addTemplateItem({ id: identifier })
                                break;
                        }
                    }
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            showSettings() {
                let mode = 'show-component-settings'
                let params = {
                    uniqueId: this.uniqueId,
                    componentType: this.componentType,
                    ancestorSettings: this.ancestorSettings
                }
                this.$bus.$emit('show-component-settings', mode, params)
            },
            focusComponent() {
                let mode = 'scene-explorer-single'
                let params = {
                    uniqueId: this.uniqueId,
                    singleParentComponent: this.component
                }
                this.$bus.$emit('scene-explorer-single', mode, params)
            },
            deleteComponent() {
                this.removeItem({
                    id: this.uniqueId,
                    parentId: this.parent
                })
            },
            toggleChildren() {
                this.showChildren = !this.showChildren;
            }
        }
    }
</script>

<style scoped lang="scss">

    .content-block-header {
        padding: 7px;
        padding-left: 10px;
    }

</style>
