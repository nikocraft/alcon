<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Ref</label>
                    <input type="text" class="form-control" v-model="settings.blockRef">
                </div>

                <div v-if="!settings.heightResponsive" class="form-group">
                    <label>Height</label> <i @click="settings.heightResponsive = !settings.heightResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px :px.sync="settings.height"></px>
                </div>
                <div v-else class="form-group">
                    <label>Height</label> <i @click="settings.heightResponsive = !settings.heightResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px-responsive
                        :extraLarge.sync="settings.height"
                        :large.sync="settings.heightLarge"
                        :medium.sync="settings.heightMedium"
                        :small.sync="settings.heightSmall"
                        :extraSmall.sync="settings.heightExtraSmall">
                    </px-responsive>
                </div>

                <div v-if="!settings.paddingResponsive" class="form-group">
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px :px.sync="settings.padding"></px>
                </div>
                <div v-else class="form-group">
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px-responsive
                        :extraLarge.sync="settings.padding"
                        :large.sync="settings.paddingLarge"
                        :medium.sync="settings.paddingMedium"
                        :small.sync="settings.paddingSmall"
                        :extraSmall.sync="settings.paddingExtraSmall">
                    </px-responsive>
                </div>

                <div v-if="!settings.marginResponsive" class="form-group">
                    <label>Margin</label> <i @click="settings.marginResponsive = !settings.marginResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px :px.sync="settings.margin"></px>
                </div>
                <div v-else class="form-group">
                    <label>Margin</label> <i @click="settings.marginResponsive = !settings.marginResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px-responsive
                        :extraLarge.sync="settings.margin"
                        :large.sync="settings.marginLarge"
                        :medium.sync="settings.marginMedium"
                        :small.sync="settings.marginSmall"
                        :extraSmall.sync="settings.marginExtraSmall">
                    </px-responsive>
                </div>

                <div class="form-group">
                    <label>Custom Class</label>
                    <input type="text" class="form-control" v-model="settings.customClass">
                    <small>Add any custom classes to target this block.</small>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Navigation" name="navigation">
                <div class="form-group">
                    <label>Position</label>
                    <select class="form-control" v-model="settings.tabNavPosition">
                        <option value="top">Top</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                    </select>
                </div>

                <div v-if="!settings.tabNavItemPaddingResponsive" class="form-group">
                    <label>Item Padding</label> <i @click="settings.tabNavItemPaddingResponsive = !settings.tabNavItemPaddingResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px :px.sync="settings.tabNavItemPadding"></px>
                </div>
                <div v-else class="form-group">
                    <label>Item Padding</label> <i @click="settings.tabNavItemPaddingResponsive = !settings.tabNavItemPaddingResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px-responsive
                        :extraLarge.sync="settings.tabNavItemPadding"
                        :large.sync="settings.tabNavItemPaddingLarge"
                        :medium.sync="settings.tabNavItemPaddingMedium"
                        :small.sync="settings.tabNavItemPaddingSmall"
                        :extraSmall.sync="settings.tabNavItemPaddingExtraSmall"/>
                </div>

                <div v-if="!settings.tabNavMarginResponsive" class="form-group">
                    <label>Navbar Margin</label> <i @click="settings.tabNavMarginResponsive = !settings.tabNavMarginResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px :px.sync="settings.tabNavMargin"></px>
                </div>
                <div v-else class="form-group">
                    <label>Navbar Margin</label> <i @click="settings.tabNavMarginResponsive = !settings.tabNavMarginResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px-responsive
                        :extraLarge.sync="settings.tabNavMargin"
                        :large.sync="settings.tabNavMarginLarge"
                        :medium.sync="settings.tabNavMarginMedium"
                        :small.sync="settings.tabNavMarginSmall"
                        :extraSmall.sync="settings.tabNavMarginExtraSmall"/>
                </div>

                <div v-if="!settings.tabNavItemColorAdvanced" class="form-group">
                    <label>Item Text Color</label> <i @click="settings.tabNavItemColorAdvanced = !settings.tabNavItemColorAdvanced" class="lo-icon lo-icon-plus-circled pull-right" title="Advanced"></i>
                    <color-picker v-model="settings.tabNavItemColor"></color-picker>
                </div>
                <div v-else class="form-group">
                    <label>Item Text Color</label> <i @click="settings.tabNavItemColorAdvanced = !settings.tabNavItemColorAdvanced" class="lo-icon lo-icon-minus-circled pull-right" title="Simple"></i>
                    <color
                        :standard.sync="settings.tabNavItemColor"
                        :hover.sync="settings.tabNavItemColorHover"
                        :active.sync="settings.tabNavItemColorActive">
                    </color>
                </div>

                <div v-if="!settings.tabNavItemBackgroundColorAdvanced" class="form-group">
                    <label>Item Background Color</label> <i @click="settings.tabNavItemBackgroundColorAdvanced = !settings.tabNavItemBackgroundColorAdvanced" class="lo-icon lo-icon-plus-circled pull-right" title="Advanced"></i>
                    <color-picker v-model="settings.tabNavItemBackgroundColor"></color-picker>
                </div>
                <div v-else class="form-group">
                    <label>Item Background Color</label> <i @click="settings.tabNavItemBackgroundColorAdvanced = !settings.tabNavItemBackgroundColorAdvanced" class="lo-icon lo-icon-minus-circled pull-right" title="Simple"></i>
                    <color
                        :standard.sync="settings.tabNavItemBackgroundColor"
                        :hover.sync="settings.tabNavItemBackgroundColorHover"
                        :active.sync="settings.tabNavItemBackgroundColorActive">
                    </color>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Tabs" name="tabs">
                <div class="form-group">
                    <label>Tabs</label>
                    <div v-for="tab in tabs">
                        <div class="form-group" style="margin-bottom: 4px; padding: 5px; border: 1px solid rgba(0,0,0, 0.15);">
                            <span style="padding-left: 12px; font-weight: bold;">{{ tab.settings.blockTitle }}</span>
                            <span @click="removeTab(tab.uniqueId)" style="cursor: pointer;" class="pull-right">x</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="button" class="btn btn-primary" style="padding: 2px 12px;"@click="addTab">+</button>
                    </div>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Css" name="css">
                <div class="form-group">
                    <prism-editor v-if="!$root.isFirefox" v-model="settings.css" :lineNumbers=true language="css"></prism-editor>
                    <textarea v-else v-model="settings.css" class="form-control" rows="3"></textarea>
                </div>
            </el-tab-pane>
        </el-tabs>
    </div>

</template>

<script>
    import SettingsMixin from '../../mixins/SettingsMixin'
    import { mapGetters, mapActions } from 'vuex'
    import { getComponentByName, processSettingsConfig } from '~/utils/helpers.js'

    export default {
        mixins: [SettingsMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Tabs'},
            blockRef: {type: String, default: ''},
            tabNavPosition: {type: String, default: 'top'},
            customClass: {type: String, default: ''},

            heightResponsive: {type: Boolean, default: false},
            height: {type: String, default: 'auto'},
            heightLarge: {type: String, default: ''},
            heightMedium: {type: String, default: ''},
            heightSmall: {type: String, default: ''},
            heightExtraSmall: {type: String, default: ''},

            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px'},
            paddingLarge: {type: String, default: ''},
            paddingMedium: {type: String, default: ''},
            paddingSmall: {type: String, default: ''},
            paddingExtraSmall: {type: String, default: ''},

            marginResponsive: {type: Boolean, default: false},
            margin: {type: String, default: '0px auto'},
            marginLarge: {type: String, default: ''},
            marginMedium: {type: String, default: ''},
            marginSmall: {type: String, default: ''},
            marginExtraSmall: {type: String, default: ''},

            tabNavItemPaddingResponsive: {type: Boolean, default: false},
            tabNavItemPadding: {type: String, default: '8px 16px'},
            tabNavItemPaddingLarge: {type: String, default: ''},
            tabNavItemPaddingMedium: {type: String, default: ''},
            tabNavItemPaddingSmall: {type: String, default: ''},
            tabNavItemPaddingExtraSmall: {type: String, default: ''},

            tabNavMarginResponsive: {type: Boolean, default: false},
            tabNavMargin: {type: String, default: '0px'},
            tabNavMarginLarge: {type: String, default: ''},
            tabNavMarginMedium: {type: String, default: ''},
            tabNavMarginSmall: {type: String, default: ''},
            tabNavMarginExtraSmall: {type: String, default: ''},

            tabNavItemColorAdvanced: {type: Boolean, default: false},
            tabNavItemColor: {type: String, default: 'white'},
            tabNavItemColorHover: {type: String, default: 'white'},
            tabNavItemColorActive: {type: String, default: 'white'},
            tabNavItemColorFocus: {type: String, default: 'white'},

            tabNavItemBackgroundColorAdvanced: {type: Boolean, default: false},
            tabNavItemBackgroundColor: {type: String, default: 'transparent'},
            tabNavItemBackgroundColorHover: {type: String, default: 'transparent'},
            tabNavItemBackgroundColorActive: {type: String, default: 'transparent'},
            tabNavItemBackgroundColorFocus: {type: String, default: 'transparent'},

            css: {type: String, default: ''},
        },
        computed: {
            modalTitle() {
                return this.settings.blockTitle + ' Settings'
            },
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
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
        methods: {
            addTab() {
                let compName = 'Tab'
                let comp = getComponentByName(compName)
                if (!comp) {
                    console.error('Wrong component\'s name')
                    return false
                }

                let blockTitle = `Tab ${this.tabs.length + 1}`
                let blockRef = `tab-${this.tabs.length + 1}`

                if(_.findIndex(this.tabs, o => {return o.settings.blockTitle == blockTitle}) + 1) {
                    blockTitle = this.checkAsDuplicate(this.tabs, {
                            propName: 'blockTitle',
                            value: blockTitle,
                            template: _.template(' (<%= iterator %>)')
                        })
                }
                if(_.findIndex(this.tabs, o => {return o.settings.blockRef == blockRef}) + 1) {
                    blockRef = this.checkAsDuplicate(this.tabs, {
                            propName: 'blockRef',
                            value: blockRef,
                            template: _.template('_<%= iterator %>')
                        })
                }

                let customSettings = processSettingsConfig(compName)
                // customSettings.blockTitle.default = 'Tab' + ' ' + (this.tabs.length + 1)
                // customSettings.blockRef.default = 'tab' + '-' + (this.tabs.length + 1)
                let settings = _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                settings = {
                    ...settings,
                    ...{
                        blockTitle,
                        blockRef
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
            removeTab(blockId) {
                this.removeItem({
                    id: blockId,
                    parentId: this.uniqueId
                })
            },
            checkAsDuplicate(items = [], options) {
                let {propName, value, iterator = 1, template} = options
                let compiledTemplate = template({iterator})
                let newVal = `${value}${compiledTemplate}`
                if( _.findIndex(this.tabs, o => { return o.settings[propName] == newVal}) + 1 ) {
                    newVal = this.checkAsDuplicate(items, {
                        ...options,
                        iterator: iterator+1
                    })
                }
                return newVal
            }
        }
    }
</script>

<style scoped lang="scss">
    .column-container {
        padding: 0;
        margin: 0;
        list-style: none;

        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        border: 1px solid transparent;
        width: 100%;
        // -webkit-flex-flow: row wrap;
        // justify-content: space-around;
    }

    .column-selector-container {
        padding: 0;
        margin: 0;
        list-style: none;

        display: -webkit-box;
        display: -moz-box;
        display: -ms-flexbox;
        display: -webkit-flex;
        display: flex;
        padding: 2px 2px;
        border: 1px solid transparent;
        cursor: pointer;
        width: 33.3%;
    }

    .one-whole, .four-equal, .five-equal, .six-equal {
        width: 100%;
    }

    .selected-columns {
        border: 1px solid rgba(33, 144, 254, 0.4)
    }

    .column-item {
        background: rgba(255,255,255, 0.01);
        width: 50%;
        border: 1px solid  rgba(0,0,0, 0.15);
        margin: 0px;
        padding: 4px;
        font-size: 10px;
    }

    .column-item:first-child {
        margin-left: 0px !important;                       /*  on all but the first column  */
    }

    .column-item:last-child {
        margin-right: 0px !important;                      /*  on all but the last column  */
    }

    // .column-item:not(:first-child) {
    //     border-left: 0;
    // }
</style>
