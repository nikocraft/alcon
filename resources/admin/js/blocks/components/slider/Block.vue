<template>
    <div class="slider" v-bind:style="{'width': '100%','height': settings.height}">
        <transition-group tag="div" class="slider-container" name="custom-classes-transition" :enter-active-class="'animated ' + enterTransition" :leave-active-class="'animated ' + leaveTransition">
            <component-wrapper
                v-for="(block, index) in subItems"
                v-if="visible(index)"
                :type="block.type"
                :uniqueId="block.uniqueId"
                :editing-slide="editingSlide"
                :content-render-style="settings.contentRenderStyle"
                :container-preview="true"
                :settings="block.settings"
                :show-headers="false"
                :show-labels="false"
                style="height: 100%; width: 100%;"
                :config="configComputed"
                v-on:remove="removeBlock(block.uniqueId)"
                v-on:openMedia="openMediaModal"
                :storePath="storePath"
                :key="block.uniqueId">
            </component-wrapper>
        </transition-group>
        <div v-if="settings.showButtons !='no' && !editingSlide" class="slide-buttons">
            <!-- <span v-for="(slide, index) in slides" @click.stop="jumpToSlide(index)" class="button" :class="{ 'button-active': visible(index) }">.</span> -->
            <i v-for="(slide, index) in slides" :key="index" @click.stop="jumpToSlide(index)" class="lo-icon button" :class="buttonClass(index)"></i>
            <i @click.stop="addSlide()" class="lo-icon lo-icon-plus-circled" style="margin-left: 5px;"></i>
        </div>
        <span v-show="settings.showArrows !='no'" @click.stop="prev()" class="arrow arrow-left"><i class="lo-icon lo-icon-left-open"></i></span>
        <span v-show="settings.showArrows !='no'" @click.stop="next()" class="arrow arrow-right"><i class="lo-icon lo-icon-right-open"></i></span>
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import ComponentWrapper from '../../ComponentWrapper'
    import { mapGetters, mapActions } from 'vuex'
    import { getComponentByName, processSettingsConfig } from '~/utils/helpers.js'

    export default {
        mixins: [GeneralMixin],
        components: {
            ComponentWrapper
        },
        data() {
            return {
                slider: null,
                duration: 500,
                transitions: {
                    fade: 'Fade',
                    slideHorisontal: 'Slide Horisontal',
                    slideVertical: 'Slide Vertical',
                    zoomOutSlideIn: 'Zoom Out Slide In',
                },
                slideDirection: '',
                currentSlide: 0,
                previousSlide: 0,
                editingSlide: false,
                layersModalOpen: false,
                heightResponsive: false,
                insertImage: undefined,
            }
        },
        computed: {
            configComputed() {
                let config = {
                    padding: this.settings.padding
                }

                return config
            },
            enterTransition() {
                var transition
                switch (this.settings.transition) {
                    case 'fade':
                        transition = 'fadeIn'
                    break;

                    case 'slideHorisontal':
                        if(this.slideDirection == 'next')
                            transition = 'slideInRight'
                        else if(this.slideDirection == 'prev')
                            transition = 'slideInLeft'
                    break;

                    case 'slideVertical':
                        if(this.slideDirection == 'next')
                            transition = 'slideInUp'
                        else if(this.slideDirection == 'prev')
                            transition = 'slideInDown'
                    break;

                    case 'zoomOutSlideIn':
                        if(this.slideDirection == 'next')
                            transition = 'slideInRight'
                        else if(this.slideDirection == 'prev')
                            transition = 'slideInLeft'
                    break;

                    case 'rubberBand':
                        transition = 'fadeIn'
                    break;
                    default:
                }

                return transition
            },
            leaveTransition() {
                var transition
                switch (this.settings.transition) {
                    case 'fade':
                        transition = 'fadeOut'
                    break;

                    case 'slideHorisontal':
                        if(this.slideDirection == 'next') {
                            transition = 'slideOutLeft'
                        } else if(this.slideDirection == 'prev') {
                            transition = 'slideOutRight'
                        }
                    break;

                    case 'slideVertical':
                        if(this.slideDirection == 'next')
                            transition = 'slideOutUp'
                        else if(this.slideDirection == 'prev')
                            transition = 'slideOutDown'
                    break;

                    case 'zoomOutSlideIn':
                        transition = 'zoomOut'
                    break;

                    case 'rubberBand':
                        transition = 'rubberBand'
                    break;
                    default:
                }

                return transition
            },
            modalTitle() {
                return this.settings.blockTitle + ' Settings'
            },
            settings: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
                }
            },
            slides: {
                get() {
                    return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            },
            subItems: {
                get() {
                    return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
                },
                set(object) {
                    this.updateItemsList({list: _.map(object, 'uniqueId'), id: this.uniqueId})
                }
            }
        },
        watch: {
            currentSlide(val, old) {
                this.previousSlide = val
            },
            slideDirection(val, old) {
                //
            },
            settings: {
                handler(newVal) {
                    this.updateItemSettings({settings: newVal, uniqueId: this.uniqueId})
                },
                deep: true
            },
            insertImage: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.getCurrentSlideUniqueId()})
                },
                deep: true
            }
        },
        methods: {
            getCurrentSlideUniqueId() {
                let uniqueId
                for (var i = 0; i < this.subItems.length; i++) {
                    if(i == this.currentSlide) {
                        uniqueId = this.subItems[i].uniqueId
                        break
                    }
                }

                return uniqueId
            },
            openMediaModal() {
                var mode = 'insertImage'
                var params = new Object
                // params.imageId = this.imageId
                params.id = this._uid

                params.cb = (image) => {
                    this.insertImage = image.original
                }

                this.$bus.$emit('open-media-modal', mode, params)
            },
            responsiveSwitch(val) {
                this.settings[val] = !this.settings[val]
            },
            zindex(val, old) {
                if(val == this.currentSlide)
                    return '1'
            },
            slideMode(val) {
                this.editingSlide = val
            },
            jumpToSlide(index) {
                this.currentSlide = index
                if(this.currentSlide > this.previousSlide)
                    this.slideDirection = 'next'
                else if(this.currentSlide < this.previousSlide)
                    this.slideDirection = 'prev'
            },
            editSlide(slide) {
                slide.mode = 'edit'
                this.editingSlide = !this.editingSlide
            },
            visible(val) {
                return val == this.currentSlide

            },
            buttonClass(val) {
                return (val == this.currentSlide)
                    ? 'lo-icon-circle'
                    : 'lo-icon-circle-empty'
            },
            addBlock(compName = undefined) {
                let comp = getComponentByName(compName)
                if (!comp) {
                    console.error('Wrong component\'s name')
                    return false
                }

                let customSettings = processSettingsConfig(compName)

                this.addItem({
                    type: compName,
                    title: customSettings.blockTitle.default,
                    parentId: this.getCurrentSlideUniqueId(),
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            },
            openComponentsLibModal() {
                let params = {
                    mode: 'standard',
                    componentName: 'Slide',
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
                    }
                }

                this.$bus.$emit('open-blocks-modal', params)
            },
            openSettingsModal() {
                let mode = 'open-settings-modal'
                let params = {
                    uniqueId: this.uniqueId,
                    type: this.$options.name
                }
                this.$bus.$emit('open-settings-modal', mode, params)
            },
            openSlideSettingsModal() {
                let mode = 'open-settings-modal'
                let params = {
                    uniqueId: this.getCurrentSlideUniqueId(),
                    type: 'Slide'
                }
                this.$bus.$emit('open-settings-modal', mode, params)
            },
            next() {
                this.slideDirection = 'next'
                this.currentSlide = this.currentSlide + 1

                if(this.currentSlide > (this.slides.length-1))
                    this.currentSlide = 0
            },
            prev() {
                this.slideDirection = 'prev'
                this.currentSlide = this.currentSlide - 1

                if(this.currentSlide < 0)
                    this.currentSlide = (this.slides.length-1)
            },
            addSlide() {
                if(this.slides.length < 10) {
                    let compName = 'Slide'
                    let comp = getComponentByName(compName)
                    let customSettings = processSettingsConfig(compName)

                    this.addItem({
                        type: compName,
                        title: 'Slide',
                        parentId: this.uniqueId,
                        settingsConfig: customSettings,
                        settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                    })

                    this.currentSlide = this.slides.length - 1
                }
            },
            removeBlock() {
                this.$emit('remove', this.uniqueId)
            }
        },
        mounted() {
            if(this.slides.length == 0)
                this.addSlide()
        }
    }
</script>

<style scoped lang="scss">
    .slider {
        cursor: pointer;
        border: 1px dashed #add8e640;
        min-height: 200px;
        position: relative;
        overflow: hidden;
        .lo-icon {
            cursor: pointer;
            font-size: 16px;
            margin-right: 0;
            // color: #b8c7ce;
        }
        .slider-container div {
            position: absolute;
            overflow: hidden;
        }
    }

    .add-slide-container {
        top: 20px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        position: absolute;
        text-align: center;
    }

    .arrow {
        border: 0px solid rgba(173, 216, 230, 0.21);
        margin: 0;
        cursor: pointer;
        border-radius: 0%;
        background-color: rgba(42, 42, 42, 0.7);
        color: #b8c7ce;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        justify-content: center;
        align-items: center;
        height: 35px;
        width: 35px;
        border-radius: 25px;

        &:hover {
            background-color: rgba(34, 34, 34, 0.8);
            border: 0px solid rgba(86, 132, 219, 0.18);
        }
    }

    .arrow i {
        font-size: 12px !important;
    }

    .arrow-left {
        left: 16px;
        padding: unset;
    }

    .arrow-right {
        right: 16px;
        padding: unset;
    }

    .slide-controls {
        margin: 0;
        position: absolute;
        top: 5px;
        width: 100%;
        text-align: right;
        padding: 10px;
        pointer-events: none;
    }

    .control {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 10px 12px;
        margin: 1px;
        cursor: pointer;
        border-radius: 50%;
        background-color: rgba(43, 92, 147, 0.35);
        color: #b8c7ce;
        text-align: center;
        font-size: 15px;
        pointer-events: all;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }
    }


    .add-slide {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 5px 12px;
        margin: 0;
        cursor: pointer;
        border-radius: 50%;
        background-color: rgba(43, 92, 147, 0.65);
        color: #b8c7ce;
        font-size: 20px;
        pointer-events: all;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }
    }

    .add-slide i {
        font-size: inherit !important;
    }

    .slide-buttons {
        position: absolute;
        bottom: 5px;
        left: 0;
        right: 0;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        font-size: 15px;
    }

    .button {
        color: white;
        margin: 0px 2px;
    }

    .button-active {
        border: 6px solid white;
        background-color: transparent;
    }

    .header {
        opacity: 1
    }
</style>
