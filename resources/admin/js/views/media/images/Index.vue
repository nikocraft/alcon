<template>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Images</h3>
                <div class="header-tools">
                    <button @click="openImageUpload" class="btn btn-block btn-primary btn-header">Upload</button>
                </div>
            </div>
            <div class="box-body" style="min-height: 600px;">
                <div class="">
                    <select class="form-control" v-model="order">
                        <option value="newer">Newer</option>
                        <option value="older">Older</option>
                    </select>
                    <select class="form-control" v-model="tagFilter">
                        <option value="">All Tags</option>
                        <option v-for="tag in imageTags" :key="tag.id" :value="tag.slug">{{tag.title}}</option>
                    </select>
                    <div style="clear: both;"></div>
                </div>

                <div class="images" v-masonry transition-duration="0.8s" item-selector=".item">
                    <div v-masonry-tile class="item" v-for="(image, index) in images" :key="index">
                        <img :src=" '/' + image.path + '/' + image.filename +'_medium.'+ image.extension" alt="" class="img-responsive">
                        <div class="edit-buttons">
                            <button type="button" v-if="image.id" @click="editImage(image)" class="btn btn-primary btn-circle">
                                <i class="lo-icon lo-icon-edit"></i>
                            </button>
                            <button type="button" @click="deleteImage(image)" class="btn btn-primary btn-circle">
                                <i class="lo-icon lo-icon-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div ref="loadingRef"></div>
                <div v-if="loading" class="spinner">
                    <div class="lds-dual-ring"></div>
                </div>
            </div>

            <v-modal ref="settingsModal"
                name="settings-modal"
                :classes="['v--modal', 'modal-content']"
                :overlay="true"
                transition="nice-modal-fade"
                :width="600"
                :height="570"
                :adaptive="true"
                :draggable="'.modal-header'"
                :widthPadding="15">
                <div class="modal-header grabbable">
                    <button type="button" class="close" aria-label="Close" @click="$refs.settingsModal.toggle(false)">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title animated">Image</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input name="imageTitle" placeholder="Title" type="text" class="form-control" v-model="editedImage.title">
                    </div>
                    <div class="form-group">
                        <label>Tags</label>
                        <v-select
                            multiple
                            taggable
                            push-tags
                            class="imagetags"
                            v-model="editedImage.tags"
                            :options="imageTags"
                            label="title"
                            max-height="200px">
                        </v-select>
                    </div>
                    <div class="form-group">
                        <label>Alt</label>
                        <input name="imageAlt" placeholder="Alt Text" type="text" class="form-control" v-model="editedImage.alt">
                    </div>
                    <div class="form-group">
                        <label>Caption</label>
                        <input name="imageCaption" placeholder="Caption" type="text" class="form-control" v-model="editedImage.caption">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="imageDescription" class="form-control" style="min-height: 70px;" v-model="editedImage.description"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="$refs.settingsModal.toggle(false)" name="cancel" type="button" class="btn btn-default">Cancel</button>
                    <button @click="saveImage" name="saveImage" type="button" class="btn btn-primary">Save</button>
                </div>
            </v-modal>

            <image-upload/>

        </div>
    </div>
</template>

<script>
    import AjaxWorker from './ajax.worker'
    import ImageUpload from './modals/ImageUpload'

    export default {
        components: {
            ImageUpload
        },
        data() {
            return {
                order: 'newer',
                tagFilter: '',
                imageTags: [],
                images: [],
                loading: false,
                page: 1,
                nextPageUrl: null,
                lastPage: 0,
                editedImage: {
                    title: '',
                    alt: '',
                    caption: '',
                    description: '',
                },

                $ajaxWorker: null
            }
        },
        watch: {
            tagFilter(val, old) {
                this.reloadImage()
            },
            order(val, old) {
                this.reloadImage()
            },
        },
        methods: {
            openImageUpload() {
                var params = new Object

                params.cb = () => {
                    this.reloadImage()
                    this.getImageTags()
                }
                this.$bus.$emit('open-image-upload', params)
            },
            editImage(image) {
                return axios.get(route('api.media.images.show', image.id))
                .then(({data: responseData}) => {
                    let { data: imageObj = {} } = responseData
                    this.$refs['settingsModal'].toggle(true)
                    this.editedImage = {
                        id: imageObj.id,
                        title: imageObj.title,
                        alt: imageObj.alt,
                        caption: imageObj.caption,
                        description: imageObj.description,
                        tags: _.map(imageObj.tags, _.partialRight(_.pick, ['id', 'title']))
                    }
                })
                .catch((error) => {
                    console.log('error')
                })
            },
            closeModal() {
                this.$refs['settingsModal'].toggle(false)
            },
            saveImage() {
                axios.put(route('api.media.images.update', this.editedImage.id), this.editedImage)
                .then((response) => {
                    this.$refs['settingsModal'].toggle(false)
                    this.getImageTags()
                })
                .catch((error) => {
                    console.log('error')
                })
            },
            deleteImage(image) {
                this.$confirm('Are you sure you want to delete this image? This action cannot be undone!', 'Delete Image', {
                    confirmButtonText: 'Delete',
                    showCancelButton: false,
                    confirmButtonClass: 'el-button--danger',
                    type: 'warning'
                }).then(() => {
                    axios.delete(route('api.media.images.destroy', image.id))
                        .then((response) => {
                            let imageIndex = this.images.indexOf(image)
                            if(imageIndex+1) this.images.splice(imageIndex, 1)

                            this.getImages()
                        })
                }).catch(() => {})
            },
            getImageTags() {
                return axios.get(route('api.media.images.tags'))
                .then(({data: responseData}) => {
                    this.imageTags = responseData.data
                })
                .catch((error) => {
                    console.log('error')
                })
            },
            reloadImage() {
                this.page = 1
                this.images = []
                this.getImages()
            },
            getImages() {
                let tagFilter = this.tagFilter

                this.$ajaxWorker.postMessage({
                    url: route('api.media.images.index', {page: this.page, order: this.order, tag: tagFilter}).toString()
                })

                this.$ajaxWorker.onmessage = (e) => {
                    this.imageTags =  e.data.tags
                    this.loading = false
                    this.lastPage = e.data.meta.last_page
                    this.nextPageUrl = e.data.links.next
                    let fetchedImages = e.data.data

                    this.images = _.unionBy(this.images, fetchedImages, 'id')
                }
            },
            handleScroll: _.debounce(function (value) {
                let clientHeight = window.innerHeight
                let scrollTop = window.scrollY
                let scrollHeight = Math.max(
                    document.body.scrollHeight, document.documentElement.scrollHeight,
                    document.body.offsetHeight, document.documentElement.offsetHeight,
                    document.body.clientHeight, document.documentElement.clientHeight
                )

                let heightDiff = scrollHeight - clientHeight

                if(heightDiff < 0) return

                let delta = heightDiff - scrollTop

                if(delta >= 0 && delta < 100) {
                    if(!this.loading && this.nextPageUrl) {
                        this.loading = true
                        this.page = this.page + 1
                        this.getImages()
                    }
                }
            }, 100)
        },
        mounted() {
            this.$ajaxWorker = new AjaxWorker()
            this.getImages()

            document.addEventListener('scroll', this.handleScroll, true)
            this.$once('hook:beforeDestroy', () => {
                this.$ajaxWorker.terminate()
                document.removeEventListener('scroll', this.handleScroll, true)
            })
        }
    }
</script>

<style lang="scss" scoped>

    select {
        float: left;
        width: 150px;
        margin-right: 5px;
    }

    .spinner {
        display: flex;
        justify-content: center;
    }

    .lds-dual-ring {
        display: flex;
        width: 64px;
        height: 64px;
    }

    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 46px;
        height: 46px;
        margin: 1px;
        border-radius: 50%;
        border: 5px solid #cef;
        border-color: #cef transparent #cef transparent;
        animation: lds-dual-ring 1.2s linear infinite;
    }

    @keyframes lds-dual-ring {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }

    .item {
        float: left;
        width: 19.2%;
        margin: .4%;

        &:hover {
            .edit-buttons {
                opacity: 1;
                background: transparent;
            }
        }
    }

    .item img {
        display: block;
        max-width: 100%;
    }

    .edit-buttons {
        position: absolute;
        right: 10px;
        top: 10px;
        opacity: 0;
    }
</style>
