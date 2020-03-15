<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Block Title</label>
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
                    <label>Number Of Posts</label>
                    <input type="text" class="form-control" v-model="settings.numberOfPosts">
                </div>

                <div class="form-group">
                    <label>Posts Type</label>
                    <select class="form-control" v-model="settings.postsType">
                        <option v-for="option in postsTypes" :key="option.key" :value="option.value">{{ option.text }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Render Featured Image</label>
                    <select class="form-control" v-model="settings.renderFeaturedImage">
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                </div>

                <div v-if="settings.renderFeaturedImage" class="form-group">
                    <label>Featured Image Height</label>
                    <input type="text" class="form-control" v-model="settings.featuredImageHeight">
                </div>

                <div class="form-group">
                    <label>Title Font Size</label>
                    <input type="text" class="form-control" v-model="settings.titleFontSize">
                </div>

                <div class="form-group">
                    <label>Title Color</label>
                    <color-picker v-model="settings.titleColor"></color-picker>
                </div>

                <div class="form-group">
                    <label>Date Font Size</label>
                    <input type="text" class="form-control" v-model="settings.dateFontSize">
                </div>

                <div class="form-group">
                    <label>Date Color</label>
                    <color-picker v-model="settings.dateColor"></color-picker>
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

    export default {
        mixins: [SettingsMixin],
        data() {
            return {
                postsTypes: [
                    { text: 'Pages', value: '1' },
                    { text: 'Posts', value: '2' }
                ]
            }
        },
        customSettings: {
            blockTitle: {type: String, default: 'Recent Posts'},
            renderTitle: {type: Boolean, default: false},
            numberOfPosts: {type: String, default: '10'},
            postsType: {type: String, default: '2'},
            renderFeaturedImage: {type: Boolean, default: true},
            featuredImageHeight: {type: String, default: '70px'},
            titleFontSize: {type: String, default: ''},
            titleColor: {type: String, default: ''},
            dateFontSize: {type: String, default: ''},
            dateColor: {type: String, default: ''},
            customClass: {type: String, default: ''},
            css: {type: String, default: ''},
        }
    }
</script>
