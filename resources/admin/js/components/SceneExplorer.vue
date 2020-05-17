<template>
    <v-modal ref="sceneModal"
        name="scene-modal"
        :classes="['v--modal', 'modal-content', 'modal-content_scene-modal']"
        :overlay="false"
        transition="nice-modal-fade"
        :clickToClose="false"
        :width="400"
        :height="500"
        :min-width="250"
        :min-height="300"
        :docked-width="300"
        :delay="100"
        :adaptive="false"
        :resizable="true"
        :draggable="'.modal-header'">

        <div class="modal-header grabbable">
            <i class="toggle-mode-btn lo-icon-cancel" style="margin:0;" @click="$modal.hide('scene-modal')"></i>
            <h4 class="modal-title animated">{{ modalTitle }}</h4>
        </div>

        <!-- <div class="save-as-template">
            <button type="button" class="btn btn-primary" @click="saveAsTemplate">Save as Template</button>
        </div> -->

        <VuePerfectScrollbar class="modal-body" style="padding-top 5px; !important">
            <div v-show="mode=='scene-explorer-all'">
                <draggable
                    class="components-tree"
                    v-model="containerList"
                    group="components-tree-root"
                    handle=".lo-icon-move"
                    chosenClass="dragging1">
                    <components-tree v-for="container in containerList" style="margin-bottom: 10px;"
                        :storePath="storePath"
                        :uniqueId="container.uniqueId"
                        :title="container.settings.blockTitle"
                        :settings="container.settings"
                        :component="container"
                        :componentType="container.type"
                        :depth="0"
                        :key="container.uniqueId">
                    </components-tree>
                </draggable>
            </div>

            <div v-if="mode=='scene-explorer-single'">
                <components-tree style="margin-bottom: 10px;"
                    :storePath="storePath"
                    :uniqueId="singleParentComponent.uniqueId"
                    :title="singleParentComponent.settings.blockTitle"
                    :settings="singleParentComponent.settings"
                    :component="singleParentComponent"
                    :componentType="singleParentComponent.type"
                    :initShowChildren="true"
                    :depth="0"
                    :key="singleParentComponent.uniqueId">
                </components-tree>
            </div>
            <!-- ++1 = {{ ancestorSettings }} -->
            <component v-if="(mode=='show-component-settings' || mode=='open-settings-modal') && uniqueId && settingsComponent"
                v-bind:is="settingsComponent"
                :storePath="storePath"
                :uniqueId="uniqueId"
                :key="uniqueId"
                :ancestorSettings="ancestorSettings"
                @self-cancel="cancelCurrentSettings">
            </component>
        </VuePerfectScrollbar>

        <div class="modal-footer">
            <button v-if="mode=='show-component-settings' && prevMode=='scene-explorer-single'" type="button" class="btn btn-primary pull-left" @click="goBackToComponent">Back</button>
            <button v-else-if="mode=='show-component-settings'" type="button" class="btn btn-primary pull-left" @click="goBackToScene">Scene</button>
            <button v-else type="button" class="btn btn-primary pull-left" @click="goBackToScene">Scene</button>
            <button type="button" class="btn btn-primary" @click="closeModal">Close</button>
        </div>
    </v-modal>
</template>

<script>
    import draggable from 'vuedraggable'
    import VuePerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'

    import { mapGetters, mapActions } from 'vuex'
    import { processSettings, processSettingsConfig, getComponentByName } from '~/utils/helpers.js'

    export default {
        components: {
            draggable,
            VuePerfectScrollbar
        },
        data() {
            return {
                mode: 'scene-explorer-all',
                storePath: undefined,
                prevMode: undefined,
                // explore-all, explore-single, settings
                uniqueId: 0,
                ancestorSettings: {},
                settingsComponent: undefined,
                singleParentComponent: undefined,
            }
        },
        computed: {
            modalTitle: {
                get() {
                    if(this.mode=='scene-explorer-all' || this.mode=='scene-explorer-single')
                        return 'Navigator'
                    else
                        return 'Settings'
                }
            },
            containerList: {
                get() {
                    return this.$store.getters[`${this.storePath}/rootItems`]
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId')})
                }
            },
            blocks: {
                get() {
                    return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            },
            block: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemBlockType`](this.uniqueId)
                }
            }
        },
        methods: {
            ...mapActions({
                addItem(dispatch, payload) {
                    return dispatch(`${this.storePath}/addItem`, payload)
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
                }
            }),
            addContainer(compName = 'Container') {
                let comp = getComponentByName(compName)

                if(!comp) {
                    // console.log('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    title: customSettings.blockTitle.default,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined)? null : object.default)
                })
            },
            saveAsTemplate() {
                let params = {
                    id: 0
                }

                this.$bus.$emit('open-template-form-modal', params)
            },
            closeModal() {
                this.$modal.hide('scene-modal')
                this.uniqueId = null
            },
            goBackToScene() {
                this.mode = 'scene-explorer-all'
                this.uniqueId = null
            },
            goBackToComponent() {
                this.mode = 'scene-explorer-single'
                this.uniqueId = null
            },
            cancelCurrentSettings() {
                this.goBackToScene()
            }
        },
        mounted() {
            this.$bus.$on('open-scene-modal', (mode, params) => {
                this.storePath = params.storePath || 'content/editor'
                this.prevMode = null
                this.mode = mode
                this.$modal.show('scene-modal')
            })

            this.$bus.$on('scene-explorer-single', (mode, params) => {
                this.prevMode = this.mode
                this.mode = mode
                this.singleParentComponent = params.singleParentComponent
            })

            this.$bus.$on('open-settings-modal', (mode, params) => {
                this.storePath = params.storePath || 'content/editor'
                this.mode = mode
                this.$modal.show('scene-modal')
                this.uniqueId = params.uniqueId
                this.ancestorSettings = params.ancestorSettings || {}
                this.settingsComponent = getComponentByName(params.type, 'settings') || undefined
            })

            this.$bus.$on('show-component', (mode, params) => {
                this.mode = mode
                this.uniqueId = params.uniqueId
                this.settingsComponent = getComponentByName(params.componentType)
            })

            this.$bus.$on('show-component-settings', (mode, params) => {
                this.prevMode = this.mode
                this.mode = mode
                this.uniqueId = params.uniqueId
                this.ancestorSettings = params.ancestorSettings || {}
                this.settingsComponent = getComponentByName(params.componentType, 'settings') || undefined
            })

            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off([
                    'open-scene-modal',
                    'scene-explorer-single',
                    'open-settings-modal',
                    'show-component',
                    'show-component-settings'
                ])
            })
        }
    }
</script>

<style lang="scss">
    .save-as-template {
        display: block;
        width: 100%;
        padding: 5px 15px;

        .btn {
            width: 100%;
            padding: 2px 12px;
        }
    }

    .modal-content_scene-modal {
        .nav-tabs-custom {
            margin-bottom: 5px;
        }

        // select {
        //     float: left;
        //     width: 150px;
        //     margin-right: 5px;
        // }

        .selected {
            border: 2px solid #00c0ef;
            padding: 0px;
        }

        .modal-header .toggle-mode-btn {
            cursor: pointer;
            font-size: 16px;
            margin-right: 8px;
            margin-top: 1px;
            color: #b8c7ce !important;
            float: right;
        }

        .modal-body {
            overflow: hidden;
        }

        &.docked {
            width: 250px !important;
        }
    }
</style>
