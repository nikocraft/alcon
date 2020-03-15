<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Render Title</label>
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

                <div class="form-group">
                    <label>Headline Tag</label>
                    <select class="form-control" v-model="settings.headlineTag">
                        <option value='h1'>h1</option>
                        <option value='h2'>h2</option>
                        <option value='h3'>h3</option>
                        <option value='h4'>h4</option>
                        <option value='h5'>h5</option>
                        <option value='h6'>h6</option>
                        <option value='div'>div</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Font</label>
                    <select class="form-control" v-model="settings.fontFamily">
                        <option value='default'>Default</option>

                        <option value='Arial'>Arial</option>
                        <option value='Helvetica'>Helvetica</option>
                        <option value='Times New Roman'>Times New Roman</option>
                        <option value='Times'>Times</option>
                        <option value='Courier New'>Courier New</option>
                        <option value='Courier'>Courier</option>
                        <option value='Verdana'>Verdana</option>
                        <option value='Georgia'>Georgia</option>
                        <!-- <option value='Palatino'>Palatino</option>
                        <option value='Garamond'>Garamond</option>
                        <option value='Bookman'>Bookman</option> -->
                        <option value='Comic Sans Ms'>Comic Sans MS</option>
                        <option value='Trebuchet Ms'>Trebuchet MS</option>
                        <option value='Arial Black'>Arial Black</option>
                        <option value='Impact'>Impact</option>
                    </select>
                </div>

                <div v-if="settings.headlineTag=='div'">
                    <div v-if="!settings.fontSizeResponsive" class="form-group">
                        <label>Font Size</label> <i @click="settings.fontSizeResponsive = !settings.fontSizeResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <px :maxAllowed=1 :px.sync="settings.fontSize"></px>
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
                        <px :maxAllowed=1 :px.sync="settings.fontLineHeight"></px>
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
                </div>
                <div class="form-group">
                    <label>Weight</label>
                    <select class="form-control" v-model="settings.fontWeight">
                        <option value='default'>Default</option>
                        <option value='100'>Light</option>
                        <option value='400'>Regular</option>
                        <option value='700'>Bold</option>
                    </select>
                </div>

                <!-- <div v-if="!settings.displayResponsive" class="form-group">
                    <label>Display</label>
                    <select class="form-control" v-model="settings.display">
                        <option value="block">Block</option>
                        <option value="inline-block">Inline Block</option>
                        <option value="none">None</option>
                    </select>
                </div> -->
                <!-- <div v-else class="form-group">
                    <label>Display</label> <i @click="settings.displayResponsive = !settings.displayResponsive" class="lo-icon lo-icon-desktop" title="Responsive"></i>

                </div> -->

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
                        :focus.sync="settings.textColorfocus">
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
                        :focus.sync="settings.backgroundColorfocus">
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
                    <label>Link</label>
                    <input type="text" class="form-control" v-model="settings.link">
                </div>

                <div v-if="settings.onClick=='open-link'" class="form-group">
                    <label>Open Link In</label>
                    <select class="form-control" v-model="settings.target">
                        <option value='_blank'>New Tab</option>
                        <option value='_self'>Same Tab</option>
                    </select>
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
            blockTitle: {type: String, default: 'Headline'},
            renderTitle: {type: Boolean, default: false},
            headlineTag: {type: String, default: 'h2'},
            customClass: {type: String, default: ''},
            fontFamily: {type: String, default: ''},

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

            fontWeight: {type: String, default: ''},

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
            flexLarge: {type: String, default: ''},
            flexMedium: {type: String, default: ''},
            flexSmall: {type: String, default: ''},
            flexExtraSmall: {type: String, default: ''},

            widthResponsive: {type: Boolean, default: false},
            width: {type: String, default: 'auto'},
            widthLarge: {type: String, default: ''},
            widthMedium: {type: String, default: ''},
            widthSmall: {type: String, default: ''},
            widthExtraSmall: {type: String, default: ''},

            alignSelfResponsive: {type: Boolean, default: false},
            alignSelf: {type: String, default: 'auto'},
            alignSelfLarge: {type: String, default: ''},
            alignSelfMedium: {type: String, default: ''},
            alignSelfSmall: {type: String, default: ''},
            alignSelfExtraSmall: {type: String, default: ''},

            displayResponsive: {type: Boolean, default: false},
            display: {type: String, default: 'block'},
            displayLarge: {type: String, default: ''},
            displayMedium: {type: String, default: ''},
            displaySmall: {type: String, default: ''},
            displayExtraSmall: {type: String, default: ''},

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
            link: {type: String, default: 'http://www.laraone.app'},
            target: {type: String, default: '_blank'},

            css: {type: String, default: ''},
        }
    }
</script>
