<template>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Themes</h3>
            </div>
            <div class="box-body">
                <div class="new-themes">
                    <el-tabs v-model="activeName" @tab-click="handleTabClick">
                        <el-tab-pane label="Installed" name="installed">
                            <!-- <div style="height: 24px; margin-bottom: 5px;" class="pull-right">
                                <vue-pagination v-show="pagination.last_page > 1"  v-bind:pagination="pagination"
                                                v-on:page-updated="getThemes()"
                                                :offset="4">
                                </vue-pagination>
                            </div>
                            <div style="clear: both;"></div> -->
                            <div class="grid">
                                <div v-for="theme in themesList" :key="theme.id" class="theme-wrapper">
                                    <div @click="themeInfo(theme)" class="theme" :style="{'border': theme.id==activeTheme ? '3px solid rgb(60, 141, 188)' : ''}">
                                        <div class="screenshot" v-bind:style="{'background': 'url(/themes/' + themeName(theme.name) + '/images/screenshot.jpg)', 'background-position': 'top center', 'background-size': 'cover'}">
                                        </div>

                                        <!-- TITLE -->
                                        <div class="theme-name">
                                            {{ upperCase(theme.name) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>

                        <el-tab-pane label="Upload" name="upload">
                            <div style="margin-bottom: 10px;">
                                Here you can upload new theme. Provide a valid zip file.
                            </div>

                            <div style="height: 270px; overflow-y: hidden !important;"
                                :class="{
                                    'b-dz-uploader': true,
                                    'b-dz-uploader_drop-active': $refs.upload && $refs.upload.dropActive,
                                    'b-dz-uploader_filled': files.length
                                }"
                                @click.self="triggerBrowse"
                            >
                                <file-upload
                                    v-show="!files.length"
                                    class="b-dz-uploader_dnd-box"
                                    ref="upload"
                                    :headers="header"
                                    v-model="files"
                                    :post-action="uploadUrl"
                                    accept="application/zip"
                                    extensions="zip"
                                    :size="15 * 1024 * 1024"
                                    :drop="true"
                                    @input-file="inputFile"
                                    @input-filter="inputFilter"
                                >
                                    <div style="display: flex; flex-direction: column;">
                                        <i class="lo-icon lo-icon-upload-cloud"></i>
                                        <div>Drag and drop a file or click here to upload a theme...</div>
                                    </div>
                                </file-upload>
                                <template v-if="files.length">
                                    <div v-for="file in files" :key="file.id"
                                        class="dz-preview dz-file-preview"
                                        :class="{
                                            'dz-error': file.error,
                                            'dz-processing': file.active
                                        }"
                                    >
                                        <div class="dz-image" style="width: 160px; height: 160px"
                                            :style="{'background-image': file.blob ? `url('${file.blob}')` : null}"
                                        >
                                        </div>
                                        <div v-if="file.icon" class="dz-icon">
                                            <i :class="['fa', file.icon]"></i>
                                        </div>
                                        <div class="dz-progress">
                                            <span class="dz-upload"
                                                :style="{width: `${file.progress}%`}"
                                            ></span>
                                        </div>
                                        <div class="dz-details">
                                            <div class="dz-size">{{ file.size | formatSize }}</div>
                                            <div class="dz-filename"><span>{{ file.name }}</span></div>
                                        </div>
                                        <div class="dz-error-message">
                                            <span>{{ file.error }}</span>
                                        </div>
                                        <div class="dz-error-mark">
                                            <i class="lo-icon lo-icon-window-close"></i>
                                        </div>
                                        <div class="dz-success-mark">
                                            <i class="lo-icon lo-icon-ok"></i>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div v-if="uploadStatus=='success'" class="success-message">
                                <div class="form-group">
                                    Theme uploaded successfully. To activate it switch to installed tab and select the theme.
                                </div>
                            </div>
                            <div v-else class="error-message">
                                {{ message }}
                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </div>
            <v-modal ref="themeInfo"
                name="theme-info"
                :classes="['v--modal', 'modal-content']"
                :overlay="true"
                transition="nice-modal-fade"
                :clickToClose="true"
                :width="'700px'"
                :height="'800px'"
                :delay="100"
                :adaptive="false"
                :resizable="false">
                    <div class="modal-header">
                        <h4 class="modal-title animated">Theme Details</h4>
                    </div>
                    <VuePerfectScrollbar v-if="themePreviewed" class="modal-body" style="display: block; flex-direction: row;">
                        <div class="form-group">
                            <div class="theme-info">
                                <label class="theme-info-label">Name:</label> <span>{{ upperCase(themePreviewed.name) }}</span>
                            </div>
                            <div class="theme-info">
                                <label class="theme-info-label">Version:</label> <span>{{ themePreviewed.version }}</span>
                            </div>
                            <div class="theme-info">
                                <label class="theme-info-label">Author:</label> <span>{{ themePreviewed.author }}</span>
                            </div>
                            <!-- <div class="theme-info">
                                <label class="theme-info-label">Org:</label> <span>Laraone</span>
                            </div> -->
                            <!-- <div class="theme-info">
                                <label class="theme-info-label">Url:</label> <span>{{ themePreviewed.url }}</span>
                            </div> -->
                        </div>

                        <div class="form-group theme-info">
                            <label>Description:</label>
                            <div>
                                {{ themePreviewed.description }}
                                <br>
                            </div>
                        </div>
                        <div class="form-group theme-info">
                            <label class="theme-info-label">Preview:</label>
                            <img class="img-responsive" :src="'/themes/' + themeName(themePreviewed.name) + '/images/screenshot.jpg'" style="margin: 0 auto;"/>
                        </div>
                    </VuePerfectScrollbar>

                    <div v-if="themePreviewed" class="modal-footer">
                        <button type="button" class="btn btn-primary pull-right" @click="$modal.hide('theme-info')">Close</button>
                        <button v-if="themePreviewed.id!=activeTheme" type="button" class="btn btn-primary pull-right" @click="setActiveTrigger()">Activate</button>
                    </div>
            </v-modal>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import VuePerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'
    import FileUpload from 'vue-upload-component'

    const uploadUrl = route('api.design.themes.upload').toString()

    export default {
        components: {
            VuePerfectScrollbar,
            FileUpload
        },
        data() {
            return {
                themesList: [],
                mode: 'installed-themes',
                activeName: 'installed',
                header: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                pagination: {
                    total: 0,
                    per_page: 2,
                    from: 1,
                    to: 0,
                    current_page: 1
                },
                activeTheme: null,
                message: null,
                uploadStatus: null,
                uploadUrl,
                paging: true,
                themePreviewed: null,
                files: []
            }
        },
        computed: {
            queryString() {
                let query = ''

                if(this.paging)
                    query += '&page='+this.pagination.current_page

                return query
            },
            themeStyle(themeId) {
                let styles = ''
                if(themeId == this.activeTheme)
                    styles = styles + 'padding: 2px solid lightblue;'
                return styles
            },
        },
        methods: {
            upperCase(word) {
                return _.startCase(word)
            },
            themeInfo(theme) {
                this.$modal.show('theme-info')
                this.themePreviewed = theme
            },
            handleTabClick(tab, event) {
                switch (tab.name) {
                    case 'installed':
                        this.getThemes()
                    break;

                    case 'upload':
                        this.uploadStatus = null
                        this.message = null
                    break;
                }
            },
            themeName(name) {
                return name.replace(" ", "-").toLowerCase()
            },
            themeScreenshot(name) {
                let styleObj = {
                    'background': 'url(/themes/'+this.themeName(name)+'/images/screenshot.jpg) top center / cover',
                }
                return styleObj
            },
            addTheme(theme) {
                let newTheme = new Object()
                newTheme.uniqueId = 'theme-' + this.themesList.length
                newTheme.id = theme.id
                newTheme.author = theme.author
                newTheme.name = theme.name
                newTheme.description = theme.description
                newTheme.version = theme.version
                newTheme.changes = theme.changes
                newTheme.url = theme.url
                newTheme.namespace = theme.namespace
                this.themesList.push(newTheme)
            },
            getThemes() {
                axios.get(route('api.design.themes.index'))
                .then(({data: responseData}) => {
                    this.themesList = []

                    for (var i = 0; i < responseData.data.length; i++) {
                        this.addTheme(responseData.data[i])
                    }

                    this.pagination = responseData.meta
                    this.activeTheme = responseData.activeTheme
                })
                .catch((error) => {
                    console.log(error)
                })
            },
            setActiveTrigger() {
                this.$modal.hide('theme-info')
                this.activeTheme = this.themePreviewed.id
                this.showModal = false
                this.setActive(this.activeTheme)
            },
            setActive(id) {
                return axios.post(route('api.design.themes.set-active'), { id })
                    .then((response) => {
                        this.activeName = 'installed'
                        // console.log('theme activated')
                    })
                    .catch((error) => {
                        // console.log('we got some errors while trying to activate the theme')
                    })
            },
            goToList() {
                this.activeName = 'installed'
            },
            allUploaded(file,response) {
            //   console.log('All files were successfully uploaded')
              // if (this.failed == 0)
              //   this.activeTabName = 'library'
            },
            uploadStarted(files) {
                this.filesAdded = true
                this.message = null
                this.uploadStatus = null
            },
            fileUploaded(file, response) {
                this.uploaded = this.uploaded + 1
                this.uploadStatus = 'success'
                // console.log('file uploaded')
            },
            fileNotUploaded(file, error, xhr) {
                this.failed = this.failed + 1
                this.uploadStatus = 'error'
                this.message = error.message
                // this.$refs.upload.clear()
            },
            inputFile(newFile, oldFile, prevent) {
                if(newFile && oldFile) {
                    // Upload error
                    if(newFile.error !== oldFile.error) {
                        this.fileNotUploaded(newFile.file, newFile.response)
                    }

                    // Uploaded successfully
                    if(newFile.success !== oldFile.success) {
                        this.fileUploaded()
                    }
                }

                // Automatic upload
                if(Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
                    if(!this.$refs.upload.active && this.files.length) {
                        this.$refs.upload.active = true
                        this.uploadStarted()
                    }
                }
            },
            inputFilter(newFile, oldFile, prevent) {
                if(!/\.(zip)$/i.test(newFile.name)) {
                    return prevent()
                }
                if(newFile && !oldFile) {
                    if(!/\.(zip|gz)$/i.test(newFile.type)) {
                        newFile.icon = 'fa-file-archive-o'
                    }
                }
            },
            triggerBrowse() {
                let el = this.$refs.upload.$el.querySelector("[name='file']")
                el && el.click()
            }
        },
        mounted() {
            this.getThemes()
        }
    }
</script>

<style lang="scss" scoped>
    .success-message {
        padding: 10px 0;
        font-weight: 700;

        & button {
            width: 200px;
        }
    }

    .error-message {
        color: #b73232;
        padding: 10px 0;
        font-weight: 700;
    }

    .theme-info {
        padding: 2px;
        
        .theme-info-label {
            width: 60px;
        }
    }

    .theme-wrapper {
        width: 100%;
        padding: 0 4px 8px;

        @media only screen and (min-width: 480px) {
            width: 50%;
        }

        @media only screen and (min-width: 680px) {
            width: 33.3%;
        }

        @media only screen and (min-width: 1000px) {
            width: 20%;
        }

        .theme {
            cursor: pointer;

            .screenshot {
                overflow: hidden; 
                height: 270px; 
            }

            .theme-name {
                display: flex;
                flex-direction: column;
                background-color: rgba(0,0,0, 0.22);
                align-items: center;
                padding: 10px 12px;
                font-size: 20px;
            }
        }
    }
</style>