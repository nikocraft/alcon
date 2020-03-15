<template>
    <div :class="containerClassList" ref="wrapper-container" :style="{'margin': this.settings.margin}">
        <div v-show="showContent" class="content-block-body" style="height: 100%; width: 100%;">
            <component
                v-bind:is="component"
                :show-headers="showHeaders"
                v-bind:content.sync="realContent"
                :uniqueId="uniqueId"
                :config="config"
                :type="type"
                v-on="$listeners"
            />
        </div>
    </div>
</template>

<script>
    import GeneralMixin from './mixins/GeneralMixin'
    import { getComponentByName, processSettingsConfig, getComponentSetting } from '~/utils/helpers.js'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        props: {
            containerClass: {type: String, default: 'content-block'}
        },
        data () {
            return {
                component: undefined,
                realContent: this.$store.getters.itemContent(this.uniqueId) || undefined,
            }
        },
        computed: {
            marginStyle () {
                if(this.type == 'Container')
                    return 'margin-top: 5px;'
            },
            containerClassList () {
                return [
                    this.containerClass,
                    this.containerClass+`_${this.type.toLowerCase()}`
                ]
            },
            blockType () {
                return this.$store.getters.itemType(this.uniqueId)
            },
            settings () {
                return this.$store.getters.itemSettings(this.uniqueId)
            },
            content () {
                return this.$store.getters.itemContent(this.uniqueId)
            },
            showFooter () {
                return getComponentSetting(this.type, 'showFooter')
            },
            topToolbar () {
                return getComponentSetting(this.type, 'topToolbar') || []
            }
        },
        watch: {
            settings: {
                handler(val) {
                    this.updateItemSettings({settings: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
            realContent: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
        },
        methods: {
            ...mapActions([
                'updateItemSettings',
                'updateItemContent',
                'addItem',
                'removeItem',
                'updateItemsList'
            ]),
            removeBlock() {
                this.$emit('remove', this.uniqueId)
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
                    parentId: this.uniqueId,
                    settingsConfig: customSettings,
                    settings: _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)
                })
            }
        },
        created () {
            let comp = getComponentByName(this.type)
            if (!comp) {
                console.log('Wrong component\'s name')
                return false
            }
            this.component = comp
        }
    }
</script>

<style lang="scss">
    //
</style>
