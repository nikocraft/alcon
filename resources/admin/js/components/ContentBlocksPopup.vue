<template>
    <v-modal ref="componentsLibModal"
        name="components-lib-modal"
        :classes="['v--modal', 'modal-content', 'modal-content_auto-responsive', 'modal-content_content-blocks']"
        :overlay="true"
        transition="nice-modal-fade"
        :clickToClose="false"
        :customWidth="true"
        :height="'96%'"
        :minHeight="300"
        :delay="100"
        :adaptive="true"
        :resizable="false"
        :widthPadding="15"
        @closed="closed">
        <div class="modal-header grabbable">
            <button type="button" class="close" aria-label="Close" @click="$modal.hide('components-lib-modal')">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title animated">{{ modalTitle }}</h4>
        </div>
        <div class="modal-body">
            <el-tabs v-model="activeTabName" class="el-tabs_elastic-content">
                <el-tab-pane label="Components" name="components">
                    <perfect-scrollbar class="b-comp-list">
                        <div
                            v-for="(item, index) in standardBlocksList"
                            @click="selectItem('block', item.block)"
                            :key="index"
                            :class="['b-comp-list_item', { 'selected': selectedItem && (selectedItem.identifier == item.block) }]"
                        >
                            {{item.title}}
                        </div>
                        <div class="clearfix"></div>
                        <!-- <hr>
                        <div
                            v-if="tpBlocksList && tpBlocksList.length"
                            v-for="item in tpBlocksList"
                            @click="selectItem('block', item.block)"
                            :class="['b-comp-list_item', { 'selected': selectedItem && (selectedItem.identifier == item.block) }]">
                            {{item.title}}
                        </div> -->
                    </perfect-scrollbar>
                </el-tab-pane>

                <!-- <el-tab-pane label="Templates" name="templates">
                    <div class="el-tab-pane_search" style="margin: 10px; margin-left: 0px; margin-top: 0;">
                        <div class="form-group m-mb-0">
                            <label>Tags</label>
                            <v-select
                                multiple
                                taggable
                                v-model="filterTags"
                                :options="tagItems"
                                label="title"
                                max-height="100px">
                            </v-select>
                        </div>
                    </div>
                    <div
                        v-if="activeTabName == 'templates'"
                        class="el-tab-pane_items-container"
                        style="background-color: rgba(255, 255, 255, 0.02);"
                        v-loading="filesLoading">
                        <perfect-scrollbar class="el-tab-pane_items" ref="templatesContainer" @ps-scroll-y="scrollTrigger">
                            <div class="b-masonry-list" v-masonry transition-duration="0.8s" item-selector=".b-masonry-list_item">
                                <div
                                    v-masonry-tile
                                    v-for="(item, index) in templates"
                                    @click="selectItem('template', item.id)"
                                    :key="index"
                                    class="b-masonry-list_item"
                                    :class="[{'b-masonry-list_item_selected': selectedItem && (selectedItem.identifier == item.id)}]">
                                    <img v-if="item.screenshot"
                                        :src="'/'+item.screenshot"
                                        class="b-masonry-list_img img-responsive"
                                    />
                                    <div v-else class="b-masonry-list_textual">
                                        <span>{{item.name}}</span>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="b-masonry-list_charging"
                                :class="{'b-masonry-list_charging_hidden': !templatesFetching}"
                            >
                                Loading...
                            </div>
                            <div style="clear: both;"></div>
                        </perfect-scrollbar>
                    </div>
                </el-tab-pane> -->
            </el-tabs>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="insertBlock" :disabled="selectedItem == null ? true : false">Insert</button>
            <button type="button" class="btn btn-primary" @click="$modal.hide('components-lib-modal')">Cancel</button>
        </div>
    </v-modal>
</template>

<script>
    import { mapActions, mapState } from 'vuex'
    import PerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'
    import { getComponentsList } from '~/utils/helpers.js'

    export default {
        components: {
            PerfectScrollbar
        },
        props: {
            storeType: {type: String, default: 'content'},
        },
        data() {
            return {
                modalTitle: 'Components Library',
                selectedItem: null,
                callback: null,
                activeTabName: 'components',

                standardBlocksList: null,
                tpBlocksList: null,

                filterTags: [],
                filesLoading: false
            }
        },
        computed: {
            templates() {
                return this.$store.state[this.storeType]['templates']['templates']
            },
            templatesFetching() {
                return this.$store.state[this.storeType]['templates']['fetching']
            },
            templatesFilled() {
                return this.$store.state[this.storeType]['templates']['filled']
            },
            tagItems() {
                return this.$store.state[this.storeType]['templates']['tags']
            }
        },
        watch: {
            filterTags(val, old) {
                this.setTemplatesFilter({tags: _.map(val, 'id')})
                this.fetchTemplates()
            }
        },
        methods: {
            ...mapActions({
                fetchTemplates(dispatch, payload) {
                    return dispatch(`${this.storeType}/templates/fetchTemplates`, payload)
                },
                setTemplatesFilter(dispatch, payload) {
                    return dispatch(`${this.storeType}/templates/setTemplatesFilter`, payload)
                },
                fetchTags(dispatch, payload) {
                    return dispatch(`${this.storeType}/templates/fetchTags`, payload)
                }
            }),
            insertBlock() {
                this.$modal.hide('components-lib-modal')
                if(this.callback) this.callback( this.selectedItem )
            },
            selectItem(type, identifier) {
                this.selectedItem = {
                    type,
                    identifier
                }
            },
            closed(event) {
                this.selectedItem = null
            },
            loadMoreTemplates() {
                this.fetchTemplates()
            },
            scrollTrigger() {
                let $templatesContainer = this.$refs.templatesContainer && this.$refs.templatesContainer.$el
                let clientHeight = $templatesContainer.clientHeight
                let scrollTop = $templatesContainer.scrollTop
                let scrollHeight = $templatesContainer.scrollHeight

                let heightDiff = scrollHeight - clientHeight

                if(heightDiff < 0) return

                let delta = heightDiff - scrollTop

                if(delta >= 0 && delta < 20) {
                    this.loadMoreTemplates()
                }
            }
        },
        mounted() {
            this.$bus.$on('open-blocks-modal', (params) => {
                this.fetchTags()
                this.fetchTemplates()

                this.standardBlocksList = getComponentsList({
                    currentComponentName: params.componentName,
                    location: params.location
                })
                this.tpBlocksList = getComponentsList({
                    currentComponentName: params.componentName,
                    componentsType: 'tp'
                })
                this.$modal.show('components-lib-modal')
                this.callback = (params.cb) ? params.cb : null
            })
            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('open-blocks-modal')
            })
        }
    }
</script>
