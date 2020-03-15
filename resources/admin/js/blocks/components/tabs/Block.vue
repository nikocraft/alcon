<template>
    <draggable class="tabs-container" :style="tabsStyles"
        v-model="tabs"
        style="min-height: 120px;"
        :group="'tabs'+uniqueId"
        handle=".lo-icon-move"
        chosenClass="dragging1">
        <div class="tabsbar" :style="tabsBarStyles">
            <div v-for="tab in tabs"
                class="tabsbar-item"
                :class="{ 'active-tab-nav-item': (activeTab == tab.settings.blockRef) }"
                :style="navButtonStyles"
                @click="openTab(tab)">{{tab.settings.blockTitle}}
            </div>
        </div>

        <template v-for="tab in tabs">
            <component-wrapper
                :id="tab.settings.blockRef"
                :style="tabStyles"
                class="tab-item"
                :class="{ 'active-tab': (activeTab == tab.settings.blockRef) }"
                style="flex: 0.8;"
                :type="tab.type"
                :uniqueId="tab.uniqueId"
                :settings="tab.settings"
                :show-headers="showBlockHeaders"
                :show-labels="showBlockLabels"
                :allow-draggable="false"
                :container-preview="containerPreview"
                v-on:pick-block-modal="pickBlockModal"
                v-on:remove="removeTab(tab.uniqueId)"
                :storePath="storePath"
                :key="tab.uniqueId">
            </component-wrapper>
        </template>
    </draggable>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import ComponentWrapper from '../../ComponentWrapper'
    import { mapGetters, mapActions } from 'vuex'
    import draggable from 'vuedraggable'
    import { getComponentByName, processSettingsConfig } from '~/utils/helpers.js'

    export default {
        mixins: [GeneralMixin],
        components: {
            ComponentWrapper,
            draggable
        },
        data() {
            return {
                showBlockHeaders: this.showHeaders,
                showBlockLabels: this.showLabels,
                showPreview: true,
                activeTab: 'tab-1'
            }
        },
        computed: {
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
                }
            },
            tabsBarStyles() {
                let styleObj = {}
                styleObj = {
                    padding: '4px 4px 4px 0px',
                    order: (this.settings.tabNavPosition == 'right') ? '2' : '0',
                }
                return styleObj
            },
            navButtonStyles() {
                let styleObj = {}
                styleObj = {
                    padding: this.settings.tabNavItemPadding,
                    'background-color': this.settings.tabNavItemBackgroundColor,
                    color: this.settings.tabNavItemColor,
                    width: (this.settings.tabNavPosition != 'top') ? '100%' : 'unset',
                    'text-align': (this.settings.tabNavPosition == 'top') ? 'center' : 'unset',
                }
                return styleObj
            },
            tabsStyles() {
                let styleObj = {}
                styleObj = {
                    height: this.settings.height,
                    padding: this.settings.padding,
                    margin: this.settings.margin,
                    display: (this.settings.tabNavPosition != 'top') ? 'flex' : 'block',
                }
                return styleObj
            },
            tabStyles() {
                let styleObj = {}
                styleObj = {
                    height: "100%"
                }
                return styleObj
            },
            settings: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
                }
            },
            tabs: {
                get() {
                    return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            }
        },
        watch: {
            showHeaders(val, old) {
                this.showBlockHeaders = val
            },
            showLabels(val, old) {
                this.showBlockLabels = val
            },
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            tabs(val, old) {
                let isFresh = (!old.length && val.length)
                let isDecrease = (val.length < old.length)
                let newActiveIndex

                if(isDecrease) {
                    let diff = _.difference(old, val) || []
                    let diffElId = diff.length && diff[0]['uniqueId']
                    let indexToRemove = _.findIndex(old, {'uniqueId': diffElId})
                    newActiveIndex = (indexToRemove > 0)
                        ? indexToRemove - 1
                        : indexToRemove
                }

                if(isFresh) {
                    newActiveIndex = 0
                }

                if(_.isUndefined(newActiveIndex)) return

                let newActive = val[newActiveIndex]
                this.activeTab = newActive
                    ? newActive.settings.blockRef
                    : undefined
            }
        },
        methods: {
            visible(tab) {
                return this.activeTab == tab.settings.blockRef
            },
            openTab(tab) {
                this.activeTab = tab.settings.blockRef
            },
            pickBlockModal(columnId) {
                let params = {
                    cb: (data = {}) => {
                        let {type, identifier} = data
                        switch (type) {
                            case 'block':
                                this.$bus.$emit('column-add-block', columnId, identifier)
                                break;
                            case 'template':
                                //
                                break;
                        }
                    }
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            addTab() {
                let compName = 'Tab'
                let comp = getComponentByName(compName)
                if(!comp) {
                    console.error('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)
                let settings = _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                settings = {
                    ...settings,
                    ...{
                        blockTitle: `Tab ${this.tabs.length + 1}`,
                        blockRef: `tab-${this.tabs.length + 1}`
                    }
                }

                this.addItem({
                    type: compName,
                    title: 'Tab',
                    parentId: this.uniqueId,
                    settingsConfig: customSettings,
                    settings
                })
            },
            removeTabs() {
                this.$emit('remove')
            },
            removeTab(blockId) {
                // let indexToRemove = _.findIndex(this.tabs, {'uniqueId': blockId})
                // let newActiveIndex = (indexToRemove > 0)
                //     ? indexToRemove - 1
                //     : indexToRemove

                this.removeItem({
                    id: blockId,
                    parentId: this.uniqueId
                }).then(response => {
                    // let newActive = this.tabs[newActiveIndex]
                    // this.activeTab = newActive
                    //     ? newActive.settings.blockRef
                    //     : undefined
                })
            },
        },
        mounted() {
            if(this.tabs.length == 0) {
                this.addTab()
                this.addTab()
            }
        }
    }
</script>

<style scoped lang="scss">
    .border {
        border: 1px solid rgba(0,0,0, 0.15);
        padding: 10px;
        text-align: center;
        height: 62px;
    }

    .form-sub-group {
        padding: 8px 12px;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .columns-block-body {
    }

    .no-padding {
        padding: 0px;
    }

    .column-bkg {
        background: rgba(255,255,255, 0.01);
        padding: 10px;
        text-align: center;
        border: 1px solid  rgba(0,0,0, 0.15);
    }

    .column-bkg:not(:first-child) {
        border-left: 0;
    }

    .columns {
        margin-bottom: 15px;
    }

    .tabs-container {
        padding: 0;
        margin: 0;
        list-style: none;

        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: block;
        // border: 1px solid transparent;
        width: 100%;
        // -webkit-flex-flow: row wrap;
        // justify-content: space-around;
    }

    .tabsbar {
        width: 100%;
        overflow: hidden;
        flex: 0.2;
    }

    .tabsbar .tabsbar-item {
        float: left;
        outline: 0;
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: none;
        display: inline-block;
        padding: 8px 16px;
        vertical-align: middle;
        overflow: hidden;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        white-space: nowrap;
        background-color: transparent;
    }

    .tabsbar .tabsbar-item:hover {
        background-color: rgba(0,0,0, 0.2);
    }

    .tabsbar .tabsbar-item:active {
        background-color: rgba(0,0,0, 0.2);
    }

    .tabsbar .tabsbar-item:focus {
        background-color: rgba(0,0,0, 0.2);
    }

    .tab-item {
        background: rgba(255,255,255, 0.01);
        padding: 4px 0px;
        display: none;
    }

    .active-tab {
        display: block;
    }

    .active-tab-nav-item {
        color: lightblue;
        background-color: rgba(0,0,0, 0.2) !important;
        font-weight: bold;
    }
</style>
