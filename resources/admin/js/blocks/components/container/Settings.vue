<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Render Block Title</label>
                    <select class="form-control" v-model="settings.renderTitle">
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <div><select class="form-control" v-model="settings.containerType">
                        <option value='boxed'>Boxed</option>
                        <option value='fullwidth'>Fullwidth</option>
                    </select></div>
                </div>

                <div v-if="!settings.displayResponsive" class="form-group">
                    <label>Display</label> <i @click="settings.displayResponsive = !settings.displayResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <select class="form-control" v-model="settings.display">
                        <option v-for="option in optionsDisplay" :key="option.key" :value="option.key">{{ option.value }}</option>
                    </select>
                </div>

                <div v-else class="form-group">
                    <label>Display</label> <i @click="settings.displayResponsive = !settings.displayResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                    <select-responsive
                        :options="optionsDisplay"
                        :extraLarge.sync="settings.display"
                        :large.sync="settings.displayLarge"
                        :medium.sync="settings.displayMedium"
                        :small.sync="settings.displaySmall"
                        :extraSmall.sync="settings.displayExtraSmall">
                    </select-responsive>
                </div>

                <div v-if="settings.display =='flex'">
                    <div v-if="!settings.flexDirectionResponsive" class="form-group">
                        <label>Flex Direction</label> <i @click="settings.flexDirectionResponsive = !settings.flexDirectionResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.flexDirection">
                            <option v-for="option in optionsFlexDirection" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Flex Direction</label> <i @click="settings.flexDirectionResponsive = !settings.flexDirectionResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsFlexDirection"
                            :extraLarge.sync="settings.flexDirection"
                            :large.sync="settings.flexDirectionLarge"
                            :medium.sync="settings.flexDirectionMedium"
                            :small.sync="settings.flexDirectionSmall"
                            :extraSmall.sync="settings.flexDirectionExtraSmall">
                        </select-responsive>
                    </div>

                    <div v-if="!settings.flexWrapResponsive" class="form-group">
                        <label>Flex Wrap</label> <i @click="settings.flexWrapResponsive = !settings.flexWrapResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.flexWrap">
                            <option v-for="option in optionsFlexWrap" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Flex Wrap</label> <i @click="settings.flexWrapResponsive = !settings.flexWrapResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsFlexWrap"
                            :extraLarge.sync="settings.flexWrap"
                            :large.sync="settings.flexWrapLarge"
                            :medium.sync="settings.flexWrapMedium"
                            :small.sync="settings.flexWrapSmall"
                            :extraSmall.sync="settings.flexWrapExtraSmall">
                        </select-responsive>
                    </div>

                    <div v-if="!settings.justifyContentResponsive" class="form-group">
                        <label>Justify Content</label> <i @click="settings.justifyContentResponsive = !settings.justifyContentResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.justifyContent">
                            <option v-for="option in optionsJustifyContent" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Justify Content</label> <i @click="settings.justifyContentResponsive = !settings.justifyContentResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsJustifyContent"
                            :extraLarge.sync="settings.justifyContent"
                            :large.sync="settings.justifyContentLarge"
                            :medium.sync="settings.justifyContentMedium"
                            :small.sync="settings.justifyContentSmall"
                            :extraSmall.sync="settings.justifyContentExtraSmall">
                        </select-responsive>
                    </div>

                    <div v-if="!settings.alignItemsResponsive" class="form-group">
                        <label>Align Items</label> <i @click="settings.alignItemsResponsive = !settings.alignItemsResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.alignItems">
                            <option v-for="option in optionsAlignItems" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Align Items</label> <i @click="settings.alignItemsResponsive = !settings.alignItemsResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsAlignItems"
                            :extraLarge.sync="settings.alignItems"
                            :large.sync="settings.alignItemsLarge"
                            :medium.sync="settings.alignItemsMedium"
                            :small.sync="settings.alignItemsSmall"
                            :extraSmall.sync="settings.alignItemsExtraSmall">
                        </select-responsive>
                    </div>

                    <div v-if="!settings.alignContentResponsive" class="form-group">
                        <label>Align Content</label> <i @click="settings.alignContentResponsive = !settings.alignContentResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.alignContent">
                            <option v-for="option in optionsAlignContent" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Align Content</label> <i @click="settings.alignContentResponsive = !settings.alignContentResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsAlignContent"
                            :extraLarge.sync="settings.alignContent"
                            :large.sync="settings.alignContentLarge"
                            :medium.sync="settings.alignContentMedium"
                            :small.sync="settings.alignContentSmall"
                            :extraSmall.sync="settings.alignContentExtraSmall">
                        </select-responsive>
                        <small>This property has no effect when there is only one line of flex items.</small>
                    </div>
                </div>

                <div v-if="!settings.heightResponsive" class="form-group">
                    <label>Height</label> <i @click="settings.heightResponsive = !settings.heightResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px :px.sync="settings.height"></px>
                </div>
                <div v-else class="form-group">
                    <label>Height</label> <i @click="settings.heightResponsive = !settings.heightResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
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
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
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
                    <label>Margin</label> <i @click="settings.marginResponsive = !settings.marginResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                    <px-responsive
                        :extraLarge.sync="settings.margin"
                        :large.sync="settings.marginLarge"
                        :medium.sync="settings.marginMedium"
                        :small.sync="settings.marginSmall"
                        :extraSmall.sync="settings.marginExtraSmall">
                    </px-responsive>
                </div>

                <div v-if="!settings.backgroundColorAdvanced" class="form-group">
                    <label>Background Color</label> <i @click="settings.backgroundColorAdvanced = !settings.backgroundColorAdvanced" class="lo-icon lo-icon-plus-circled pull-right" title="Advanced"></i>
                    <color-picker v-model="settings.backgroundColor"></color-picker>
                </div>
                <div v-else class="form-group">
                    <label>Background Color</label> <i @click="settings.backgroundColorAdvanced = !settings.backgroundColorAdvanced" class="lo-icon lo-icon-minus-circled pull-right" title="Simple"></i>
                    <color
                        :standard.sync="settings.backgroundColor"
                        :hover.sync="settings.backgroundColorHover"
                        :active.sync="settings.backgroundColorActive">
                    </color>
                </div>

                <div class="form-group">
                    <label>Background Image</label>
                    <div class="input-group">
                        <input type="text" v-model="settings.backgroundImage" class="form-control"/>
                        <span @click="openMediaModal()" class="input-group-addon" style="cursor: pointer; border-left: 0;">Select</span>
                    </div>
                </div>

                <div v-if="settings.backgroundImage" class="form-group">
                    <label>Background Style</label>
                    <select class="form-control" v-model="settings.backgroundStyle">
                        <option value="scroll">Scroll</option>
                        <option value="fixed">Fixed</option>
                        <option value="local">Local</option>
                    </select>
                </div>

                <div v-if="settings.backgroundImage" class="form-group">
                    <label>Background Size</label>
                    <select class="form-control" v-model="settings.backgroundSize">
                        <option value="cover">Cover</option>
                        <option value="contain">Contain</option>
                    </select>
                </div>


                <div v-if="settings.backgroundImage" class="form-group">
                    <label>Background Position</label>
                    <select class="form-control" v-model="settings.backgroundPosition">
                        <option value="center">Center</option>
                        <option value="left">Left</option>
                        <option value="right">Right</option>
                        <option value="top">Top</option>
                        <option value="bototm">Bottom</option>
                    </select>
                </div>

                <div v-if="settings.backgroundImage" class="form-group">
                    <label>Background Repeat</label>
                    <select class="form-control" v-model="settings.backgroundRepeat">
                        <option value="no-repeat">No Repeat</option>
                        <option value="repeat">Repeat</option>
                        <option value="repeat-x">Repeat X</option>
                        <option value="repeat-y">Repeat Y</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Ref #</label>
                    <input type="text" class="form-control" v-model="settings.blockRef">
                </div>

                <div class="form-group">
                    <label>Custom Class</label>
                    <input type="text" class="form-control" v-model="settings.customClass">
                </div>
            </el-tab-pane>
            <el-tab-pane label="Actions" name="actions">
                <div class="form-group">
                    <label>On Click</label>
                    <select class="form-control" v-model="settings.onClick">
                        <option value='nothing'>Do Nothing</option>
                        <option value='open-link'>Open Link</option>
                    </select>
                </div>

                <div v-if="settings.onClick=='open-link'" class="form-group">
                    <label>Target</label>
                    <select class="form-control" v-model="settings.target">
                        <option value='_blank'>New Tab</option>
                        <option value='_self'>Same Tab</option>
                    </select>
                </div>

                <div v-if="settings.onClick=='open-link'" class="form-group">
                    <label>Link</label>
                    <input type="text" class="form-control" v-model="settings.link">
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

    export default {
        mixins: [SettingsMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Container'},
            renderTitle: {type: Boolean, default: false},
            blockRef: {type: String, default: ''},
            customClass: {type: String, default: ''},
            containerType: {type: String, default: 'boxed'},
            enablePreviewMode: {type: Boolean, default: true},
            showPreview: {type: Boolean, default: true},

            displayResponsive: {type: Boolean, default: false},
            display: {type: String, default: 'block', childPropagation: true},
            displayLarge: {type: String, default: '', childPropagation: true},
            displayMedium: {type: String, default: '', childPropagation: true},
            displaySmall: {type: String, default: '', childPropagation: true},
            displayExtraSmall: {type: String, default: '', childPropagation: true},

            flexDirectionResponsive: {type: Boolean, default: false},
            flexDirection: {type: String, default: 'column', childPropagation: true},
            flexDirectionLarge: {type: String, default: '', childPropagation: true},
            flexDirectionMedium: {type: String, default: '', childPropagation: true},
            flexDirectionSmall: {type: String, default: '', childPropagation: true},
            flexDirectionExtraSmall: {type: String, default: '', childPropagation: true},

            flexWrapResponsive: {type: Boolean, default: false},
            flexWrap: {type: String, default: 'nowrap'},
            flexWrapLarge: {type: String, default: ''},
            flexWrapMedium: {type: String, default: ''},
            flexWrapSmall: {type: String, default: ''},
            flexWrapExtraSmall: {type: String, default: ''},

            justifyContentResponsive: {type: Boolean, default: false},
            justifyContent: {type: String, default: 'center'},
            justifyContentLarge: {type: String, default: ''},
            justifyContentMedium: {type: String, default: ''},
            justifyContentSmall: {type: String, default: ''},
            justifyContentExtraSmall: {type: String, default: ''},

            alignItemsResponsive: {type: Boolean, default: false},
            alignItems: {type: String, default: 'center'},
            alignItemsLarge: {type: String, default: ''},
            alignItemsMedium: {type: String, default: ''},
            alignItemsSmall: {type: String, default: ''},
            alignItemsExtraSmall: {type: String, default: ''},

            alignContentResponsive: {type: Boolean, default: false},
            alignContent: {type: String, default: 'flex-start'},
            alignContentLarge: {type: String, default: ''},
            alignContentMedium: {type: String, default: ''},
            alignContentSmall: {type: String, default: ''},
            alignContentExtraSmall: {type: String, default: ''},

            minHeight: {type: String, default: '60px'},

            heightResponsive: {type: Boolean, default: false},
            height: {type: String, default: 'auto'},
            heightLarge: {type: String, default: ''},
            heightMedium: {type: String, default: ''},
            heightSmall: {type: String, default: ''},
            heightExtraSmall: {type: String, default: ''},

            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px 15px'},
            paddingLarge: {type: String, default: '0px 15px'},
            paddingMedium: {type: String, default: '0px 15px'},
            paddingSmall: {type: String, default: '0px 15px'},
            paddingExtraSmall: {type: String, default: '0px 15px'},

            marginResponsive: {type: Boolean, default: false},
            margin: {type: String, default: '0px auto'},
            marginLarge: {type: String, default: ''},
            marginMedium: {type: String, default: ''},
            marginSmall: {type: String, default: ''},
            marginExtraSmall: {type: String, default: ''},

            backgroundColorAdvanced: {type: Boolean, default: false},
            backgroundColor: {type: String, default: ''},
            backgroundColorHover: {type: String, default: ''},
            backgroundColorActive: {type: String, default: ''},
            backgroundColorFocus: {type: String, default: ''},

            backgroundImage: {type: String, default: ''},
            backgroundStyle: {type: String, default: 'scroll'},
            backgroundSize: {type: String, default: 'cover'},
            backgroundSizeManual: {type: String, default: '100%'},
            backgroundPosition: {type: String, default: 'center'},
            backgroundRepeat: {type: String, default: 'repeat'},

            onClick: {type: String, default: 'nothing'},
            link: {type: String, default: 'http://www.laraone.app'},
            target: {type: String, default: '_blank'},

            css: {type: String, default: ''},
        },
        methods: {
            openMediaModal() {
                var mode = 'insertImage'
                var params = new Object

                params.cb = (image) => {
                    this.settings.backgroundImage = '/' + image.path + image.filename + '.' + image.extension
                }

                this.$bus.$emit('open-media-modal', mode, params)
            }
        },
        watch: {
            'settings.containerType': {
                handler(val) {
                    if(val == 'fullwidth') {
                        this.settings.padding = '0px'
                        this.settings.paddingLarge = '0px'
                        this.settings.paddingMedium = '0px'
                        this.settings.paddingSmall = '0px'
                        this.settings.paddingExtraSmall = '0px'
                    }
                },
            }
        }
    }
</script>
