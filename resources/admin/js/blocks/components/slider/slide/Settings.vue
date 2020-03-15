<template>
    <div class="block-settings">
        <el-tabs v-model="activeTab">
            <el-tab-pane label="General" name="general">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" v-model="settings.blockTitle"/>
                </div>

                <div v-if="!settings.displayResponsive" class="form-group">
                    <label>Display</label> <i @click="settings.displayResponsive = !settings.displayResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                    <select class="form-control" v-model="settings.display">
                        <option v-for="option in optionsDisplay" :key="option.key" :value="option.key">{{ option.value }}</option>
                    </select>
                </div>

                <div v-else class="form-group">
                    <label>Display</label> <i @click="settings.displayResponsive = !settings.displayResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                    <select-responsive
                        :options="optionsDisplay"
                        :extraLarge.sync="settings.display"
                        :large.sync="settings.displayLarge"
                        :medium.sync="settings.displayMedium"
                        :small.sync="settings.displaySmall"
                        :extraSmall.sync="settings.displayExtraSmall">
                    </select-responsive>
                </div>

                <div v-if="settings.display == 'flex' || settings.displayLarge == 'flex' || settings.displayMedium == 'flex' || settings.displaySmall == 'flex' || settings.displayExtraSmall == 'flex'">
                    <div v-if="!settings.flexDirectionResponsive" class="form-group">
                        <label>Flex Direction</label> <i @click="settings.flexDirectionResponsive = !settings.flexDirectionResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.flexDirection">
                            <option v-for="option in optionsFlexDirection" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Flex Direction</label> <i @click="settings.flexDirectionResponsive = !settings.flexDirectionResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsFlexDirection"
                            :extraLarge.sync="settings.flexDirection"
                            :large.sync="settings.flexDirectionLarge"
                            :medium.sync="settings.flexDirectionMedium"
                            :small.sync="settings.flexDirectionSmall"
                            :extraSmall.sync="settings.flexDirectionExtraSmall">
                        </select-responsive>
                    </div>

                    <div v-if="!settings.flexWrapResponsive" class="form-group">
                        <label>Flex Wrap</label> <i @click="settings.flexWrapResponsive = !settings.flexWrapResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.flexWrap">
                            <option v-for="option in optionsFlexWrap" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Flex Wrap</label> <i @click="settings.flexWrapResponsive = !settings.flexWrapResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsFlexWrap"
                            :extraLarge.sync="settings.flexWrap"
                            :large.sync="settings.flexWrapLarge"
                            :medium.sync="settings.flexWrapMedium"
                            :small.sync="settings.flexWrapSmall"
                            :extraSmall.sync="settings.flexWrapExtraSmall">
                        </select-responsive>
                    </div>

                    <div v-if="!settings.justifyContentResponsive" class="form-group">
                        <label>Justify Content</label> <i @click="settings.justifyContentResponsive = !settings.justifyContentResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.justifyContent">
                            <option v-for="option in optionsJustifyContent" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Justify Content</label> <i @click="settings.justifyContentResponsive = !settings.justifyContentResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsJustifyContent"
                            :extraLarge.sync="settings.justifyContent"
                            :large.sync="settings.justifyContentLarge"
                            :medium.sync="settings.justifyContentMedium"
                            :small.sync="settings.justifyContentSmall"
                            :extraSmall.sync="settings.justifyContentExtraSmall">
                        </select-responsive>
                    </div>

                    <div v-if="!settings.alignItemsResponsive" class="form-group">
                        <label>Align Items</label> <i @click="settings.alignItemsResponsive = !settings.alignItemsResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.alignItems">
                            <option v-for="option in optionsAlignItems" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                    </div>
                    <div v-else class="form-group">
                        <label>Align Items</label> <i @click="settings.alignItemsResponsive = !settings.alignItemsResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsAlignItems"
                            :extraLarge.sync="settings.alignItems"
                            :large.sync="settings.alignItemsLarge"
                            :medium.sync="settings.alignItemsMedium"
                            :small.sync="settings.alignItemsSmall"
                            :extraSmall.sync="settings.alignItemsExtraSmall">
                        </select-responsive>
                    </div>

                    <div v-if="!settings.alignContentResponsive" class="form-group">
                        <label>Align Content</label> <i @click="settings.alignContentResponsive = !settings.alignContentResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive"></i>
                        <select class="form-control" v-model="settings.alignContent">
                            <option v-for="option in optionsAlignItems" :key="option.key" :value="option.key">{{ option.value }}</option>
                        </select>
                        <small>This property has no effect when there is only one line of flex items.</small>
                    </div>
                    <div v-else class="form-group">
                        <label>Align Content</label> <i @click="settings.alignContentResponsive = !settings.alignContentResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                        <select-responsive
                            :options="optionsAlignItems"
                            :extraLarge.sync="settings.alignContent"
                            :large.sync="settings.alignContentLarge"
                            :medium.sync="settings.alignContentMedium"
                            :small.sync="settings.alignContentSmall"
                            :extraSmall.sync="settings.alignContentExtraSmall">
                        </select-responsive>
                        <small>This property has no effect when there is only one line of flex items.</small>
                    </div>
                </div>

                <!-- <div v-if="!settings.paddingResponsive" class="form-group">
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop" title="Responsive"></i>
                    <px :px.sync="settings.padding"></px>
                </div>
                <div v-else class="form-group">
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop" title="Responsive"></i>
                    <px-responsive
                        :extraLarge.sync="settings.padding"
                        :large.sync="settings.paddingLarge"
                        :medium.sync="settings.paddingMedium"
                        :small.sync="settings.paddingSmall"
                        :extraSmall.sync="settings.paddingExtraSmall"/>
                </div> -->
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

                <div v-if="settings.onClick=='open-link'" class="form-group">
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
    import SettingsMixin from '../../../mixins/SettingsMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [SettingsMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Slide'},

            displayResponsive: {type: Boolean, default: false},
            display: {type: String, default: 'block', childPropagation: true},
            displayLarge: {type: String, default: '', childPropagation: true},
            displayMedium: {type: String, default: '', childPropagation: true},
            displaySmall: {type: String, default: '', childPropagation: true},
            displayExtraSmall: {type: String, default: '', childPropagation: true},

            flexDirectionResponsive: {type: Boolean, default: false},
            flexDirection: {type: String, default: 'column', childPropagation: true},
            flexDirectionLarge: {type: String, default: ''},
            flexDirectionMedium: {type: String, default: ''},
            flexDirectionSmall: {type: String, default: ''},
            flexDirectionExtraSmall: {type: String, default: ''},

            flexWrapResponsive: {type: Boolean, default: false},
            flexWrap: {type: String, default: 'nowrap'},
            flexWrapLarge: {type: String, default: ''},
            flexWrapMedium: {type: String, default: ''},
            flexWrapSmall: {type: String, default: ''},
            flexWrapExtraSmall: {type: String, default: ''},

            justifyContentResponsive: {type: Boolean, default: false},
            justifyContent: {type: String, default: 'center'},
            justifyContentLarge: {type: String, default: ''},
            justifyContentMedium: {type: String, default: ''},
            justifyContentSmall: {type: String, default: ''},
            justifyContentExtraSmall: {type: String, default: ''},

            alignItemsResponsive: {type: Boolean, default: false},
            alignItems: {type: String, default: 'center'},
            alignItemsLarge: {type: String, default: ''},
            alignItemsMedium: {type: String, default: ''},
            alignItemsSmall: {type: String, default: ''},
            alignItemsExtraSmall: {type: String, default: ''},

            alignContentResponsive: {type: Boolean, default: false},
            alignContent: {type: String, default: 'flex-start'},
            alignContentLarge: {type: String, default: ''},
            alignContentMedium: {type: String, default: ''},
            alignContentSmall: {type: String, default: ''},
            alignContentExtraSmall: {type: String, default: ''},

            onClick: {type: String, default: 'nothing'},
            link: {type: String, default: 'http://www.laraone.app'},
            target: {type: String, default: '_blank'},

            css: {type: String, default: ''},
        }
    }
</script>
