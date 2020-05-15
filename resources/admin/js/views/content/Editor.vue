<template>
    <div class="content-editor">
        <div :class="(editorSettings.wideLayout == true) ? 'col-md-12' : 'col-md-9'">
            <div class="box box-primary">
                <div class="box-header" style="display: flex; justify-content: space-between; width: 100%;">
                    <div style="flex: 1;">
                        <h3 class="box-title">Content Editor</h3>
                    </div>
                    <div v-if="!editorSettings.wideLayout && previewUrl"><a :href="previewUrl" target="_blank" style="width: auto; padding: 2px 10px;" class="btn btn-primary" type="submit" name="viewPost">View Post</a></div>
                    <div v-if="editorSettings.wideLayout" style="flex: 1; display: flex; justify-content: flex-end; align-items: center;">
                        <a v-if="previewUrl" :href="previewUrl" target="_blank" style="width: auto; padding: 2px 10px; margin-right: 5px;" class="btn btn-primary" type="submit" name="viewPost">View Post</a>
                        <i @click="$modal.show('editor-tips-modal')" title="Editor Shortcuts" class="lo-icon lo-icon-info-circled" style="margin-right: 5px;"></i>
                        <i @click="$modal.show('editor-settings-modal')" title="Settings" class="lo-icon lo-icon-cog" style="margin-right: 5px;"></i>
                        <button @click="saveAction()" :disabled='saveBlocks' style="width: 65px; padding: 2px 10px;" class="btn btn-primary" type="submit" name="savePost">{{ saveBtnText }}</button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="form-group">
                        <label style="margin-right: 5px;">Title</label>
                        <input name="postTitle" class="form-control" v-model="contentData.title"
                            v-validate="'required'"
                            :class="{'form-control':true, 'input': true, 'is-danger': errors.has('title') }"
                            type="text"
                            placeholder="Enter Title">
                        <span v-show="errors.has('title')" class="help is-danger">{{ errors.first('title') }}</span>
                    </div>

                    <el-tabs v-model="activeTabName">
                        <el-tab-pane label="Content" name="content">
                            <div style="display: flex; flex-direction: column; justify-content: space-between; min-height: 120px;">
                                <!-- {{ containerList }} -->
                                <div class="components">
                                    <draggable
                                        :move="onMove"
                                        @start="onDraggingStart"
                                        @end="onDraggingEnd"
                                        v-model="containerList"
                                        handle=".lo-icon-move"
                                        chosenClass="dragging1">
                                        <component-wrapper-functional v-for="container in containerList"
                                            :mode="mode"
                                            :freshComponent="container.freshComponent"
                                            :type="container.type"
                                            :uniqueId="container.uniqueId"
                                            :settings="container.settings"
                                            :show-labels="editorSettings.showLabels"
                                            :show-headers="editorSettings.showHeaders"
                                            v-on:remove="removeContainer(container.uniqueId)"
                                            :key="container.uniqueId"
                                            :overlayed="dragging">
                                        </component-wrapper-functional>
                                    </draggable>
                                </div>

                                <div class="component-buttons">
                                    <button type="button" name="addComponentButton" class="btn btn-component btn-sm" @click="openComponentsLibModal">+</button><!--
                                 --><template v-if="favoriteComponents && favoriteComponents.length">
                                        <button v-for="comp in favoriteComponents" :key="comp.block" :name="comp.block + 'Button'" v-on:click="addBlock(comp.block)" type="button" class="btn btn-component btn-sm">
                                            {{comp.title}}
                                        </button>
                                    </template>
                                    <div class="pull-right toolbox">
                                        <i title="Settings" class="lo-icon lo-icon-cog" name="openFavoritesModal" @click="openFavoritesModal"></i>
                                    </div>
                                </div>

                            </div>
                        </el-tab-pane>

                        <el-tab-pane label="Seo" name="seo" style="min-height: 120px;">
                            <div class="form-group">
                                <label>Slug</label>
                                <input name="seoSlug" type="text" class="form-control" v-model="contentData.slug">
                            </div>
                            <div class="form-group">
                                <label>SEO Title</label>
                                <input name="seoTitle" type="text" class="form-control" v-model="contentData.seo.title">
                                <span>Most search engines use a maximum of 60 characters for the title.</span>
                            </div>
                            <div class="form-group">
                                <label>Meta Decription</label>
                                <textarea name="seoDescription" class="form-control" rows="3" v-model="contentData.seo.metaDescription"></textarea>
                                <span>Most search engines use a maximum of 160 characters for the description.</span>
                            </div>
                            <div class="form-group">
                                <label>Focus Keyword</label>
                                <input name="seoFocusKeyword" type="text" class="form-control" v-model="contentData.seo.focusKeyword">
                            </div>
                            <div class="form-group">
                                <label>Preview</label>
                                <div class="seo-preview">
                                    <div name="seoPreviewTitle" class="title">{{ contentData.seo.title }}</div>
                                    <div name="seoPreviewUrl" class="slug">{{ previewUrl }}</div>
                                    <div name="seoPreviewDescription" class="meta">{{ contentData.seo.metaDescription }}</div>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label>Analysis</label>
                                <div>Article Heading: Yes</div>
                                <div>Page title: Yes</div>
                                <div>Page URL: Yes</div>
                                <div>Content: No</div>
                                <div>Meta description: Yes</div>
                            </div> -->
                        </el-tab-pane>

                        <el-tab-pane v-if="defaultContentLayout" label="Layout" name="layout" style="min-height: 120px;">
                            <div name="defaultContent" v-if="contentData.layout == 'default'" class="form-group">
                                This post will be displayed with default layout.
                            </div>
                            <div name="defaultContentOverriden" v-else class="form-group">
                                You have overriden the default layout for this post.
                            </div>
                            <div class="form-group">
                                <label>Default Layout</label>
                                <div class="content-layouts" style="justify-content: flex-start;">
                                    <div name="defaultLayoutFullwidth" v-if="defaultContentLayout =='content-fullwidth'" :style="{'border': (contentData.layout=='default' ?  '2px solid #20a0ff78' : '')}" @click="setLayout('default')" class="content-layout">
                                        <div class="content-fullwidth">
                                            Content
                                        </div>
                                    </div>

                                    <div name="defaultLayoutContentSidebar" v-if="defaultContentLayout =='content-sidebar'" :style="{'border': (contentData.layout=='default' ?  '2px solid #20a0ff78' : '')}" @click="setLayout('default')" class="content-layout">
                                        <div class="content-item">
                                            Content
                                        </div>
                                        <div class="sidebar-item">
                                            Sidebar
                                        </div>
                                    </div>

                                    <div name="defaultLayoutSidebarContent" v-if="defaultContentLayout =='sidebar-content'" :style="{'border': (contentData.layout=='default' ?  '2px solid #20a0ff78' : '')}" @click="setLayout('default')" class="content-layout">
                                        <div class="sidebar-item">
                                            Sidebar
                                        </div>
                                        <div class="content-item">
                                            Content
                                        </div>
                                    </div>

                                    <div name="defaultLayoutNone" v-if="defaultContentLayout =='none'" :style="{'border': (contentData.layout=='default' ?  '2px solid #20a0ff78' : '')}" @click="setLayout('default')" class="content-layout">
                                        <div style="display: flex; flex: 1; justify-content: center; align-items: center;">
                                            None
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Layouts</label>
                                <div class="content-layouts">
                                    <div name="layoutContentFullwidth" :style="{'border': (contentData.layout=='content-fullwidth' ?  '2px solid #20a0ff78' : '')}" @click="setLayout('content-fullwidth')" class="content-layout">
                                        <div class="content-fullwidth">
                                            Content
                                        </div>
                                    </div>

                                    <div name="layoutContentSidebar" :style="{'border': (contentData.layout=='content-sidebar' ?  '2px solid #20a0ff78' : '')}" @click="setLayout('content-sidebar')" class="content-layout">
                                        <div class="content-item">
                                            Content
                                        </div>
                                        <div class="sidebar-item">
                                            Sidebar
                                        </div>
                                    </div>

                                    <div name="layoutSidebarContent" :style="{'border': (contentData.layout=='sidebar-content' ?  '2px solid #20a0ff78' : '')}" @click="setLayout('sidebar-content')" class="content-layout">
                                        <div class="sidebar-item">
                                            Sidebar
                                        </div>
                                        <div class="content-item">
                                            Content
                                        </div>
                                    </div>

                                    <div name="layoutNone" :style="{'border': (contentData.layout=='none' ?  '2px solid #20a0ff78' : '')}" @click="setLayout('none')" class="content-layout">
                                        <div style="display: flex; flex: 1; justify-content: center; align-items: center;">
                                            None
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>

                        <el-tab-pane v-if="defaultContentSettings" label="Settings" name="settings" style="min-height: 120px;">
                            <div class="form-group">
                                <label>Show Title</label>
                                <select name="showTitle" class="form-control" v-model="contentData.settings.showTitle">
                                    <option :value="true">Show</option>
                                    <option :value="false">Hide</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Show Meta Data</label>
                                <select name="showMetaData" class="form-control" v-model="contentData.settings.showMetaData">
                                    <option :value="true">Show</option>
                                    <option :value="false">Hide</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Show Author Bio</label>
                                <select name="showAuthorBio" class="form-control" v-model="contentData.settings.showAuthorBio">
                                    <option :value="true">Show</option>
                                    <option :value="false">Hide</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Show Comments</label>
                                <select name="showComments" class="form-control" v-model="contentData.settings.showComments">
                                    <option :value="true">Show</option>
                                    <option :value="false">Hide</option>
                                </select>
                            </div>
                        </el-tab-pane>

                        <!-- <el-tab-pane label="Css" name="css">
                            <div class="form-group">
                                <prism-editor v-if="!$root.isFirefox" v-model="contentData.css" :lineNumbers=true language="css"></prism-editor>
                                <textarea v-else v-model="contentData.css" class="form-control" rows="3"></textarea>
                            </div>
                        </el-tab-pane> -->
                        <!-- 
                        <el-tab-pane label="Js" name="js">
                            <div class="form-group">
                                <prism-editor v-if="!$root.isFirefox" v-model="contentData.js" :lineNumbers=true language="javascript"></prism-editor>
                                <textarea v-else v-model="contentData.js" class="form-control" rows="3"></textarea>
                            </div>
                        </el-tab-pane> -->
                    </el-tabs>
                </div>
            </div>
        </div>

        <div v-if="!editorSettings.wideLayout" class="col-md-3">
            <div class="box box-primary">
                <!-- <button @click="changeMode">CHANGE MODE</button> -->
                <div class="box-header" style="display: flex; justify-content: space-between;">
                    <div style="flex: 1;">
                        <h3 class="box-title">Post Details</h3>
                    </div>
                    <div style="flex: 1; display: flex; justify-content: flex-end; align-items: center;">
                        <i name="editorShortcuts" @click="$modal.show('editor-tips-modal')" title="Editor Shortcuts" class="lo-icon lo-icon-info-circled" style="margin-right: 5px;"></i>
                        <i name="editorSettings" @click="$modal.show('editor-settings-modal')" title="Settings" class="lo-icon lo-icon-cog" style="margin-right: 5px;"></i>
                        <button @click="saveAction()" :disabled='saveBlocks' style="width: 65px; padding: 2px 10px;" class="btn btn-primary" type="submit" name="savePost">{{saveBtnText}}</button>
                    </div>
                </div>

                <div class="box-body">
                    <div class="form-group sidebar">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="form-group">
                                <span name="postStatus" @click="cycleStatus" class="label label-info" style="cursor: pointer; font-size: 14px;">{{statusText}}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div><label>Author</label></div>
                            <div name="postAuthor" class="form-group content-author">
                                {{ contentData.author.firstname }} {{ contentData.author.lastname }}
                            </div>
                        </div>

                        <div v-if="allowedTaxonomies && allowedTaxonomies.length && editorSettings.showTaxonomies" class="form-group">
                            <div v-for="taxonomy in allowedTaxonomies" :key="taxonomy.id" class="form-group">
                                <label>{{ taxonomy.name }}</label>
                                <div class="form-group_toolbox pull-right">
                                    <router-link :to="{ name: 'taxonomy', params: {contentTypeId, taxonomyId: taxonomy.id }}" class="content-title">
                                        <i :title="'Edit ' + taxonomy.name" class="lo-icon lo-icon-cog"></i>
                                    </router-link>
                                </div>
                                <v-select
                                    :multiple="taxonomy.settings['allowMultiple']"
                                    :push-tags="taxonomy.settings['allowCreate']"
                                    :taggable="taxonomy.settings['allowCreate']"
                                    :filterable="taxonomy.settings['allowFilterable']"
                                    v-model="taxonomies[taxonomy.id]"
                                    @input="updateTaxonomy($event, taxonomy)"
                                    :options="taxonomy.terms"
                                    label="name"
                                    max-height="100px">
                                </v-select>
                            </div>
                        </div>

                        <div v-if="editorMode=='edit' && editorSettings.showFeaturedImage" class="form-group">
                            <label>Featured Image</label>
                            <div v-if="!(featuredimage && featuredimage.id)" name="setFeaturedImage" @click="openMediaModal" class="set-featured-image">
                                Set Featured Image
                            </div>
                            <div v-else>
                                <div @click="openMediaModal" class="featured-image">
                                    <img name="postFeaturedImage" :src="featuredimage.medium" class="img-responsive">
                                </div>
                                <div @click="removeFeaturedImage" name="removeFeaturedImage" class="text-center remove-featured-image">
                                    Remove Featured Image
                                </div>
                            </div>
                        </div>


                        <template v-if="editorMode=='edit' && editorSettings.showContentDates">
                            <div class="form-group">
                                <div><label>Dates</label></div>
                                <div class="content-date-item">
                                    <div class="content-date-text">Created</div> <div name="postCreatedAt" class="content-date">{{ contentData.createdAt }}</div>
                                </div>
                                <div class="content-date-item">
                                    <div class="content-date-text">Updated</div> <div name="postUpdatedAt" class="content-date">{{ contentData.updatedAt }}</div>
                                </div>
                                <div class="content-date-item">
                                    <div class="content-date-text">Published</div> <div name="postPublishedAt" class="content-date">{{ contentData.publishedAt }}</div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <scene-explorer />
        <content-blocks-popup storeType="content" />
        <favorite-blocks-popup />
        <template-form storeType="content" />

        <v-modal ref="editorSettingsModal"
            name="editor-settings-modal"
            :classes="['v--modal', 'modal-content']"
            :overlay="true"
            transition="nice-modal-fade"
            :width="400"
            :height="420"
            :adaptive="true"
            :widthPadding="15">
            <div class="modal-header grabbable">
                <button type="button" class="close" aria-label="Close" @click="$modal.hide('editor-settings-modal')">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title animated">{{ settingsModalTitle }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Editor Layout</label>
                    <select name="editorWideLayout" class="form-control" v-model="editorSettings.wideLayout">
                        <option :value='true'>Wide</option>
                        <option :value='false'>Standard</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Content Block Headers</label>
                    <select name="editorShowHeaders" class="form-control" v-model="editorSettings.showHeaders">
                        <option :value='true'>Show</option>
                        <option :value='false'>Hide</option>
                    </select>
                </div>

                <div v-if="editorSettings.showHeaders == false" class="form-group">
                    <label>Content Block Labels</label>
                    <select name="editorShowLabels" class="form-control" v-model="editorSettings.showLabels">
                        <option :value='true'>Show</option>
                        <option :value='false'>Hide</option>
                    </select>
                </div>

                <!-- <div class="form-group">
                    <label>Auto Save</label>
                    <select class="form-control" v-model="editorSettings.autoSave">
                        <option value=0>Off</option>
                        <option value=30>Every 30 seconds</option>
                        <option value=60>Every 60 seconds</option>
                        <option value=120>Every 120 seconds</option>
                    </select>
                </div> -->

                <div class="form-group">
                    <label>Show Taxonomies</label>
                    <select name="showTaxonomies" class="form-control" v-model="editorSettings.showTaxonomies">
                        <option :value='true'>Show</option>
                        <option :value='false'>Hide</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Show Featured Image</label>
                    <select name="showFeaturedImage" class="form-control" v-model="editorSettings.showFeaturedImage">
                        <option :value='true'>Show</option>
                        <option :value='false'>Hide</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Show Content Dates</label>
                    <select name="showContentDates" class="form-control" v-model="editorSettings.showContentDates">
                        <option :value='true'>Show</option>
                        <option :value='false'>Hide</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Show Shortcut Notifications</label>
                    <select name="shortcutNotifications" class="form-control" v-model="editorSettings.shortcutNotifications">
                        <option :value='true'>Enabled</option>
                        <option :value='false'>Disabled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Show Editor Notifications</label>
                    <select name="editorNotifications" class="form-control" v-model="editorSettings.editorNotifications">
                        <option :value='true'>Enabled</option>
                        <option :value='false'>Disabled</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" name="saveEditorSettings" @click="saveEditorSettingsTrigger">Save</button>
            </div>
        </v-modal>

        <v-modal ref="editorTipsModal"
            name="editor-tips-modal"
            :classes="['v--modal', 'modal-content']"
            :overlay="true"
            transition="nice-modal-fade"
            :width="400"
            :height="350"
            :adaptive="true"
            :widthPadding="15">
            <div class="modal-header grabbable">
                <button type="button" class="close" aria-label="Close" @click="$modal.hide('editor-tips-modal')">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title animated">{{ shortcutsModalTitle }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group" style="padding-top: 4px;">
                        <span style="font-weight: bold;">ctrl + e</span> - Open Scene Manager
                    </div>
                    <div class="form-group" style="padding-top: 4px;">
                        <span style="font-weight: bold;">ctrl + s</span> - Save content
                    </div>
                    <div class="form-group" style="padding-top: 4px;">
                        <span style="font-weight: bold;">ctrl + l</span> - Toggle wide layout
                    </div>
                    <div class="form-group" style="padding-top: 4px;">
                        <span style="font-weight: bold;">ctrl + h</span> - Toggle content block header
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="$modal.hide('editor-tips-modal')">Close</button>
            </div>
        </v-modal>
    </div>
</template>

<script>
    import { mapState, mapGetters, mapActions } from 'vuex'
    import ComponentWrapperFunctional from '~/blocks/ComponentWrapperFunctional'
    import { processSettings, processSettingsConfig, getComponentByName, getComponentsList } from '~/utils/helpers.js'
    import draggable from 'vuedraggable'
    import ContentBlocksPopup from '~/components/ContentBlocksPopup'
    import FavoriteBlocksPopup from '~/components/FavoriteBlocksPopup'
    import TemplateForm from '~/components/TemplateForm'
    import { format } from 'date-fns'

    export default {
        components: {
            ContentBlocksPopup,
            FavoriteBlocksPopup,
            TemplateForm,
            draggable,
            ComponentWrapperFunctional
        },
        data() {
            return {
                settingsModalTitle: 'Editor Settings',
                shortcutsModalTitle: 'Editor Shortcuts',
                activeTabName: 'content',

                showContent: true,
                dateTimePicker: '',
                saveBlocks: false,

                mode: 'backend',
                dragging: false,
            }
        },
        props: {
            id: {type: [Number, String]},
            contentTypeSlug: {type: String, default: ''},
        },
        watch: {
            id(val, old) { this.init() },
            contentData: {
                handler(data) { this.setContentData({path: 'data', data}) },
                deep: true
            },
            settings: {
                handler(data) { this.setContentData({path: 'settings', data}) },
                deep: true
            },
            dateTimePicker(val, old) {
                this.publishAt = val
            },
            statusMode(val) {
                if(['publish', 'draft'].includes(val)) {
                    this.contentData.publishAt = null
                } else if(['schedule', 'scheduled'].includes(val)) {
                    this.contentData.publishAt = this.dateTimePicker
                } else if (val == 'published') {
                    this.contentData.publishAt = this.contentData.publishedAt
                }
            }
        },
        computed: {
            contentTypeId() {
                return this.$store.getters['contentTypes/index/getIdBySlug'](this.contentTypeSlug)
            },
            contentTypeObj() {
                return this.contentTypeId && this.$store.getters['contentTypes/index/getObj'](this.contentTypeId)
            },
            ...mapState('content/editor', {
                contentData: state => state.data,
                originalData:  state => state.originals.data,
                featuredimage: state => state.data.featuredimage,
                settings: state => state.settings,
                editorSettings: state => state.editorSettings,
                terms: state => state.terms,
                allowedTaxonomies: state => state.allowedTaxonomies,
                defaultContentLayout: state => state.defaultContentLayout,
                defaultContentSettings: state => state.defaultContentSettings,
            }),
            ...mapGetters({
                user: 'content/editor/user',
                editorMode: 'content/editor/editorMode',
                taxonomies: 'content/editor/taxonomiesObject',
                favoriteBlocks: 'content/editor/favoriteBlocks'
            }),
            containerList: {
                get() { return this.$store.getters['content/editor/rootItems'] },
                set(object) { this.updateItemsList({list: _.map(object, 'uniqueId')}) }
            },
            favoriteComponents() {
                return getComponentsList({
                    componentsList: this.favoriteBlocks,
                    location: 'content'
                })
            },
            saveBtnText() {
                if(this.saveBlocks)
                    return 'Saving'
                else {
                    return 'Save'
                }
            },
            statusMode() {
                let publishedStatus = 'publish'

                switch (this.contentData.status) {
                    case 1:
                        publishedStatus = this.contentData.publishedAt ? 'published' : 'publish'
                        break;
                    case 2:
                        publishedStatus = 'draft'
                        break;
                    case 3:
                        publishedStatus = this.contentData.publishedAt ? 'scheduled' : 'schedule'
                        break;
                }

                return publishedStatus
            },
            publishAt: {
                get() { return this.$store.content.data.publishAt },
                set(date) { this.setContentData({path: 'data', data: {publishAt: date}}) }
            },
            modeText() { return _.capitalize(this.editorMode) },
            statusText() { return _.capitalize(this.statusMode) },
            dateText() {
                let dateText

                if(this.contentData.status == 1)
                    dateText = 'Immediately'
                else if(this.contentData.status == 2)
                    dateText = 'Not yet'

                if(this.editorMode == 'edit' && this.contentData.status == 1 && this.originalData.status == 1)
                    dateText = this.publishedAt
                else if(this.editorMode == 'edit' && this.contentData.status == 1 && this.originalData.status == 3)
                    dateText = 'Immediately'

                return dateText
            },
            previewUrl() {
                let output = [window.location.origin]
                const { front_slug } = this.contentTypeObj || {}
                front_slug && output.push(front_slug)
                this.contentData && output.push(this.contentData.slug)
                return output.join('/')
            }
        },
        methods: {
            ...mapActions('content/editor', [
                'resetState',
                'addItem',
                'addTemplateItem',
                'removeItem',
                'setTemplateSlug',
                'setContentType',
                'setContentId',
                'setContentData',
                'setFeaturedImage',
                'updateItemsList',
                'fetchContentData',
                'storeContent',
                'updateContent',
                'saveEditorSettings'
            ]),
            saveEditorSettingsTrigger() {
                this.$modal.hide('editor-settings-modal')
                this.saveEditorSettings()
            },
            setLayout(layout) {
                this.contentData.layout = layout
            },
            openComponentsLibModal() {
                let params = {
                    componentName: this.type,
                    cb: (data = {}) => {
                        let {type, identifier} = data
                        switch (type) {
                            case 'block':
                                this.addBlock(identifier)
                                break;
                            case 'template':
                                this.addTemplateItem({ id: identifier })
                                break;
                        }
                    },
                    location: 'content'
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            openFavoritesModal() {
                this.$bus.$emit('open-favorites-modal', {location: 'content'})
            },
            addBlock(compName = 'Container') {
                let comp = getComponentByName(compName)
                if(!comp) return false
                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    freshComponent: true,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            setFocus(component) {
                switch (component) {
                    case 'Text':
                        console.log('we got text component')
                        this.$refs['text'].api.elements[0].focus()
                        break;
                    case 'Youtube':
                        console.log('we got youtube component')
                        this.$refs['video'].focus()
                        break;
                    default:
                        break;
                }
            },
            updateFeaturedImage(image) {
                this.setFeaturedImage(image)
                let formData = {
                    id: this.id,
                    featuredImageId: this.featuredimage.id
                }
                return axios.post(route('api.content.set.featuredimage'), formData)
                    .then((response) => {
                        //
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },
            removeFeaturedImage() {
                this.setFeaturedImage(null)
                let formData = {
                    id: this.id
                }
                return axios.post(route('api.content.remove.featuredimage'), formData)
                    .then((response) => {
                    })
                    .catch((error) => {

                    })
            },
            openMediaModal() {
                let mode = 'featuredImage',
                    params = {
                        cb: (image) => {
                            this.updateFeaturedImage(image)
                        }
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            onDraggingStart(evt) {
                this.dragging = true
            },
            onDraggingEnd(evt) {
                this.dragging = false
            },
            onMove(evt) {
                return !evt.related.classList.contains('item_disabled')
            },
            localDateTime(dateTime) {
                if(dateTime) {
                    return format(
                        new Date(`${dateTime} UTC`),
                        'yyyy-MM-dd HH:mm'
                    )
                } else {
                    return null
                }
            },
            saveAction() {
                this.$validator.validateAll().then( result => {
                    result && this.save().then(() => {
                        this.editorSettings.editorNotifications && this.$notify({
                            customClass: 'content-editor-notification',
                            message: 'Content saved',
                            position: 'top-right',
                            offset: 80,
                            showClose: false,
                            duration: 2000
                        })
                    })
                })
            },
            save() {
                return (this.editorMode == 'create') ? this.storeContent() : this.updateContent()
            },
            cycleStatus() {
                this.contentData.status = this.contentData.status + 1
                if(this.contentData.status > 2)
                    this.contentData.status = 1
            },
            removeContainer(containerId) {
                this.removeItem({
                    id: containerId
                })
            },
            changeMode() {
                this.mode = (this.mode == 'frontend') ? 'backend' : 'frontend'
            },
            updateTaxonomy(val, taxonomy) {
                if(!val || !val.length) return

                let taxonomyId = taxonomy.id
                let maxAllowed = taxonomy.settings['maxAllowed']

                if(val.length > maxAllowed) {
                    val.pop()
                }
            },
            init(payload) {
                let {id = this.id, contentTypeId = this.contentTypeId} = payload || {}

                this.resetState()
                this.setContentType(contentTypeId)

                if(id) {
                    this.setContentId(id)
                    this.fetchContentData()
                } else {
                    this.fetchContentData({mode: 'default'})
                }
            },
            setKeyShortcuts(event) {
                let key = event.keyCode
                let notifyDuration = 2000
                let notifyOffset = 50
                let notifyShowClose = false
                let notifyPosition = 'bottom-left'

                if(event.ctrlKey) {
                    switch(key) {
                        case 83:
                            event.preventDefault()
                            this.editorSettings.shortcutNotifications && this.$notify({
                                customClass: 'content-editor-notification',
                                message: 'CTRL + S',
                                position: notifyPosition,
                                offset: notifyOffset,
                                showClose: notifyShowClose,
                                duration: notifyDuration
                            })
                            this.saveAction()
                        break;
                        // char L pressed - wide layout
                        case 76:
                            event.preventDefault()
                            this.editorSettings.shortcutNotifications && this.$notify({
                                customClass: 'content-editor-notification',
                                message: 'CTRL + L',
                                position: notifyPosition,
                                offset: notifyOffset,
                                showClose: notifyShowClose,
                                duration: notifyDuration
                            })
                            this.editorSettings.wideLayout = !this.editorSettings.wideLayout
                            let contentTypeId = this.contentTypeId,
                                formData = {
                                    settings: {
                                        wideLayout: this.editorSettings.wideLayout
                                    }
                                }

                            axios.post(route('api.content.editor.settings', {contentTypeId}), formData)
                        break;

                        // char H pressed
                        case 72:
                            event.preventDefault()
                            this.editorSettings.shortcutNotifications && this.$notify({
                                message: 'CTRL + H',
                                position: notifyPosition,
                                offset: notifyOffset,
                                showClose: notifyShowClose,
                                duration: notifyDuration
                            })
                            let shouldWeShowHeaders = !this.editorSettings.showHeaders

                            if(!this.showContent)
                                shouldWeShowHeaders = true

                            this.editorSettings.showHeaders = shouldWeShowHeaders
                        break;

                        // char E pressed, open scene explorer
                        case 69:
                            event.preventDefault()
                            this.editorSettings.shortcutNotifications && this.$notify({
                                customClass: 'content-editor-notification',
                                message: 'CTRL + E',
                                position: notifyPosition,
                                offset: notifyOffset,
                                showClose: notifyShowClose,
                                duration: notifyDuration
                            })
                            let mode = 'scene-explorer-all'
                            let params = {
                                type: this.$options.name
                            }
                            this.$bus.$emit('open-scene-modal', mode, params)
                        break;

                        // char c pressed, open components explorer
                        // case 67:
                        //     event.preventDefault()
                        //     this.openComponentsLibModal()
                        // break;
                    }
                }
            }
        },
        created() {
            // this.$store.dispatch('content/favorites/getComponentsData')
            this.init()

            window.addEventListener('keydown', this.setKeyShortcuts)
            this.$once('hook:beforeDestroy', () => {
                window.removeEventListener('keydown', this.setKeyShortcuts)
            })
        },
        updated() {
            // this.$nextTick(function () {
            //     console.log('something has been added')
            //     console.log(this.$refs)
            //     if(this.$refs.videoInput) this.$refs.videoInput.focus()
            // })
        },
        mounted() {
        }
    }
</script>


<style>
    .seo-preview {
        background-color: #283339;
        color: rgba(159, 194, 213, 0.7);
        padding: 10px;
        font-size: 13px;
    }
    .title {
        font-size: 17px;
        margin-bottom: 3px;
    }
    .slug {
        font-size: 14px;
        margin-bottom: 2px;
    }
    .content-date-item {
        padding: 3px 3px 3px 10px;
        display: flex;
        justify-content: space-between;
    }

    .content-date {
        font-weight: 700;
    }

    .content-author {
        font-size: 18px;
    }
</style>
