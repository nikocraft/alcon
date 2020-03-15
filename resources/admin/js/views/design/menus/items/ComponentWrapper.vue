<template>
    <div class="menuItem" ref="wrapper-container">
        <div class="menu-item-header" style="cursor: pointer;" @click="showContentToggle = !showContentToggle" :style="marginStyle">
            <i class="lo-icon lo-icon-move dd-handle" title="Reorder"></i>
            <!-- <i class="lo-icon lo-icon-cog" title="Settings" @click="openSettingsModal()"></i> -->
            {{ content.title }}
            <div class="pull-right">
                <i @click="removeMenuItem" class="lo-icon lo-icon-trash-empty" title="Delete"></i>
            </div>
        </div>

        <div class="content-block-body" style="height: 100%; width: 100%;">
            <component
                v-bind:is="component"
                v-bind:content.sync="realContent"
                :show-content="showContentToggle"
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
    import { getComponentByName, processSettingsConfig, getComponentSetting } from '../utils/helpers.js'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [GeneralMixin],
        props: {
            containerClass: {type: String, default: 'content-block'}
        },
        data() {
            return {
                component: undefined,
                realContent: this.$store.getters['menus/editor/itemContent'](this.uniqueId) || undefined,
                showContentToggle: false,
            }
        },
        computed: {
            marginStyle () {
                if(this.type == 'Container')
                    return 'margin-top: 5px;'
            },
            blockType() {
                return this.$store.getters['menus/editor/itemType'](this.uniqueId)
            },
            settings() {
                return this.$store.getters['menus/editor/itemSettings'](this.uniqueId)
            },
            content() {
                return this.$store.getters['menus/editor/itemContent'](this.uniqueId)
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
            ...mapActions('menus/editor', [
                'updateItemSettings',
                'updateItemContent',
                'addItem',
                'removeItem',
                'updateItemsList'
            ]),
            removeMenuItem() {
                // this.$emit('remove', this.uniqueId)
                this.removeItem({
                    id: this.uniqueId
                })
            },
            // openSettingsModal() {
            //     let mode = 'open-settings-modal'
            //     let comp = getComponentByName(this.type, 'settings')
            //     let params = {
            //         uniqueId: this.uniqueId,
            //         type: this.type,
            //         customComponent: comp
            //     }
            //     this.$bus.$emit('open-settings-modal', mode, params)
            // },
            addBlock(compName = undefined) {
                let comp = getComponentByName(compName)
                if(!comp) {
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
            },
        },
        created() {
            let comp = getComponentByName(this.type)
            if(!comp) {
                console.log('Wrong component\'s name')
                return false
            }
            this.component = comp
        }
    }
</script>

<style lang="scss">
    .content-block {
        position: relative;
    }
    .menu-item-header {
        position: relative;
        padding-left: 40px !important;
    }
    .dd-handle {
        padding: 15px 12px;
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
