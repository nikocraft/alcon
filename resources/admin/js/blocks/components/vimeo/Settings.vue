<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
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
