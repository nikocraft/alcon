<template>
    <div>
        <div class="images" v-bind:style="{'padding': settings.padding, 'margin-left': '-'+settings.spaceBetweenImages, 'margin-right': '-'+settings.spaceBetweenImages, 'background-color': settings.backgroundColor}">
            <div class="image-block" v-for="(image, index) in imageList"
                        v-bind:style="{
                            'border': '0px dashed rgba(0,0,0, 0.2)',
                            'justify-content': 'center',
                            'width': imageWidth,
                            'height': settings.imageHeight,
                            'margin': settings.spaceBetweenImages}">
                <!-- IMAGE -->
                <div class="image" style="width: 100%; height: 100%;">
                    <!-- SHOW IMAGE -->
                        <img v-if="image.id" @click="openMediaModal(image.uniqueId)" style="margin-left: auto; margin-right: auto;" :src="imageUrl(image)" :class="[settings.imageResponsive ? 'img-responsive' : '']"
                            v-bind:style="{
                                'cursor': 'pointer',
                                'height': settings.imageHeight,
                                'width': '100%',
                                'object-fit': 'cover',
                                'border': settings.imageBorder,
                                'border-radius': settings.imageBorderRadius}">

                        <!-- NO IMAGE? SHOW IMAGE TEXT -->
                        <div v-else @click="openMediaModal(image.uniqueId)" class="text-center"
                            style="cursor: pointer; display: flex; flex-direction: column; justify-content: center; height: 100%;"
                            :style="{'background-color': 'rgba(0,0,0, 0.15)', 'padding': noImagePadding}">
                            <h4>Image</h4>
                        </div>

                    <div class="edit-buttons">
                        <button type="button" v-if="image.id" @click="editImage(image)" class="btn btn-primary btn-circle">
                            <i class="lo-icon lo-icon-edit"></i>
                        </button>
                        <button type="button" @click="removeImage(image.uniqueId)" class="btn btn-primary btn-circle">
                            <i class="lo-icon lo-icon-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div style="clear: both;"></div>

        <v-modal ref="settingsModal"
            name="settings-modal"
            :classes="['v--modal', 'modal-content']"
            :overlay="true"
            transition="nice-modal-fade"
            :width="600"
            :height="300"
            :adaptive="true"
            :draggable="'.modal-header'"
            :widthPadding="15">
            <div class="modal-header grabbable">
                <button type="button" class="close" aria-label="Close" @click="$modal.hide('settings-modal')">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title animated">{{ modalTitle }}</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Title</label>
                    <input placeholder="Enter Title" type="text" class="form-control" v-model="editedImage.title">
                </div>
                <div class="form-group">
                    <label>Alt</label>
                    <input placeholder="Enter Alt Text" type="text" class="form-control" v-model="editedImage.alt">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" @click="saveImage">Save</button>
            </div>
        </v-modal>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        data() {
            return {
                modalTitle: 'Image',
                editedImage: {
                    title: '',
                    alt: ''
                },
                imageList: [],
                maxImages:250,
                minImages: 1,
                imageSizeOptions: [
                    { text: 'Original', value: 'original' },
                    { text: 'Large', value: 'large' },
                    { text: 'Medium', value: 'medium' },
                    { text: 'Grid Large', value: 'grid_large' },
                    { text: 'Grid Medium', value: 'grid_medium' },
                    { text: 'Thumbnail Large', value: 'thumb_large' },
                    { text: 'Thumbnail Small', value: 'thumb' },
                ],
                cssSettingsOpen: false,
                blockSettingsOpen: false,
                imagesSettingsOpen: false,
            }
        },
        computed: {
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
                }
            },
            settings() {
                return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
            },
            imageWidth() {
                let offset = this.settings.spaceBetweenImages.replace(/[^0-9.]/g,"") * 2
                let value = Math.floor(100 * 100 / this.settings.columnsPerRow ) / 100
                this.settings.imageWidth = `calc(${value}% - ${offset}px)`
                return this.settings.imageWidth
            },
            imageAmount() {
                return this.settings.imageAmount
            },
            content() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            },
            noImagePadding() {
                if(this.settings.columnsPerRow < 5)
                    return '20px'
                else if(this.settings.columnsPerRow < 6)
                    return '20px'
                else
                    return '10px'
            }
        },
        watch: {
            imageList: {
                handler(val) {
                    this.settings.imageAmount = this.imageList.length
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
            settings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
            'settings.imageSize'(val, old) {
                for(var i = 0; i < this.imageList.length; i++) {
                    this.imageUrl(this.imageList[i])
                }
            },
            imageAmount(val, old) {
                if(val > old) {
                    this.addImages(val)
                }
                else {
                    if(this.imageList.length != val)
                        this.removeImages(this.imageList.length - val)
                }
            },
        },
        methods: {
            imageUrl(image) {
                switch (this.settings.imageSize) {
                    case 'original':
                        return '/' + image.path + image.filename + '.' + image.extension
                    break;

                    case 'large':
                        return '/' + image.path + image.filename + '_large.' + image.extension
                    break;

                    case 'medium':
                        return '/' + image.path + image.filename + '_medium.' + image.extension
                    break;

                    case 'grid_large':
                        return '/' + image.path + image.filename + '_grid_large.' + image.extension
                    break;

                    case 'grid_medium':
                        return '/' + image.path + image.filename + '_grid_medium.' + image.extension
                    break;

                    case 'thumb_large':
                        return '/' + image.path + image.filename + '_thumb_large.' + image.extension
                    break;

                    case 'thumb':
                        return '/' + image.path + image.filename + '_thumb.' + image.extension
                    break;

                    default:
                        return '/' + image.path + image.filename + '_grid_medium.' + image.extension
                }
            },
            openMediaModal(uniqueId) {
                let mode = 'insertImage'
                let params = new Object
                // params.imageId = this.selectedImage
                this.imageInput = uniqueId

                params.cb = (image) => {
                    for(var i = 0; i < this.imageList.length; i++) {
                        let item = this.imageList[i]
                        if(item.uniqueId == uniqueId) {
                            // item.object = image
                            item.filename = image.filename
                            item.id = image.id
                            item.extension = image.extension
                            item.path = image.path
                            item.id = image.id
                            break
                        }
                    }
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            removeImages(val) {
                for(var i = 0; i < val; i++) {
                    this.imageList.pop()
                }
                // this.imageList.splice(-1, val)
            },
            saveImage() {
                this.$modal.hide('settings-modal')
            },
            editImage(image) {
                this.editedImage = image
                this.$modal.show('settings-modal')
            },
            removeImage(uniqueId) {
                // _.remove(this.imageList, ['uniqueId', uniqueId])
                this.imageList = _.reject(this.imageList, ['uniqueId', uniqueId])
            },
            addImages(val) {
                let toAdd = val - this.imageList.length
                for(var i = 0; i < toAdd; i++) {
                    let image = new Object()
                    image.uniqueId = this.uniqueId+'-image-' + this.imageList.length
                    image.id = null
                    image.title = ''
                    image.alt = ''
                    this.imageList.push(image)
                }
            },
            addImage() {
                if(this.imageList.length < this.maxImages) {
                    let image = new Object()
                    image.uniqueId = this.uniqueId+'-image-' + this.imageList.length
                    image.id = null
                    image.title = ''
                    image.alt = ''
                    this.imageList.push(image)
                    this.settings.imageAmount = this.imageList.length
                }
            },
            processContent(content) {
                let self = this
                _.forEach(content, function(item) {
                    let image = new Object()
                    image.uniqueId = self.uniqueId+'-image-' + self.imageList.length
                    image.id = item.id
                    image.title = item.title
                    image.alt = item.alt
                    image.filename = item.filename
                    image.extension = item.extension
                    image.path = item.path
                    self.imageList.push(image)
                    self.settings.imageAmount = self.imageList.length
                })
                // this.imageAlt = content.alt
            }
        },
        mounted() {
            if(this.content)
                this.processContent(this.content)
            else
                this.addImages(this.settings.imageAmount)
        }
    }
</script>

<style lang="scss" scoped>

    .btn-circle {
        width: 36px;
        height: 36px;
        text-align: center;
        padding: 6px 0;
        font-size: 14px;
        line-height: 1.42;
        border-radius: 50%;
    }

    .content-block_images {
        .content-block-body {
            min-height: 122px;
        }

        .h1, h1, .h2, h2, .h3, h3, .h4, h4 {
            min-height: 1.3em;
        }

        .title {
            margin-bottom: 5px;
            margin-top: 5px;
            padding-top: 5px;
            padding-bottom: 5px;

            &:hover {
                background-color: rgba(0,0,0, 0.05);
            }
        }

        .images {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
        }

        .content-block {
            textarea {
               resize: none;
               height: 60px;
            }
        }

        .image {
            textarea {
               resize: none;
               min-height: 4em;
               max-height: 7em;
               padding-left: 2px;
            }
        }

        .remove-image {
            padding: 0;
            border: 0;
            position: absolute;
            right: 5px;
            top: 10px;
            opacity: 0;
        }

        .remove-image span {
            opacity: 1;
            background: rgb(151, 26, 26);
            padding: 0px 7px;
            font-size: 18px;
            border: 0px;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-shadow: 0 0 0;
        }

        .image {
            // display: flex;
            // justify-content: center;

            &:hover {
                .edit-buttons {
                    opacity: 1;
                    background: transparent;
                }
            }
        }

        .image-block {
            position: relative;
        }

        .edit-buttons {
            position: absolute;
            right: 10px;
            top: 10px;
            opacity: 0;
        }
    }
</style>
