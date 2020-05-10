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

                <div class="form-group">
                    <label>Allow Fullscreen</label>
                    <select class="form-control" v-model="settings.allowFullscreen">
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Allow Autoplay</label>
                    <select class="form-control" v-model="settings.autoplay">
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

                <div class="form-group">
                    <label>Custom Class</label>
                    <input type="text" class="form-control" v-model="settings.customClass">
                    <small>Add any custom classes to target this block.</small>
                </div>
            </el-tab-pane>
            <el-tab-pane label="Actions" name="actions">
                <div class="form-group">
                    <label>Link</label>
                    <input type="text" class="form-control" v-model="content">
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
            blockTitle: {type: String, default: 'Vimeo'},
            renderTitle: {type: Boolean, default: false},
            allowFullscreen: {type: Boolean, default: false},
            autoplay: {type: Boolean, default: false},
            customClass: {type: String, default: ''},

            position: {type: String, default: 'center'},

            flexResponsive: {type: Boolean, default: false},
            flex: {type: String, default: '0 1 auto'},
            flexLarge: {type: String, default: ''},
            flexMedium: {type: String, default: ''},
            flexSmall: {type: String, default: ''},
            flexExtraSmall: {type: String, default: ''},

            widthResponsive: {type: Boolean, default: false},
            width: {type: String, default: '100%'},
            widthLarge: {type: String, default: ''},
            widthMedium: {type: String, default: ''},
            widthSmall: {type: String, default: ''},
            widthExtraSmall: {type: String, default: ''},

            heightResponsive: {type: Boolean, default: false},
            height: {type: String, default: '450px'},
            heightLarge: {type: String, default: ''},
            heightMedium: {type: String, default: ''},
            heightSmall: {type: String, default: ''},
            heightExtraSmall: {type: String, default: ''},

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

            css: {type: String, default: ''},
        }
    }
</script>
