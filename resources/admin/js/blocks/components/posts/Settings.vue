<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Block Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle">
                </div>

                <div class="form-group">
                    <label>Posts Type</label>
                        <select class="form-control" v-model="settings.postsType">
                            <option v-for="option in postsTypes" :key="option.key" :value="option.value">{{ option.text }}</option>
                        </select>
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
                        <option value='nothing'>Do nothing</option>
                        <option value='lightbox'>Lightbox</option>
                        <option value='open-link'>Link</option>
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
                    <input type="text" class="form-control" v-model="content.link">
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
                postsTypes: [
                    { text: 'Pages', value: '1' },
                    { text: 'Posts', value: '2' }
                ]
            }
        },
        customSettings: {
            blockTitle: {type: String, default: 'Posts'},
            renderTitle: {type: Boolean, default: false},
            postsType: {type: String, default: '1'},
            displayFeaturedImage: {type: Boolean, default: true},
            customClass: {type: String, default: ''},

            onClick: {type: String, default: 'lightbox'},
            target: {type: String, default: '_blank'},

            css: {type: String, default: ''},
        }
    }
</script>
