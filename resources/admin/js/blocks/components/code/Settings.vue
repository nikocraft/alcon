<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Language</label>
                    <select class="form-control" v-model="settings.devLanguage">
                        <option v-for="option in optionsDevLanguages" :key="option.key" :value="option.key">{{ option.value }}</option>
                    </select>
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
                optionsDevLanguages: [
                    {key: 'php', value: 'PHP'},
                    {key: 'javascript', value: 'JavaScript'},
                    {key: 'typescript', value: 'TypeScript'},
                    {key: 'java', value: 'Java'},
                    {key: 'csharp', value: 'C-Sharp'},
                    {key: 'go', value: 'Go'},
                    {key: 'jsx', value: 'React JSX'},
                    {key: 'tsx', value: 'React TSX'},
                    {key: 'html', value: 'HTML'},
                    {key: 'css', value: 'CSS'},
                    {key: 'sass', value: 'Sass'},
                    {key: 'scss', value: 'Scss'},
                    {key: 'less', value: 'Less'},
                    {key: 'html', value: 'XML'},
                    {key: 'html', value: 'SVG'},
                    {key: 'git', value: 'GIT'},
                    {key: 'json', value: 'JSON'},
                    {key: 'regex', value: 'Regex'},
                    {key: 'markdown', value: 'Markdown'},
                    {key: 'graphql', value: 'GraphQL'},
                    {key: 'sql', value: 'SQL'},
                    {key: 'bash', value: 'Bash'},
                    {key: 'docker', value: 'Docker'},
                    {key: 'yaml', value: 'Yaml'},
                    {key: 'nginx', value: 'Nginx'},
                ]
            }
        },
        customSettings: {
            blockTitle: {type: String, default: 'Code'},
            customClass: {type: String, default: ''},
            lineNumbers: {type: Boolean, default: true},
            devLanguage: {type: String, default: 'css'},

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
        }
    }
</script>
