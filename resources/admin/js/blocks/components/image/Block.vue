<template>
    <div class="image-block-body" style="display: flex;" :style="imageBlockStyles">
        <div>
            <img v-if="image.id" :src="imageUrl" @click.stop="openMediaModal" :class="[settings.imageResponsive ? 'img-responsive' : '' , settings.customClass]" :style="imgStyles">
            <div v-else @click.stop="openMediaModal" class="text-center no-image" style="width: 100%;">
                <h3 style="height: 30px;">Image</h3>
            </div>
        </div>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        data() {
            return {
                image: {
                    title: '',
                    caption: '',
                    link: '',
                }
            }
        },
        computed: {
            imageBlockStyles() {
                let styleObj = {
                    padding: this.settings.padding,
                    backgroundColor: this.settings.backgroundColor,
                    justifyContent: this.settings.imagePosition,
                }
                return styleObj
            },
            imgStyles() {
                let styleObj = {
                    'border': this.settings.imageBorder,
                    'border-radius': this.settings.imageBorderRadius,
                    'width': this.settings.imageResponsive ? 'auto' : this.settings.width
                }
                return styleObj
            },
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
                }
            },
            settings: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
                }
            },
            subItems() {
                return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
            },
            content() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            },
            imageUrl() {
                return this.getCorrectImageSize()
            }
        },
        watch: {
            image: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            }
        },
        methods: {
            openMediaModal() {
                var mode = 'insertImage'
                var params = new Object
                // params.imageId = this.imageId
                params.id = this._uid

                params.cb = (image) => {
                    let tmpImg = {
                        title: '',
                        caption: '',
                        id: image.id,
                        filename: image.filename,
                        extension: image.extension,
                        path: image.path
                    }

                    this.image = {
                        ...this.image,
                        ...tmpImg
                    }
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            getCorrectImageSize() {
                if(this.image.id) {
                    switch (this.settings.imageSize) {
                        case 'original':
                            return '/' + this.image.path + this.image.filename + '.' + this.image.extension
                        break;

                        case 'large':
                            return '/' + this.image.path + this.image.filename + '_large.' + this.image.extension
                        break;

                        case 'medium':
                            return '/' + this.image.path + this.image.filename + '_medium.' + this.image.extension
                        break;

                        case 'grid_large':
                            return '/' + this.image.path + this.image.filename + '_grid_large.' + this.image.extension
                        break;

                        case 'grid_medium':
                            return '/' + this.image.path + this.image.filename + '_grid_medium.' + this.image.extension
                        break;

                        case 'thumb_large':
                            return '/' + this.image.path + this.image.filename + '_thumb_large.' + this.image.extension
                        break;

                        case 'thumb':
                            return '/' + this.image.path + this.image.filename + '_thumb.' + this.image.extension
                        break;

                        default:
                            return '/' + this.image.path + this.image.filename + '_grid_medium.' + this.image.extension
                    }
                }
            }
        },
        mounted() {
            if(this.content)
                this.image = this.content
        }
    }
</script>

<style scoped lang="scss">
    .no-image {
        padding: 20px;
        border: 1px dashed rgba(0, 0, 0, 0.2);
        background-color: rgba(0,0,0, 0.12);
        cursor: pointer;
    }

    img {
        cursor: pointer;
    }
</style>
