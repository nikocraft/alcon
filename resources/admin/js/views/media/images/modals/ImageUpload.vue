<template>
    <v-modal ref="imageUploadModal"
        name="image-upload-modal"
        :classes="['v--modal', 'modal-content', 'modal-content_auto-responsive', 'modal-content_image-upload-modal']"
        :overlay="true"
        transition="nice-modal-fade"
        :clickToClose="false"
        :customWidth="true"
        :height="'70%'"
        :minHeight="300"
        :delay="100"
        :adaptive="true"
        :resizable="false"
        :widthPadding="15">
        <div class="modal-body">
            <div class="l-dz-uploader_container">
                <div class="l-dz-uploader_form">
                    <div class="tags">
                        <v-select
                            multiple
                            taggable
                            v-model="selectedTags"
                            :options="imageTags"
                            label="title"
                            max-height="300px"
                            placeholder="Tags">
                        </v-select>
                    </div>
                </div>
                <div class="l-dz-uploader_content-box">
                    <div class="l-dz-uploader_content">
                        <div class="b-dz-uploader"
                            :class="{
                                'b-dz-uploader_drop-active': $refs.upload && $refs.upload.dropActive,
                                'b-dz-uploader_filled': files.length
                            }"
                            @click.self="triggerBrowse">
                            <file-upload
                                class="b-dz-uploader_dnd-box"
                                ref="upload"
                                :headers="header"
                                :data="additionData"
                                v-model="files"
                                :post-action="uploadUrl"
                                accept="image/*"
                                :extensions="/\.(gif|jpeg|jpg|png)$/i"
                                :size="10 * 1024 * 1024"
                                :maximum="99"
                                :multiple="true"
                                :drop="true"
                                :thread="3"
                                @input-filter="inputFilter">
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
                                            <i class="lo-icon lo-icon-window-close"></i>
                                        </div>
                                        <div class="dz-success-mark">
                                            <i class="lo-icon lo-icon-ok"></i>
                                        </div>
                                    </template>
                                </div>
                            </transition-group>
                        </div>
                        <div class="status" v-if="$refs.upload">
                            <span>Uploaded: {{ uploadedFilesCount }}</span> <span v-if="failedFilesCount">Failed: {{ failedFilesCount }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default" name="close" @click="cancel">Close</button>
            <button type="button" class="btn btn-primary" name="uploadImages" @click="uploadTrigger">Upload</button>
        </div>
    </v-modal>
</template>

<script>
    import PerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'
    import FileUpload from 'vue-upload-component'
    import Compressor from 'compressorjs'

    const uploadUrl = route('api.media.upload').toString()

    export default {
        components: {
            PerfectScrollbar,
            FileUpload
        },
        data() {
            return {
                uploadUrl,
                images: null,
                header: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                additionData: {},
                callback: null,
                files: [],
                imageTags: [],
                selectedTags: []
            }
        },
        computed: {
            failedFilesCount() {
                return _.filter(this.files, o => { return o.error }).length
            },
            uploadedFilesCount() {
                return _.filter(this.files, o => { return o.success }).length
            },
            allUploadedFlag() {
                return !!(this.files.length && this.files.length == (this.failedFilesCount + this.uploadedFilesCount))
            }
        },
        watch: {
            allUploadedFlag(val, old) {
                if(val) this.allUploaded()
            },
            selectedTags(val, old) {
                this.additionData = {}
                _.forEach(val, (o, k) => {
                    this.additionData[`tags[${k}][id]`] = o.id
                    this.additionData[`tags[${k}][title]`] = o.title
                })

                _.forEach(this.files, (o, k) => {
                    this.$refs.upload.update(o.id, {data: this.additionData})
                })
            }
        },
        methods: {
            allUploaded() {
                let failedIds = _(this.files)
                    .filter(o => { return o.error })
                    .map('id')

                _.delay(() => {
                    for(let id of failedIds) {
                        this.$refs.upload.remove(id)
                    }
                }, 3000)
            },
            cancel() {
                if(this.files.length)
                    this.callback()
                this.$refs.imageUploadModal.toggle(false)
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
            },
            uploadTrigger() {
                this.$refs.upload.active = true
            },
            fetchTags() {
                return axios.get(route('api.media.images.tags'))
                    .then(({data: responseData}) => {
                        this.imageTags = responseData.data
                    })
                    .catch((error) => {
                        console.error('API error', error)
                        this.tags = []
                    })
            }
        },
        mounted() {
            this.$bus.$on('open-image-upload', (params) => {
                this.fetchTags()
                this.files = []
                this.selectedTags = []
                this.$refs.imageUploadModal.toggle(true)
                this.callback = (params.cb) ? params.cb : null
            })
            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('open-image-upload')
            })
        }
    }
</script>

<style lang="scss">
    .modal-content_image-upload-modal {
        .b-dz-uploader {
            margin-bottom: 4px;
        }

        .tags {
            margin-bottom: 8px;
        }

        .status {
            padding: 5px 0;
        }

        .file-uploads.file-uploads-html5 label {
            cursor: pointer;
        }
    }
</style>
