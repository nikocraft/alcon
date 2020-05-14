<template>
    <div slot="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>{{ trans('text.block_title') }}</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
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

                <div class="form-group">
                    <label>Color</label>
                    <color-picker v-model="settings.textColor"></color-picker>
                </div>

                <div class="form-group">
                    <label>Background Color</label>
                    <color-picker v-model="settings.backgroundColor"></color-picker>
                </div>

                <div class="form-group">
                    <label>Text Shadow</label>
                    <text-shadow v-model="settings.textShadow"></text-shadow>
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
            <!-- <el-tab-pane label="Actions" name="actions">
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
            </el-tab-pane> -->

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
            customClass: {type: String, default: ''},

            textColor: {type: String, default: ''},
            textShadow: {type: String, default: '0px 0px 0px transparent'},
            backgroundColor: {type: String, default: ''},

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
