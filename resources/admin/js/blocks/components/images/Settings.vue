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
                        <px :maxAllowed=1 :px.sync="settings.flex"></px>
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
                    <label>Images</label>
                    <el-input-number v-model="settings.imageAmount" :min="minImages" :max="maxImages"></el-input-number>
                </div>

                <div class="form-group">
                    <label>Columns</label>
                    <el-input-number v-model="settings.columnsPerRow" :min="1" :max="6"></el-input-number>
                </div>

                <div class="form-group">
                    <label>Image Size</label>
                    <select class="form-control" v-model="settings.imageSize">
                        <option v-for="option in imageSizeOptions" :value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>

                <div v-if="!settings.imageHeightResponsive" class="form-group">
                    <label>Image Height</label> <i @click="settings.imageHeightResponsive = !settings.imageHeightResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive On"></i>
                    <px :px.sync="settings.imageHeight"></px>
                </div>
                <div v-else class="form-group">
                    <label>Image Height</label> <i @click="settings.imageHeightResponsive = !settings.imageHeightResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive Off"></i>
                    <px-responsive
                        :extraLarge.sync="settings.imageHeight"
                        :large.sync="settings.imageHeightLarge"
                        :medium.sync="settings.imageHeightMedium"
                        :small.sync="settings.imageHeightSmall"
                        :extraSmall.sync="settings.imageHeightExtraSmall">
                    </px-responsive>
                </div>

                <div class="form-group">
                    <label>Image Spacing</label>
                    <px :maxAllowed="1" :px.sync="settings.spaceBetweenImages"></px>
                </div>

                <div class="form-group">
                    <label>Image Border</label>
                    <border v-model="settings.imageBorder"></border>
                </div>

                <div class="form-group">
                    <label>Image Border Radius</label>
                    <px :maxAllowed="1" :px.sync="settings.imageBorderRadius"></px>
                </div>

                <div v-if="!settings.paddingResponsive" class="form-group">
                    <label>Padding</label> <i @click="settings.paddingResponsive = !settings.paddingResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive On"></i>
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
                    <label>Margin</label> <i @click="settings.marginResponsive = !settings.marginResponsive" class="lo-icon lo-icon-desktop pull-right" title="Responsive On"></i>
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
                        :focus.sync="settings.backgroundColorFocus">
                    </color>
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
        data() {
            return {
                maxImages: 250,
                minImages: 1,
                imageSizeOptions: [
                    { text: 'Original', value: 'original' },
                    { text: 'Large', value: 'large' },
                    { text: 'Medium', value: 'medium' },
                    { text: 'Thumbnail', value: 'thumb' },
                ],
            }
        },
        customSettings: {
            blockTitle: {type: String, default: 'Images'},
            renderTitle: {type: Boolean, default: false},
            customClass: {type: String, default: ''},

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

            spaceBetweenImages: {type: String, default: '2px'},

            imageBorder: {type: String, default: '0px solid transparent'},
            imageBorderRadius: {type: String, default: '0px'},

            imageSize: {type: String, default: 'large'},
            columnsPerRow: {type: Number, default: 3},
            imageAmount: {type: Number, default: 3},

            imageWidth: {type: String, default: ''},

            imageHeightResponsive: {type: Boolean, default: false},
            imageHeight: {type: String, default: '300px'},
            imageHeightLarge: {type: String, default: ''},
            imageHeightMedium: {type: String, default: ''},
            imageHeightSmall: {type: String, default: ''},
            imageHeightExtraSmall: {type: String, default: ''},

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

            backgroundColorAdvanced: {type: Boolean, default: false},
            backgroundColor: {type: String, default: ''},
            backgroundColorHover: {type: String, default: ''},
            backgroundColorActive: {type: String, default: ''},
            backgroundColorFocus: {type: String, default: ''},

            onClick: {type: String, default: 'lightbox'},
            target: {type: String, default: '_blank'},

            css: {type: String, default: ''},
        }
    }
</script>
