<template>
    <div style="position: relative;">
        <div v-bind:style="vimeoStyles">
            <iframe v-if="embed" :id="this.uniqueId" width="100%" height="100%" :src="embed" style="border: 0; position: absolute; left: 0; top: 0;"></iframe>
        </div>
        <input type="text" class="form-control" v-model="video" placeholder="Insert Vimeo Url" style="margin-top: 3px;" :ref="'vimeo-input-'+uniqueId">
    </div>
</template>

<script>
    import GeneralMixin from '../../mixins/GeneralMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        props: {
            overlayed: {type: Boolean, default: false}
        },
        data() {
            return {
                video: ''
            }
        },
        computed: {
            vimeoStyles() {
                let styleObj = {
                    display: 'block',
                    cursor: 'pointer',
                    padding: '28.25%',
                    position: 'relative',
                }
                return styleObj
            },
            blockType: {
                get() {
                    return this.$store.getters[`${this.storePath}/itemType`](this.uniqueId)
                }
            },
            embed() {
                return this.video
                    ? "https://player.vimeo.com/video/" + this.video.substr(this.video.indexOf(".com/") + 5)
                    : undefined
            },
            modalTitle() {
                return this.blockTitle + ' Settings'
            },
            settings() {
                return this.$store.getters[`${this.storePath}/itemSettings`](this.uniqueId)
            },
            subItems() {
                return this.$store.getters[`${this.storePath}/items`](this.uniqueId)
            },
            content() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            },
        },
        watch: {
            video(val, old) {
                this.updateItemContent({content: val, uniqueId: this.uniqueId})
            },
            content: {
                handler(val, old) {
                    this.video = val
                },
                immediate: true
            }
        },
        methods: {
            setVideo() {
                this.$refs['settingsModal'].toggle(true)
            }
        },
        mounted() {
            if(this.freshComponent) {
                this.$refs['vimeo-input-' + this.uniqueId].focus()
            }
        }
    }
</script>



<style scoped lang="scss">
    .video-buttons {
        margin: 0;
        position: absolute;
        top: 5px;
        display: flex;
        justify-content: center;
        width: 100%;
        padding: 10px;
        pointer-events: none;
    }

    .set-video {
        border: 1px solid rgba(173, 216, 230, 0.5);
        padding: 10px 12px;
        margin: 1px;
        cursor: pointer;
        border-radius: 50%;
        background-color: rgba(43, 92, 147, 0.65);
        color: #b8c7ce;
        text-align: center;
        font-size: 15px;
        pointer-events: all;

        &:hover {
            background-color: rgba(43, 92, 147, 0.85);
            border: 1px solid rgba(173, 216, 230, 0.8);
        }
    }
</style>
