<template>
    <div class="theme-settings-editor">
        <div class="col-md-9">
            <div class="box box-primary" style="min-height: 525px;">
                <div class="box-header">
                    <h3 class="box-title">Customize Theme</h3>
                </div>
                <div class="box-body noselect">
                    <el-tabs v-model="activeSection">
                        <template v-for="section in visibleSections">
                            <el-tab-pane :label="section.label" :name="section.key" :key="section.key">
                                <settings-renderer
                                    class="multilevel-form"
                                    :data="section"
                                    :settings.sync="settings[section.key]"
                                />
                            </el-tab-pane>
                        </template>
                    </el-tabs>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Theme Info</h3>
                </div>
                <div class="box-body noselect">
                    <div class="form-group">
                        <div class="form-group" v-if="themeData">
                            <div class="theme-info">
                                <label class="theme-info-label">Name:</label> <span>{{ themeData.name }}</span>
                            </div>
                            <div class="theme-info">
                                <label class="theme-info-label">Version:</label> <span>{{ themeData.version }}</span>
                            </div>
                            <div class="theme-info">
                                <label class="theme-info-label">Author:</label> <span>{{ themeData.author }}</span>
                            </div>
                            <div class="theme-info">
                                <label class="theme-info-label">Url:</label> <span>{{ themeData.url }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer" style="display: flex; justify-content: flex-end;">
                    <button @click="saveSettings" type="button" name="button" class="btn btn-primary btn-save">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import settingsRenderer from './SettingsRenderer.vue'
    import { setPropDeep } from '~/utils/helpers.js'

    const flatten = (obj, prefix = '', res = {}) =>
        Object.entries(obj).reduce((r, [key, val]) => {
            const k = `${prefix}${key}`
            if(typeof val === 'object' && !val.hasOwnProperty('id')){
                flatten(val, `${k}.`, r)
            } else {
                res[k] = val
            }
            return r
        }, res)

    export default {
        components: {
            settingsRenderer
        },
        provide() {
            return {
                rootSettings: this.settings
            }
        },
        data() {
            return {
                themeData: null,
                sectionData: null,
                activeSection: 'global',
                settings: {},
                settingsTmp: {}
            }
        },
        computed: {
            visibleSections() {
                return this.sectionData && this.sectionData.filter((o) => {
                    return o.meta ? !o.meta.hidden : true
                })
            },
        },
        methods: {
            setMainLayout(layout) {
                this.settings.layouts.main.value = layout
            },
            setLayout(contentType, layout) {
                this.settings.layouts[contentType].value = layout
            },
            saveSettings() {
                let formData = {
                    settings: flatten(this.settings)
                }

                return axios.post(route('api.design.themes.update-active'), formData)
                    .then((response) => {

                    })
                    .catch((error) => {

                    })
            },
            setSettings(settings, baseKeys = []) {
                _.forEach(settings, (setting) => {
                    let keys = [...baseKeys, setting.key]
                    if(setting.type == 'section') {
                        this.setSettings(setting.settings, keys)
                    } else {
                        setPropDeep(this.settings, keys, {
                            id: setting.id,
                            value: (setting.type=='json')
                                ? JSON.parse(setting.value)
                                : setting.value,
                            type: setting.type
                        })
                    }

                })
            },
            getThemeSettings() {
                return axios.get(route('api.design.themes.get-active'))
                    .then(({data: responseData}) => {
                        this.themeData = responseData.data
                        this.sectionData = this.themeData.settings

                        this.setSettings(responseData.data.settings)
                        // _.forEach(responseData.data.settings, (section) => {
                        //     _.forEach(section.settings, (setting) => {
                        //         !(this.settings && this.settings[setting.section]) &&  Vue.set(this.settings, setting.section, {[setting.key]: {}})

                        //         Vue.set(this.settings[setting.section], setting.key, {
                        //                 id: setting.id,
                        //                 value: (setting.type=='json')
                        //                     ? JSON.parse(o.value)
                        //                     : setting.value,
                        //                 type: setting.type
                        //             })
                        //     })
                        // })

                        // _.forEach(responseData.settings, (o) => {
                        //     !(this.settings && this.settings[o.section]) && Vue.set(this.settings, o.section, {[o.key]: {}})
                        //     // Vue.set(this.settings[o.section], o.key, _.pick(o, ['id', 'type', 'value']))
                        //     Vue.set(this.settings[o.section], o.key, {
                        //         id: o.id,
                        //         value: (o.type=='json')
                        //             ? JSON.parse(o.value)
                        //             : o.value,
                        //         type: o.type
                        //     })
                        // })
                    })
                    .catch((error) => {

                    })
            }
        },
        mounted() {
            this.getThemeSettings()
        }
    }
</script>

<style lang="scss" scoped>
    .theme-info {
        padding: 2px;
    }

    .theme-info-label {
        width: 80px;
    }

    .setting-row {
        display: flex;
        padding: 10px;
        border: 1px solid #00000021;
    }

    .setting-half-row {
        width: 50%;
    }

    .setting-half-row:first-child {
        margin-right: 5px;
    }
</style>
