<template>
    <v-modal ref="mediaModal"
        name="overlayed-modal"
        :classes="['v--modal', 'modal-content', 'modal-content_auto-responsive', 'modal-content_media-modal']"
        :overlay="true"
        transition="nice-modal-fade"
        :clickToClose="false"
        :customWidth="true"
        :height="'96%'"
        :minHeight="300"
        :delay="100"
        :adaptive="true"
        :resizable="false"
        :widthPadding="15">
        <div class="modal-body">
            <el-tabs v-model="activeTabName" class="el-tabs_elastic-content">
                <el-tab-pane label="Library" name="library">
                    <div class="el-tab-pane_search" style="margin: 10px; margin-left: 0px; margin-top: 0;">
                        <select class="form-control" v-model="order">
                            <option v-for="option in orderOptions" :key="option.value" :value="option.value">
                                {{ option.text }}
                            </option>
                        </select>
                        <select class="form-control" v-model="imageTagFilter">
                            <option value="">All Tags</option>
                            <option v-for="tag in imageTags" :key="tag.slug" :value="tag.slug">{{tag.title}}</option>
                        </select>
                    </div>
                    <div
                        v-if="activeTabName == 'library'"
                        class="el-tab-pane_items-container"
                        style="background-color: rgba(255, 255, 255, 0.02);">
                        <perfect-scrollbar class="el-tab-pane_items" ref="libraryContainer" @ps-scroll-y="scrollTrigger">
                            <div class="b-masonry-list" v-masonry transition-duration="0.8s" item-selector=".b-masonry-list_item">
                                <div
                                    v-masonry-tile
                                    v-for="image in images"
                                    @click="selectImage(image.id)"
                                    :key="image.id"
                                    class="b-masonry-list_item"
                                    :class="[{'b-masonry-list_item_selected': selectedImage == image.id}]"
                                    >
                                    <img
                                        :src=" '/' + image.path + '/' + image.filename +'_medium.'+ image.extension"
                                        alt=""
                                        class="b-masonry-list_img img-responsive">
                                </div>
                            </div>
                            <div
                                class="b-masonry-list_charging"
                                :class="{'b-masonry-list_charging_hidden': !loading}"
                            >
                                Loading...
                            </div>
                            <div style="clear: both;"></div>
                        </perfect-scrollbar>
                    </div>
                </el-tab-pane>
                <el-tab-pane label="Upload" name="upload">
                    <perfect-scrollbar class="b-dz-uploader"
                        :class="{
                            'b-dz-uploader_drop-active': $refs.upload && $refs.upload.dropActive,
                            'b-dz-uploader_filled': files.length
                        }"
                        @click.self="triggerBrowse"
                    >
                        <file-upload
                            class="b-dz-uploader_dnd-box"
                            ref="upload"
                            :headers="header"
                            v-model="files"
                            :post-action="uploadUrl"
                            accept="image/*"
                            :extensions="/\.(gif|jpeg|jpg|png)$/i"
                            :size="10 * 1024 * 1024"
                            :maximum="99"
                            :multiple="true"
                            :drop="true"
                            :thread="3"
                            @input-file="inputFile"
                            @input-filter="inputFilter"
                        >
                            <span class="drag-and-drop-here">
                                <i class="lo-icon lo-icon-upload-cloud"></i><br>
                                Drag and drop files here or click to upload...
                            </span>
                        </file-upload>
                        <transition-group name="el-fade-in" tag="div" class="m-inline-block">
                            <div v-for="file in files" :key="file.id"
                                class="dz-preview dz-file-preview"
                                :class="{
                                    'dz-error': file.error,
                                    'dz-processing': file.active
                                }"
                            >
                                <template v-if="!file.success">
                                    <div class="dz-image" style="width: 172px;height: 172px"
                                        :style="{'background-image': file.blob ? `url('${file.blob}')` : null}"
                                    >
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
                                        <i class="lo-icon lo-icon-close"></i>
                                    </div>
                                    <div class="dz-success-mark">
                                        <i class="lo-icon lo-icon-ok"></i>
                                    </div>
                                </template>
                            </div>
                        </transition-group>
                    </perfect-scrollbar>
                </el-tab-pane>
            </el-tabs>
            <div v-if="$refs.upload">
                <span>Uploaded: {{ uploadedFilesCount }}</span> <span v-if="failedFilesCount">Failed: {{ failedFilesCount }}</span>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="insertImage" :disabled="selectedImage == null ? true : false">Insert Image</button>
            <button type="button" class="btn btn-primary" @click="cancel">Cancel</button>
        </div>
    </v-modal>
</template>

<script>
    import PerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'
    import FileUpload from 'vue-upload-component'
    import Compressor from 'compressorjs'

    let URL = window.URL || window.webkitURL

    export default {
        components: {
            PerfectScrollbar,
            FileUpload
        },
        data() {
            return {
                modalTitle: 'Media Library',
                mode: null,
                activeTabName: 'library',
                uploadUrl: this.initUploadUrl,
                imageId: null,
                selectedImage: null,

                images: null,
                loading: false,
                page: 1,
                nextPageUrl: null,
                lastPage: 0,
                order: 'newer',
                orderOptions: [
                    { text: 'Newer', value: 'newer' },
                    { text: 'Older', value: 'older' }
                ],
                imageTags: null,
                imageTagFilter: '',
                imageTagsDefault: 'all',
                imageTagsOptions: [
                    { text: 'All Image Tags', value: 'all' }
                ],
                formData: {},
                filter: null,
                search: null,
                header: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                callingComponentId: null,
                callback: null,

                files: []
            }
        },
        props: {
            initUploadUrl: {type: String, default: route('api.media.upload').toString()},
        },
        computed: {
            failedFilesCount() {
                return _.filter(this.files, o => { return o.error }).length
            },
            uploadedFilesCount() {
                return _.filter(this.files, o => { return o.success }).length
            },
            allUploadedFlag() {
                // this.$refs.upload && this.$refs.upload.uploaded
                return !!(this.files.length && this.files.length == (this.failedFilesCount + this.uploadedFilesCount))
            }
        },
        watch: {
            order(val, old) {
                this.formData = {}
                this.formData = {order: val, tag: this.imageTagFilter}
                this.reinitLibrary()
            },
            imageTagFilter(val, old) {
                this.formData = {}
                this.formData = {order: val, tag: this.imageTagFilter}
                this.reinitLibrary()
            },
            activeTabName(val, old) {
                this.handleTabChange(val)
            },
            allUploadedFlag(val, old) {
                if(val) this.allUploaded()
            }
        },
        methods: {
            allUploaded() {
                this.reinitLibrary()
                if(this.failedFilesCount == 0) {
                    _.delay(() => {
                        this.activeTabName = 'library'
                    }, 200)
                }
            },
            selectImage(imageId) {
                this.selectedImage = imageId
            },
            insertImage() {
                this.$modal.hide('overlayed-modal')
                if(this.callback) this.callback( _.find(this.images, {id: this.selectedImage}) )
            },
            cancel() {
                this.$modal.hide('overlayed-modal')
            },
            handleTabChange(tab) {
                if(!tab) return false
                let tabName = (typeof tab === 'object') ? tab.name : tab
                switch(tabName) {
                    case 'library':
                        this.$refs.upload.clear()
                        break
                    case 'upload':
                        //
                        break
                }
            },
            reinitLibrary() {
                this.selectedImage = null
                this.images = null
                this.page = 1
                this.getImages()
            },
            getImages() {
                this.loading = true
                let url = route('api.media.images.index', {page: this.page})
                axios.get(url, {params: this.formData})
                    .then(({data: responseData}) => {
                        this.loading = false
                        this.lastPage = responseData.meta.last_page
                        this.nextPageUrl = responseData.links.next
                        let fetchedImages = responseData.data
                        this.images = _.unionBy(this.images, fetchedImages, 'id')
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },
            scrollTrigger() {
                let $libraryContainer = this.$refs.libraryContainer && this.$refs.libraryContainer.$el
                let clientHeight = $libraryContainer.clientHeight
                let scrollTop = $libraryContainer.scrollTop
                let scrollHeight = $libraryContainer.scrollHeight

                let heightDiff = scrollHeight - clientHeight

                if(heightDiff < 0) return

                let delta = heightDiff - scrollTop

                if(delta >= 0 && delta < 20) {
                    if(!this.loading && this.nextPageUrl) {
                        this.page = this.page + 1
                        this.getImages(this.page)
                    }
                }
            },
            loadImageTags() {
                axios.get(route('api.media.images.tags'))
                    .then(({data: responseData}) => {
                        this.imageTags = responseData.data
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            },
            inputFile(newFile, oldFile, prevent) {
                // Automatic upload
                if(Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
                    if(!this.$refs.upload.active) {
                        _.delay(() => {
                            this.$refs.upload.active = true
                        }, 500)
                    }
                }
            },
            inputFilter(newFile, oldFile, prevent) {
                if(newFile && !oldFile) {
                    // Create the 'blob' field for thumbnail preview
                    newFile.blob = ''

                    new Compressor(newFile.file, {
                        maxWidth: 250,
                        maxHeight: 250,
                        quality: 0.6,
                        success(result) {
                            if(URL) {
                                newFile.blob = URL.createObjectURL(result)
                            }
                        },
                        error(err) {
                            window.alert(e.message)
                        },
                    })
                }
            },
            triggerBrowse() {
                let el = this.$refs.upload.$el.querySelector("[name='file']")
                el && el.click()
            }
        },
        mounted() {
            this.$bus.$on('open-media-modal', (mode, params) => {
                this.loadImageTags()
                this.getImages()

                this.imageTagFilter = ''
                this.mode = mode
                this.$modal.show('overlayed-modal')
                this.selectImage(params.imageId)
                this.callback = (params.cb) ? params.cb : null
            })

            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('open-media-modal')
            })
        }
    }
</script>

<style lang="scss">
    .modal-content_media-modal {
        select {
            float: left;
            width: 150px;
            margin-right: 5px;
        }
    }
    .el-loading-mask {
        background-color: rgba(0,0,0,.5) !important;
    }
    .el-loading-spinner {
        .path {
            stroke-width: 6;
        }
    }
</style>
