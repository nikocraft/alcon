<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
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
                    <label>Font</label>
                    <select class="form-control" v-model="settings.fontFamily">
                        <option value=''>Default</option>
                        <option value='Arial'>Arial</option>
                        <option value='Helvetica'>Helvetica</option>
                        <option value='Times New Roman'>Times New Roman</option>
                        <option value='Times'>Times</option>
                        <option value='Courier New'>Courier New</option>
                        <option value='Courier'>Courier</option>
                        <option value='Verdana'>Verdana</option>
                        <option value='Georgia'>Georgia</option>
                        <option value='Comic Sans Ms'>Comic Sans MS</option>
                        <option value='Trebuchet Ms'>Trebuchet MS</option>
                        <option value='Arial Black'>Arial Black</option>
                        <option value='Impact'>Impact</option>
                    </select>
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

                <div class="form-group">
                    <label>Color</label>
                    <color-picker v-model="settings.textColor"></color-picker>
                </div>

                <div class="form-group">
                    <label>Background Color</label>
                    <color-picker v-model="settings.backgroundColor"></color-picker>
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

            fontWeight: {type: String, default: 'default'},
            textColor: {type: String, default: ''},
            backgroundColor: {type: String, default: ''},

            paddingResponsive: {type: Boolean, default: false},
            padding: {type: String, default: '0px'},
            paddingLarge: {type: String, default: '0px'},
            paddingMedium: {type: String, default: '0px'},
            paddingSmall: {type: String, default: '0px'},
            paddingExtraSmall: {type: String, default: '0px'},

            marginResponsive: {type: Boolean, default: false},
            margin: {type: String, default: '0px 0px 15px 0px'},
            marginLarge: {type: String, default: ''},
            marginMedium: {type: String, default: ''},
            marginSmall: {type: String, default: ''},
            marginExtraSmall: {type: String, default: ''},

            css: {type: String, default: ''},
        }
    }
</script>
