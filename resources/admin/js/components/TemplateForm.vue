<template>
    <v-modal
        name="template-form"
        :classes="['v--modal', 'modal-content']"
        :overlay="true"
        transition="nice-modal-fade"
        :clickToClose="false"
        :width="400"
        :height="600"
        :delay="100"
        :adaptive="false"
        :resizable="false"
        @closed="closed"
    >
        <div class="modal-header grabbable">
            <button type="button" class="close" aria-label="Close" @click="$modal.hide('template-form')">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title animated">{{ modalTitle }}</h4>
        </div>
        <perfect-scrollbar class="modal-body" :swicher="bodyScrollbarShown">
            <div class="form-group">
                <label>Template name</label>
                <input
                    v-model="form.name"
                    v-validate="'required'"
                    :class="{'form-control':true, 'input': true, 'is-danger': errors.has('name') }"
                    name="name"
                    type="text"
                />
                <span v-show="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
            </div>
            <div class="form-group">
                <label>Tags</label>
                <v-select
                    multiple
                    taggable
                    push-tags
                    v-model="form.tags"
                    :options="tagItems"
                    label="title"
                    max-height="100px">
                </v-select>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea v-model="form.description" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label>Screenshot</label>
                <div
                    :class="{
                        'b-screenshot-uploader': true,
                        'b-screenshot-uploader_drop-active': $refs.upload && $refs.upload.dropActive,
                        'b-screenshot-uploader_filled': files.length
                    }"
                    @click.self="triggerBrowse"
                >
                    <file-upload
                        class="b-screenshot-uploader_dnd-box"
                        ref="upload"
                        :headers="header"
                        v-model="files"
                        :post-action="screenshotUploadUrl"
                        accept="image/*"
                        extensions="gif,jpeg,jpg,png"
                        :size="0"
                        :multiple="false"
                        :drop="true"
                        @input-file="inputFile"
                        @input-filter="inputFilter"
                    >
                        <i class="lo-icon lo-icon-upload-cloud"></i><br>
                        <div class="b-screenshot-uploader_text">
                            Drop file here or <em>click to upload</em>
                        </div>
                    </file-upload>

                    <transition-group
                        v-if="files && files.length"
                        name="el-fade-in"
                        tag="div"
                        class="b-screenshot-uploader_files"
                        @click="triggerBrowse"
                    >
                        <div v-for="(file, index) in files" :key="file.id"
                            class="b-screenshot-uploader_file"
                            :class="{
                                'b-screenshot-uploader_file_error': file.error,
                                'b-screenshot-uploader_file_processing': file.active
                            }"
                            @click="triggerBrowse"
                        >
                            <!-- <div class="b-screenshot-uploader_file-preview" style="width: 74px; height: 74px"
                                :style="{'background-image': file.blob ? `url('${file.blob}')` : null}"
                            >
                            </div> -->
                            <img :src="file.blob ? file.blob : null"  class="b-screenshot-uploader_img" />
                            <div class="b-screenshot-uploader_file-progress">
                                <span class="b-screenshot-uploader_file-upload"
                                    :style="{width: `${file.progress}%`}"
                                ></span>
                            </div>
                        </div>
                    </transition-group>
                </div>
            </div>
        </perfect-scrollbar>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="saveTrigger" :disabled="false">Save</button>
            <button type="button" class="btn btn-primary" @click="$modal.hide('template-form')">Cancel</button>
        </div>
    </v-modal>
</template>

<script>
    import { mapActions, mapState } from 'vuex'
    import PerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'
    import FileUpload from 'vue-upload-component'

    const screenshotUploadUrl = route('api.design.templates.uploadscreenshot')

    export default {
        components: {
            PerfectScrollbar,
            FileUpload
        },
        props: {
            storeType: {type: String, default: 'content'},
        },
        data() {
            return {
                modalTitle: 'Save as template',
                selectedId: null,
                form: {
                    name: '',
                    tags: undefined,
                    description: '',
                    screenshot: ''
                },

                // uploader
                screenshotUploadUrl: screenshotUploadUrl,
                header: {'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content},
                files: [],

                bodyScrollbarShown: true
            }
        },
        computed: {
            // ...mapState(`${this.storeType}/templates`, {
            //     tagItems: state => state.tags
            // }),
            tagItems() {
                return this.$store.state[this.storeType]['templates']['tags']
            },
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
            }
        },
        methods: {
            ...mapActions({
                saveAsTemplate(dispatch, payload) {
                    return dispatch(`${this.storeType}/editor/saveAsTemplate`, payload)
                },
                resetTemplates(dispatch, payload) {
                    return dispatch(`${this.storeType}/templates/init`, payload)
                },
                fetchTags(dispatch, payload) {
                    return dispatch(`${this.storeType}/templates/fetchTags`, payload)
                }
            }),
            reinitForm() {
                this.form = {
                    name: '',
                    tags: undefined,
                    description: '',
                    screenshot: ''
                }
                this.files = []
                this.$refs.upload && this.$refs.upload.clear()
            },
            closed() {
                this.reinitForm()
            },
            saveTrigger() {
                this.$validator.validateAll().then( result => {
                    if(result) {
                        this.save()
                    }
                })
            },
            save() {
                this.saveAsTemplate({
                    id: this.selectedId,
                    form: this.form
                }).then(() => {
                    this.fetchTags()
                    this.resetTemplates()
                    this.$modal.hide('template-form')
                })
            },

            inputFile(newFile, oldFile, prevent) {
                // Automatic upload
                if(Boolean(newFile) !== Boolean(oldFile) || oldFile.error !== newFile.error) {
                    if(!this.$refs.upload.active) {
                        this.$refs.upload.active = true
                    }
                }
            },
            inputFilter(newFile, oldFile, prevent) {
                if(newFile && !oldFile) {
                    // Create the 'blob' field for thumbnail preview
                    newFile.blob = ''
                    let URL = window.URL || window.webkitURL
                    if(URL && URL.createObjectURL) {
                        newFile.blob = URL.createObjectURL(newFile.file)
                    }
                }
            },
            triggerBrowse() {
                let el = this.$refs.upload.$el.querySelector("[name='file']")
                el && el.click()
            },
            allUploaded() {
                let screenshotUrl = this.files[0].response || ''
                this.form.screenshot = screenshotUrl
            },
            tagsSelectVisibleChange(visibility) {
                this.bodyScrollbarShown = !visibility
            }
        },
        mounted() {
            this.$bus.$on('open-template-form-modal', ({id}) => {
                this.fetchTags()

                this.selectedId = id || null
                this.$modal.show('template-form')
            })
            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('open-template-form-modal')
            })
        }
    }
</script>

<style lang="scss">
    .b-screenshot-uploader {
        position: relative;
        display: inline-block;
        text-align: center;
        cursor: pointer;
        outline: none;
        width: 100%;
        &_dnd-box {
            background-color: rgba(0,0,0,.2);
            border: 1px dashed #d9d9d9;
            border-radius: 2px;
            box-sizing: border-box;
            width: 100%;
            height: 90px;
            margin: 0;
            text-align: center;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            vertical-align: top;
            &:hover {
                border-color: #409EFF;
            }
            i.lo-icon {
                font-size: 32px;
                color: #c0c4cc;
                margin: 18px 0 4px;
                line-height: 28px;
            }
        }
        &_text {
            color: #b8c7ce;
            font-size: 14px;
            text-align: center;
            font-weight: normal;
            em {
                color: #409eff;
                font-style: normal;
            }
        }
        &_files {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: auto;
            background-color: rgb(40, 51, 57);
            padding: 8px;
        }
        &_file {
            position: relative;
            width: 100%;
            display: inline-block;
            vertical-align: top;
            min-height: 70px;
        }
        &_file-preview {
            border-radius: 0;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
        }
        &_img {
            max-width: 100%;
            height: auto;
        }
        &_file-progress {
            opacity: 0;
            z-index: 1000;
            pointer-events: none;
            position: absolute;
            height: 4px;
            bottom: 0;
            margin-top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.9);
            -webkit-transform: scale(1);
            border-radius: 1px;
            overflow: hidden;
        }
        &_file-progress &_file-upload {
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 0;
            background: rgba(60, 141, 188, 0.9);
        }
    }
</style>
