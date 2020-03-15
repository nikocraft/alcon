<template>
    <div>
        <div :class="'col-md-9'">
            <div class="box box-primary">
                <div class="box-header" style="display: flex; justify-content: space-between; width: 100%;">
                    <div style="flex: 1;">
                        <h3 class="box-title">Menu Editor</h3>
                    </div>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label style="margin-right: 5px;">Name</label>
                        <input class="form-control" v-model="menuData.name"
                            v-validate="'required'"
                            :class="{'form-control':true, 'input': true, 'is-danger': errors.has('title') }"
                            name="title"
                            type="text"
                            placeholder="Enter Title">
                        <span v-show="errors.has('title')" class="help is-danger">{{ errors.first('title') }}</span>
                    </div>

                    <el-tabs v-model="activeName">
                        <el-tab-pane label="Menu Items" name="menu">
                            <div style="display: flex; flex-direction: column; justify-content: space-between; min-height: 120px;">
                                <dnd-wrapper :data="nestedItems" @change="((data) => updateFullList({data}))"></dnd-wrapper>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Options</h3>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label>Location:</label>
                        <select class="form-control" v-model="menuData.location">
                            <option v-for="(location, key) in locations" :key="key" :value="location.key">{{location.label}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Type:</label>
                        <el-collapse v-model="activeAccordion" accordion>
                            <el-collapse-item title="Pages" name="pages" class="pages">

                                <VuePerfectScrollbar class="form-group" style="max-height: 300px; padding: 7px; border: 1px solid rgba(0, 0, 0, 0.1);">
                                    <div v-for="page in pages" :key="page.id" class="page" @click="selectPage(page)" :style="selectedPageStyle(page)">
                                        {{ page.title }}
                                    </div>
                                </VuePerfectScrollbar>

                                <div class="form-group" style="display: flex; justify-content: flex-end;">
                                    <button @click="addPageItem()" class="btn btn-primary" type="submit" :disabled='!selectedPage'>Add</button>
                                </div>
                            </el-collapse-item>

                            <el-collapse-item title="Custom Url" name="custom">
                                <div class="form-group">
                                    <label>Label</label>
                                    <input type="text" class="form-control" v-model="customUrl.title">
                                </div>
                                <div class="form-group">
                                    <label>Url</label>
                                    <input type="text" class="form-control" v-model="customUrl.url">
                                </div>
                                <div class="form-group" style="display: flex; justify-content: flex-end;">
                                    <button @click="addCustomMenuItem()" class="btn btn-primary" type="submit" :disabled='customUrl.title ==""'>Add</button>
                                </div>
                            </el-collapse-item>
                        </el-collapse>
                    </div>
                </div>

                <div class="box-footer" style="display: flex; justify-content: flex-end;">
                    <button @click="saveMenu()" style="width: 70px;" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    import ComponentWrapperFunctional from './items/ComponentWrapperFunctional'
    import { processSettings, processSettingsConfig, getComponentByName } from './utils/helpers.js'
    import draggable from 'vuedraggable'
    import VuePerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'
    import DndWrapper from '~/components/ui/dnd-list/DndWrapper'

    export default {
        components: {
            draggable,
            VuePerfectScrollbar,
            ComponentWrapperFunctional,
            DndWrapper
        },
        data() {
            return {
                activeName: 'menu',
                activeAccordion: 'none',
                pages: [],
                locations: [],
                selectedPage: null,
                layout: 'default',
                customUrl: {
                    title: '',
                    url: ''
                }
            }
        },
        props: {
            id: {type: [Number, String], default: null}
        },
        watch: {
            id(val, old) {
                if(val && !old) {
                    this.setMenuData({id: val})
                } else {
                    this.init()
                }
            }
        },
        computed: {
            ...mapGetters('menus/editor', [
                'menuData',
                'rootItems'
            ]),
            nestedItems() {
                return this.$store.getters['menus/editor/nestedItems']()
            },
            menuItems: {
                get() {
                    return this.rootItems
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId')})
                }
            },
            saveMenuText() {
                if(this.saveBlocks)
                    return 'Saving'
                else {
                    return 'Save'
                }
            },
            modeText() {
                return this.id ? 'Edit' : 'Create'
            },
        },
        methods: {
            ...mapActions('menus/editor', [
                'resetState',
                'addItem',
                'addCustomItem',
                'removeItem',
                'setEditorMode',
                'setMenuData',
                'updateItemsList',
                'updateFullList',
                'fetchMenu',
                'saveAll'
            ]),
            selectedPageStyle(page) {
                if(this.selectedPage == page) {
                    return 'border: 2px solid rgba(82, 116, 141, 0.27);'
                }
            },
            setLayout(layout) {
                this.contentLayout = layout
            },
            selectPage(page) {
                this.selectedPage = page
            },
            addCustomMenuItem(compName = 'CustomUrlItem') {
                let comp = getComponentByName(compName)
                let customSettings = processSettingsConfig(compName)

                let content = {
                    title: this.customUrl.title,
                    url: this.customUrl.url
                }

                this.addItem({
                    type: compName,
                    content: content,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
                this.customUrl.title = ''
                this.customUrl.url = ''
            },
            addPageItem(compName = 'PageItem') {
                let comp = getComponentByName(compName)
                let customSettings = processSettingsConfig(compName)

                let content = {
                    title: this.selectedPage.title,
                    url: '/' + this.selectedPage.slug
                }

                this.addItem({
                    type: compName,
                    content: content,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })

                this.selectedPage = null
            },
            addMegaMenuItem(compName = 'MegaMenuItem') {
                let comp = getComponentByName(compName)
                let customSettings = processSettingsConfig(compName)
                this.addItem({
                    type: compName,
                    title: 'MegaMenu',
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            onEnd(evt) {
            },
            onMove(evt) {
                return !evt.related.classList.contains('item_disabled')
            },
            saveMenu() {
                this.$validator.validateAll().then( result => {
                    if(result) {
                        this.saveMenu()
                    }
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
            getLocations() {
                return axios.get(route('api.design.menus.locations'))
                    .then((response) => {
                        this.locations = response.data.data
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },
            saveMenu() {
                this.saveAll()
            },
            init() {
                this.resetState()
                this.id && this.fetchMenu({id: this.id})
            }
        },
        mounted() {
            this.getPages()
            this.getLocations()
            this.init()
        }
    }
</script>
