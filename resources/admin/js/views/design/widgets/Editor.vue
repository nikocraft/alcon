<template>
    <div class="content-editor">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header" style="display: flex; justify-content: space-between; width: 100%;">
                    <div style="flex: 1;">
                        <h3 class="box-title">Widgets Editor</h3>
                    </div>
                    <div style="flex: 1; display: flex; justify-content: flex-end; align-items: center;">
                        <button @click="saveTrigger" :disabled='saveBlocks' style="width: 65px; padding: 2px 10px;" class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label style="margin-right: 5px;">Title</label>
                        <input class="form-control" v-model="data.title"
                            v-validate="'required'"
                            :class="{'form-control':true, 'input': true, 'is-danger': errors.has('title') }"
                            name="title"
                            type="text"
                            placeholder="Enter Title">
                        <span v-show="errors.has('title')" class="help is-danger">{{ errors.first('title') }}</span>
                    </div>

                    <div class="form-group">
                        <label style="margin-right: 5px;">Widgets Area</label>
                        <select class="form-control" v-model="data.location">
                            <option v-for="widgetArea in widgetAreas" :value="widgetArea.key" :key="widgetArea.key">{{widgetArea.value}}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="margin-right: 5px;">Editor Layout</label>
                        <select class="form-control" v-model="data.layout">
                            <option value="horizontal">Horizontal</option>
                            <option value="vertical">Vertical</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="margin-right: 5px;">Visibility</label>
                        <select class="form-control" v-model="data.filterMode">
                            <option value="all">Everywhere</option>
                            <option value="selected">Selected</option>
                            <option value="exclude">Exclude</option>
                        </select>
                    </div>

                    <div v-if="data.filterMode == 'selected'" class="form-group">
                        <label style="margin-right: 5px;">Content Type</label>
                        <el-select v-model="data.contentType" @remove-tag="contentTypeCleared($event)" multiple placeholder="Select">
                            <el-option
                                v-for="item in contentTypes"
                                :key="item"
                                :label="item"
                                :value="item">
                            </el-option>
                        </el-select>
                    </div>

                    <div v-if="data.filterMode == 'selected' && data.contentType.includes('pages')" class="form-group">
                        <label style="margin-right: 5px;">Pages</label>
                        <el-select v-model="data.content.pages" multiple placeholder="Select">
                            <el-option
                                v-for="item in pages"
                                :key="item.id"
                                :label="item.title"
                                :value="item.id">
                            </el-option>
                        </el-select>
                    </div>

                    <el-tabs v-model="activeName">
                        <el-tab-pane label="Content" name="content">
                            <div :class="widgetContainerClass">
                                <div class="widgets_components_wrapper">
                                    <div style="display: flex; flex-direction: column; justify-content: space-between;">
                                        <div class="widget-component-buttons">
                                            <template v-if="favoriteComponents && favoriteComponents.length">
                                                <button v-for="comp in favoriteComponents" :key="comp.block" v-on:click="addBlock(comp.block)" type="button" name="button" class="btn btn-component btn-sm">
                                                    {{ comp.title }}
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="widgets_container_wrapper">
                                    <div class="widgets_container">
                                        <draggable
                                            style="self"
                                            :move="onMove"
                                            @start="onDraggingStart"
                                            @end="onDraggingEnd"
                                            v-model="containerList"
                                            handle=".lo-icon-move"
                                            chosenClass="dragging1">
                                            <component-wrapper-functional v-for="container in containerList"
                                                :type="container.type"
                                                :uniqueId="container.uniqueId"
                                                :settings="container.settings"
                                                :show-labels="showLabels"
                                                :show-headers="showHeaders"
                                                v-on:remove="removeContainer(container.uniqueId)"
                                                :key="container.uniqueId"
                                                :overlayed="dragging"
                                                store-path="widgets/editor">
                                            </component-wrapper-functional>
                                        </draggable>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </div>
        </div>

        <scene-explorer />
        <content-blocks-popup storeType="widgets" />
        <template-form storeType="widgets" />

    </div>
</template>
<script>
    import { mapState, mapGetters, mapActions } from 'vuex'
    import ComponentWrapperFunctional from '~/blocks/ComponentWrapperFunctional'
    import { processSettings, processSettingsConfig, getComponentByName, getComponentsList } from '~/utils/helpers.js'
    import draggable from 'vuedraggable'
    import ContentBlocksPopup from '~/components/ContentBlocksPopup'
    import TemplateForm from '~/components/TemplateForm'

    export default {
        components: {
            ContentBlocksPopup,
            TemplateForm,
            draggable,
            ComponentWrapperFunctional
        },
        data() {
            return {
                activeName: 'content',
                showHeaders: false,
                showLabels: true,
                showContent: true,
                saveBlocks: false,
                favoriteComponents: getComponentsList({
                    location: 'widgets'
                }),
                dragging: false,
                contentTypes: [
                    'pages',
                    'posts'
                ],
                pages: []
            }
        },
        props: {
            id: {type: [Number, String], default: null}
        },
        watch: {
            id(val, old) {
                if(val && !old) {
                    this.setId(val)
                } else {
                    this.init()
                }
            }
        },
        computed: {
            ...mapState('widgets/editor', [
                'allContentItems',
                'data',
                'widgetAreas'
            ]),
            ...mapGetters('widgets/editor', [
                'editorMode',
                'rootItems'
            ]),
            widgetContainerClass() {
                if(this.data.layout == 'horizontal')
                    return 'widgets_horizontal_wrapper'
                else
                    return 'widgets_vertical_wrapper'
            },
            containerList: {
                get() { return this.rootItems },
                set(object) { this.updateItemsList({list: _.map(object, 'uniqueId')}) }
            },
            saveBtnText() { return (this.saveBlocks) ? 'Saving' : 'Save' },
            modeText() { return _.capitalize(this.editorMode) },
        },
        methods: {
            ...mapActions('widgets/editor', [
                'resetState',
                'setId',
                'addItem',
                'addTemplateItem',
                'removeItem',
                'setEditorMode',
                'setWidgetData',
                'updateItemsList',
                'fetch',
                'fetchWidgetAreas',
                'saveAll'
            ]),
            contentTypeCleared(val) {
                delete this.data.content[val]
            },
            setLayout(layout) {
                this.layout.contentLayout = layout
            },
            openComponentsLibModal() {
                let params = {
                    componentName: this.type,
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
                    },
                    location: 'widgets'
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            addBlock(compName = 'Container') {
                let comp = getComponentByName(compName)

                if(!comp) {
                    console.log('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    title: customSettings.blockTitle.default,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            onDraggingStart(evt) {
                this.dragging = true
            },
            onDraggingEnd(evt) {
                this.dragging = false
            },
            onMove(evt) {
                return !evt.related.classList.contains('item_disabled')
            },
            saveTrigger() {
                // this.$validator.validateAll().then( result => {
                //     if (result) {
                        this.saveAll()
                //     }
                // })
            },
            removeContainer(containerId) {
                this.removeItem({
                    id: containerId
                })
            },
            getPages() {
                return axios.get(route('api.content.index', 1))
                    .then((response) => {
                        this.pages = response.data.data
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },
            init() {
                let id = this.id
                this.resetState()
                this.getPages()
                if(id) {
                    this.fetch({id})
                } else {
                    this.fetchWidgetAreas()
                }
            },
            setKeyShortcuts(event) {
                let key = event.keyCode
                if(event.ctrlKey) {
                    switch(key) {
                        // char L pressed - wide layout
                        case 76:
                            event.preventDefault()
                            this.wideLayout = !this.wideLayout
                        break;

                        // char H pressed
                        case 72:
                            event.preventDefault()
                            let shouldWeShowHeaders = !this.showHeaders

                            if(!this.showContent)
                                shouldWeShowHeaders = true

                            this.showHeaders = shouldWeShowHeaders
                        break;

                        // char E pressed, open scene explorer
                        case 69:
                            event.preventDefault()

                            let mode = 'scene-explorer-all'
                            let params = {
                                type: this.$options.name
                            }
                            this.$bus.$emit('open-scene-modal', mode, params)
                        break;

                        // char c pressed, open components explorer
                        // case 67:
                        //     event.preventDefault()
                        //     this.openComponentsLibModal()
                        // break;
                    }
                }
            }
        },
        created() {
            this.init()
            window.addEventListener('keydown', this.setKeyShortcuts)
            this.$once('hook:beforeDestroy', () => {
                window.removeEventListener('keydown', this.setKeyShortcuts)
            })
        }
    }
</script>

<style lang="scss">
    .widgets_vertical_wrapper {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }

    .widgets_horizontal_wrapper {
        display: flex;
        flex-direction: column;
    }

    .widgets_vertical_wrapper, .widgets_horizontal_wrapper{
        .widgets_components_wrapper {
            min-height: 200px;
            width: 70%;
            background-color: rgba(0, 0, 0, 0.08);
            order: 1;
        }

        .widgets_container_wrapper {
            display: flex;
            flex-direction: column;
            min-height: 120px;
            width: 25%;
            order: 2;
        }
    }

    .widgets_horizontal_wrapper {
        .widgets_container_wrapper {
            width: 100%;
            margin-bottom: 20px;
            order: 1;
        }

        .widgets_components_wrapper {
            width: 100%;
            order: 2;
        }
    }

    .widgets_container {
        width: 100%;
        padding: 0px 15px;
        background-color: rgb(31, 41, 46);
        min-height: 250px;
    }
</style>
