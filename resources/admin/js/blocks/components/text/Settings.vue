<template>
    <div slot="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>{{ trans('text.block_title') }}</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Render Block Title</label>
                    <select class="form-control" v-model="settings.renderTitle">
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                </div>

                <div v-if="ancestorSettings.display == 'flex'">
                    <div v-if="!settings.flexResponsive" class="form-group">
                        <label>Flex</label> <i @click="settings.flexResponsive = !settings.flexResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <input type="text" v-model="settings.flex" class="form-control">
                    </div>
                    <div v-if="settings.flexResponsive" class="form-group">
                        <label>Flex</label> <i @click="settings.flexResponsive = !settings.flexResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <px-responsive
                            :extraLarge.sync="settings.flex"
                            :large.sync="settings.flexLarge"
                            :medium.sync="settings.flexMedium"
                            :small.sync="settings.flexSmall"
                            :extraSmall.sync="settings.flexExtraSmall">
                        </px-responsive>
                    </div>

                    <div v-if="!settings.alignSelfResponsive" class="form-group">
                        <label>Align Self</label> <i @click="settings.alignSelfResponsive = !settings.alignSelfResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.alignSelf">
                            <option v-for="option in optionsAlignSelf" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Align Self</label> <i @click="settings.alignSelfResponsive = !settings.alignSelfResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select-responsive
                            :options="optionsAlignSelf"
                            :extraLarge.sync="settings.alignSelf"
                            :large.sync="settings.alignSelfLarge"
                            :medium.sync="settings.alignSelfMedium"
                            :small.sync="settings.alignSelfSmall"
                            :extraSmall.sync="settings.alignSelfExtraSmall">
                        </select-responsive>
                    </div>

                    <div v-if="!settings.widthResponsive" class="form-group">
                        <label>Width</label> <i @click="settings.widthResponsive = !settings.widthResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <px :maxAllowed=1 :px.sync="settings.width"></px>
                    </div>
                    <div v-if="settings.widthResponsive" class="form-group">
                        <label>Width</label> <i @click="settings.widthResponsive = !settings.widthResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <px-responsive
                            :extraLarge.sync="settings.width"
                            :large.sync="settings.widthLarge"
                            :medium.sync="settings.widthMedium"
                            :small.sync="settings.widthSmall"
                            :extraSmall.sync="settings.widthExtraSmall">
                        </px-responsive>
                    </div>
                </div>

                <div v-if="!settings.fontSizeResponsive" class="form-group">
                    <label>Font Size</label> <i @click="settings.fontSizeResponsive = !settings.fontSizeResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px :px.sync="settings.fontSize"></px>
                </div>

                <div v-if="settings.fontSizeResponsive" class="form-group">
                    <label>Font Size</label> <i @click="settings.fontSizeResponsive = !settings.fontSizeResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                    <px-responsive
                        :extraLarge.sync="settings.fontSize"
                        :large.sync="settings.fontSizeLarge"
                        :medium.sync="settings.fontSizeMedium"
                        :small.sync="settings.fontSizeSmall"
                        :extraSmall.sync="settings.fontSizeExtraSmall">
                    </px-responsive>
                </div>

                <div v-if="!settings.fontLineHeightResponsive" class="form-group">
                    <label>Line Height</label> <i @click="settings.fontLineHeightResponsive = !settings.fontLineHeightResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px :px.sync="settings.fontLineHeight"></px>
                </div>

                <div v-if="settings.fontLineHeightResponsive" class="form-group">
                    <label>Line Height</label> <i @click="settings.fontLineHeightResponsive = !settings.fontLineHeightResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                    <px-responsive
                        :extraLarge.sync="settings.fontLineHeight"
                        :large.sync="settings.fontLineHeightLarge"
                        :medium.sync="settings.fontLineHeightMedium"
                        :small.sync="settings.fontLineHeightSmall"
                        :extraSmall.sync="settings.fontLineHeightExtraSmall">
                    </px-responsive>
                </div>

                <div v-if="!settings.textColorAdvanced" class="form-group">
                    <label>Color</label> <i @click="settings.textColorAdvanced = !settings.textColorAdvanced" class="lo-icon lo-icon-plus-circled pull-right" title="Advanced"></i>
                    <color-picker v-model="settings.textColor"></color-picker>
                </div>
                <div v-else class="form-group">
                    <label>Color</label> <i @click="settings.textColorAdvanced = !settings.textColorAdvanced" class="lo-icon lo-icon-minus-circled pull-right" title="Simple"></i>
                    <color
                        :standard.sync="settings.textColor"
                        :hover.sync="settings.textColorHover"
                        :active.sync="settings.textColorActive"
                        :focus.sync="settings.textColorFocus">
                    </color>
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
                        :active.sync="settings.backgroundColorActive"
                        :focus.sync="settings.backgroundColorFocus">
                    </color>
                </div>

                <div v-if="!settings.textShadowAdvanced" class="form-group">
                    <label>Text Shadow</label> <i @click="settings.textShadowAdvanced = !settings.textShadowAdvanced" class="lo-icon lo-icon-plus-circled pull-right" title="Advanced"></i>
                    <text-shadow v-model="settings.textShadow"></text-shadow>
                </div>
                <div v-else class="form-group">
                    <label>Text Shadow</label> <i @click="settings.textShadowAdvanced = !settings.textShadowAdvanced" class="lo-icon lo-icon-minus-circled pull-right" title="Simple"></i>
                    <text-shadow-advanced
                        :standard.sync="settings.textShadow"
                        :hover.sync="settings.textShadowHover"
                        :active.sync="settings.textShadowActive"
                        :focus.sync="settings.textShadowFocus">
                    </text-shadow-advanced>
                </div>

                <div v-if="!settings.paddingResponsive" class="form-group">
                    <label>{{ trans('text.padding') }}</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <px :px.sync="settings.padding"></px>
                </div>
                <div v-else class="form-group">
                    <label>{{ trans('text.padding') }}</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                    <px-responsive
                        :extraLarge.sync="settings.padding"
                        :large.sync="settings.paddingLarge"
                        :medium.sync="settings.paddingMedium"
                        :small.sync="settings.paddingSmall"
                        :extraSmall.sync="settings.paddingExtraSmall">
                    </px-responsive>
                </div>

                <div v-if="!settings.marginResponsive" class="form-group">
                    <label>{{ trans('text.margin') }}</label> <i @click="settings.marginResponsive = !settings.marginResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
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

                <div class="form-group">
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
        data() {
            return {
                activeTab: 'general'
            }
        },
        customSettings: {
            blockTitle: {type: String, default: 'Text'},
            renderTitle: {type: Boolean, default: false},
            customClass: {type: String, default: ''},

            textColorAdvanced: {type: Boolean, default: false},
            textColor: {type: String, default: ''},
            textColorHover: {type: String, default: ''},
            textColorActive: {type: String, default: ''},
            textColorFocus: {type: String, default: ''},

            textShadowAdvanced: {type: Boolean, default: false},
            textShadow: {type: String, default: '0px 0px 0px transparent'},
            textShadowHover: {type: String, default: '0px 0px 0px transparent'},
            textShadowActive: {type: String, default: '0px 0px 0px transparent'},
            textShadowFocus: {type: String, default: '0px 0px 0px transparent'},

            backgroundColorAdvanced: {type: Boolean, default: false},
            backgroundColor: {type: String, default: ''},
            backgroundColorHover: {type: String, default: ''},
            backgroundColorActive: {type: String, default: ''},
            backgroundColorFocus: {type: String, default: ''},

            flexResponsive: {type: Boolean, default: false},
            flex: {type: String, default: '0 1 auto'},
            flexLarge: {type: String, default: '0 1 auto'},
            flexMedium: {type: String, default: '0 1 auto'},
            flexSmall: {type: String, default: '0 1 auto'},
            flexExtraSmall: {type: String, default: '0 1 auto'},

            widthResponsive: {type: Boolean, default: false},
            width: {type: String, default: 'auto'},
            widthLarge: {type: String, default: 'auto'},
            widthMedium: {type: String, default: 'auto'},
            widthSmall: {type: String, default: 'auto'},
            widthExtraSmall: {type: String, default: 'auto'},

            alignSelfResponsive: {type: Boolean, default: false},
            alignSelf: {type: String, default: 'auto'},
            alignSelfLarge: {type: String, default: 'auto'},
            alignSelfMedium: {type: String, default: 'auto'},
            alignSelfSmall: {type: String, default: 'auto'},
            alignSelfExtraSmall: {type: String, default: 'auto'},

            displayResponsive: {type: Boolean, default: false},
            display: {type: String, default: 'block'},
            displayLarge: {type: String, default: 'block'},
            displayMedium: {type: String, default: 'block'},
            displaySmall: {type: String, default: 'block'},
            displayExtraSmall: {type: String, default: 'block'},

            fontSizeResponsive: {type: Boolean, default: false},
            fontSize: {type: String, default: ''},
            fontSizeLarge: {type: String, default: ''},
            fontSizeMedium: {type: String, default: ''},
            fontSizeSmall: {type: String, default: ''},
            fontSizeExtraSmall: {type: String, default: ''},

            fontLineHeightResponsive: {type: Boolean, default: false},
            fontLineHeight: {type: String, default: ''},
            fontLineHeightLarge: {type: String, default: ''},
            fontLineHeightMedium: {type: String, default: ''},
            fontLineHeightSmall: {type: String, default: ''},
            fontLineHeightExtraSmall: {type: String, default: ''},

            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px'},
            paddingLarge: {type: String, default: ''},
            paddingMedium: {type: String, default: ''},
            paddingSmall: {type: String, default: ''},
            paddingExtraSmall: {type: String, default: ''},

            marginResponsive: {type: Boolean, default: false},
            margin: {type: String, default: '0px 0px 15px 0px'},
            marginLarge: {type: String, default: ''},
            marginMedium: {type: String, default: ''},
            marginSmall: {type: String, default: ''},
            marginExtraSmall: {type: String, default: ''},

            onClick: {type: String, default: 'nothing'},
            link: {type: String, default: ''},
            target: {type: String, default: '_blank'},

            css: {type: String, default: ''},
        }
    }
</script>
