<template>
    <div class="col-xs-12" v-if="contentTypeData && settings">
        <div class="box box-primary index-page">
            <div class="box-header">
                <h3 class="box-title">{{ contentTypeData.name }}</h3>
                <div class="header-tools">
                    <i @click="$refs && $refs.settingsModal.toggle(true)" class="lo-icon lo-icon-cog" style="margin: 0; margin-right: 10px; font-size: 16px; cursor: pointer;" title="Display Settings"></i>
                    <button
                        class="btn btn-block btn-primary btn-header"
                        type="button"
                        style="padding: 2px 10px; min-width: 65px;"
                        @click="$router.push({ name: 'content.create' })">Create</button>
                </div>
            </div>
            <div class="box-body">
                <div>
                    <div class="search_options pull-left">
                        <div style="display: inline-flex;">
                            <div style="float: left; margin-right: 1px;"><input v-model="search" class="form-control" type="text" placeholder="Search" style="padding-left: 5px; width: 100%;"></div>
                            <div style="float: left; margin-right: 1px;"><select v-model="filter" style="width: 120px;" class="form-control">
                                    <option value="title">Title</option>
                                    <option value="username">Username</option>
                            </select></div>
                            <div @click="searchTrigger" style="float: left;"><button class="btn btn-primary">Search</button></div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                    <div :class="settings.indexPageDisplay">
                        <!-- HEADER -->
                        <div v-if="settings.indexPageDisplay == 'list'" class="row-header">
                            <!-- <div v-if="displayStatus" class="column status_column">Status</div> -->
                            <div v-if="settings.indexPageDisplayAuthor" class="column author_column">Author</div>
                            <div class="column title_column">Title</div>
                            <div v-if="settings.indexPageDisplayFeaturedImage" class="column featured_image_column">Featured Image</div>
                            <div v-if="settings.indexPageDisplayCreatedUpdated" class="column created_column">Created</div>
                            <div v-if="settings.indexPageDisplayCreatedUpdated" class="column updated_column">Updated</div>
                            <div class="column actions_column">Actions</div>
                        </div>

                        <!-- NORMAL Render -->
                        <template v-if="settings.indexPageDisplay == 'list' || (settings.indexPageDisplay == 'grid' && settings.indexPageGridStyle=='normal')">
                            <div
                                v-for="content in items"
                                :key="content.id"
                                class="row"
                                :class="settings.indexPageGridColumns">

                                <!-- AUTHOR -->
                                <div v-if="settings.indexPageDisplayAuthor" class="column author_column">
                                    <template v-if="content.author.firstname">
                                        {{content.author.firstname}} {{content.author.lastname}}
                                    </template>
                                    <template v-else>
                                        {{content.author.username}}
                                    </template>
                                </div>

                                <!-- TITLE -->
                                <div class="column title_column">
                                    <router-link :to="{ name: 'content.edit', params: { id: content.id }}" class="content-title">
                                        {{ content.title }}
                                    </router-link>
                                </div>

                                <!-- FEATURED -->
                                <div v-if="settings.indexPageDisplayFeaturedImage && settings.indexPageDisplay == 'list'" class="column featured_image_column">
                                    <img v-if="content.featuredimage" class="featured_image img-responsive" :src="content.featuredimage.medium">
                                    <!-- <img v-else class="featured_image img-responsive" src="/images/no_featured_default.jpg" style="min-height: 220px;" v-bind:style="{'min-height': minImageHeight}"> -->
                                </div>

                                <!-- FEATURED -->
                                <div v-if="settings.indexPageDisplayFeaturedImage && settings.indexPageDisplay == 'grid'" class="column featured_image_column" v-bind:style="{'min-height': minFeaturedImageColumnHeight}">
                                    <img v-if="content.featuredimage" class="featured_image img-responsive" :src="content.featuredimage.medium">
                                    <!-- <img v-else class="featured_image img-responsive" src="/images/no_featured_default.jpg" style="min-height: 220px;" v-bind:style="{'min-height': minImageHeight}"> -->
                                </div>

                                <div v-if="settings.indexPageDisplayCreatedUpdated" class="column created_column">
                                    {{ content.created_at }}
                                </div>

                                <div v-if="settings.indexPageDisplayCreatedUpdated" class="column updated_column">
                                    {{ content.updated_at }}
                                </div>

                                <!-- ACTIONS -->
                                <div class="column actions_column">
                                    <router-link :to="{ name: 'content.edit', params: { id: content.id }}">
                                        <button class="btn btn-primary edit-btn" type="button">
                                            <i class="lo-icon lo-icon-edit"></i>
                                        </button>
                                    </router-link>

                                    <button  @click="deleteContent(content.id)" class="btn btn-xs btn-danger action-btn" type="button">
                                        <i class="lo-icon lo-icon-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <!-- Grid Inline Render -->
                        <template v-if="settings.indexPageDisplay == 'grid' && settings.indexPageGridStyle=='inline'">
                            <div
                                v-for="content in items"
                                :key="content.id"
                                class="row"
                                :class="settings.indexPageGridColumns"
                                v-bind:style="{'height': inlineDivHeight}">
                                <div class="contents"
                                    :style="{'cursor':'default', 'padding':'0', 'display':'flex', 'justify-content': 'space-between', 'flex-direction': 'column', 'background-image': imageUrl(content.featuredimage && content.featuredimage.original), 'width': '100%', 'background-size': 'cover','background-position': 'center'}">
                                    <div class="inline_column"
                                        style="margin: 0; flex: 1; align-items: center; justify-content: center; padding: 12px;"
                                        v-bind:style="{'font-size': gridTitleFontSize}">
                                        <!-- AUTHOR -->
                                        <div v-if="settings.indexPageDisplayAuthor" class="author">
                                            <template v-if="content.author.firstname">
                                                {{content.author.firstname}} {{content.author.lastname}}
                                            </template>
                                            <template v-else>
                                                {{content.author.username}}
                                            </template>
                                        </div>

                                        <!-- TITLE -->
                                        <!-- <a :href="content.url" class="content-title"> <dot-dot-dot clamp="..." :length="50" less="Show Less" :text="content.title"> </dot-dot-dot>  </a> -->
                                        <router-link class="content-title" :to="{ name: 'content.edit', params: { id: content.id }}">
                                            <!-- <dot-dot-dot clamp="..." :length="50" less="Show Less" :text="content.title"> </dot-dot-dot> -->
                                            {{ content.title }}
                                        </router-link>

                                        <!-- ACTIONS -->
                                        <div class="actions" style="font-size: 30px;">
                                            <router-link :to="{ name: 'content.edit', params: { id: content.id }}">
                                                <button class="btn btn-xs btn-primary edit-btn" type="button">
                                                    <i class="lo-icon lo-icon-edit"></i>
                                                </button>
                                            </router-link>

                                            <button @click="deleteContent(content.id)" class="btn btn-xs btn-danger action-btn" type="button">
                                                <i class="lo-icon lo-icon-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- FOOTER -->
                        <div v-if="settings.indexPageDisplay == 'list'" class="row-header">
                            <div v-if="settings.indexPageDisplayAuthor" class="column author_column">Author</div>
                            <div class="column title_column">Title</div>
                            <div v-if="settings.indexPageDisplayFeaturedImage" class="column featured_image_column">Featured Image</div>
                            <div v-if="settings.indexPageDisplayCreatedUpdated" class="column created_column">Created</div>
                            <div v-if="settings.indexPageDisplayCreatedUpdated" class="column updated_column">Updated</div>
                            <div class="column actions_column">Actions</div>
                        </div>
                    </div>

                    <div style="height: 24px; margin-bottom: 5px; margin-top: 5px;" class="pull-right">
                        <vue-pagination v-show="pagination.last_page > 1"  v-bind:pagination="pagination"
                                        v-on:page-updated="updateRouteQuery"
                                        :offset="4">
                        </vue-pagination>
                        <!-- {{ pagination }} -->
                    </div>
                    <div style="clear: both;"></div>

                    <v-modal ref="settingsModal"
                        name="settings-modal"
                        :classes="['v--modal', 'modal-content']"
                        :overlay="true"
                        transition="nice-modal-fade"
                        :width="600"
                        :height="650"
                        :adaptive="true"
                        :draggable="'.modal-header'"
                        :widthPadding="15">
                        <div class="modal-header grabbable">
                            <button type="button" class="close" aria-label="Close" @click="$refs.settingsModal.toggle(false)">
                                <span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title animated">{{ contentTypeData.name+' Display Settings' }}</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Items Per Page</label>
                                <input type="text" class="form-control" v-model="settings.indexPageItemsPerPage">
                            </div>

                            <div class="form-group">
                                <label>Sort By</label>
                                <select class="form-control" v-model="settings.indexPageSortBy">
                                    <option value='latest'>Latest</option>
                                    <option value='oldest'>Oldest</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Display as</label>
                                <select class="form-control" v-model="settings.indexPageDisplay">
                                    <option value='list'>List</option>
                                    <option value='grid'>Grid</option>
                                </select>
                            </div>

                            <div v-show="settings.indexPageDisplay == 'grid'" class="form-group">
                                <label>Display Style</label>
                                <select class="form-control" v-model="settings.indexPageGridStyle">
                                    <option value='inline'>Inline</option>
                                    <option value='normal'>Normal</option>
                                </select>
                            </div>

                            <div v-show="settings.indexPageDisplay == 'grid'" class="form-group">
                                <label>Columns</label>
                                <select class="form-control" v-model="settings.indexPageGridColumns">
                                    <option value='column-1'>1</option>
                                    <option value='column-2'>2</option>
                                    <option value='column-3'>3</option>
                                    <option value='column-4'>4</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Display Status</label>
                                <select class="form-control" v-model="settings.indexPageDisplayStatus">
                                    <option v-bind:value='true'>Yes</option>
                                    <option v-bind:value='false'>No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Display Author</label>
                                <select class="form-control" v-model="settings.indexPageDisplayAuthor">
                                    <option v-bind:value='true'>Yes</option>
                                    <option v-bind:value='false'>No</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Display Featured Image</label>
                                <select class="form-control" v-model="settings.indexPageDisplayFeaturedImage">
                                    <option v-bind:value='true'>Yes</option>
                                    <option v-bind:value='false'>No</option>
                                </select>
                            </div>

                            <div v-show="settings.indexPageGridDisplayStyle == 'normal'" class="form-group">
                                <label>Display Created&Updated</label>
                                <select class="form-control" v-model="settings.indexPageDisplayCreatedUpdated">
                                    <option v-bind:value='true'>Yes</option>
                                    <option v-bind:value='false'>No</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="saveSettingsTrigger">Save</button>
                        </div>
                    </v-modal>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions, mapGetters } from 'vuex'
    import dataList from '../../store/modules/common/dataList'
    // import DotDotDot from '~/components/ui/DotDotDot'

    const getStoreName = contentTypeId => (`contentList-${contentTypeId}`)
    const getApiUrl = contentTypeId => route('api.content.index', {contentTypeId})

    export default {
        components: {
            // DotDotDot
        },
        data() {
            return {
                search: null,
                filter: 'title',
                paging: true,
            }
        },
        props: {
            contentTypeSlug: {type: String, default: ''},
            queryString: {type: Object},

            gridTitleFontSize: {type: String, default: '35px'},
        },
        computed: {
            ...mapState({
                items(state) {
                    return state && state[getStoreName(this.contentTypeId)].items
                },
                pagination(state) {
                    return state && state[getStoreName(this.contentTypeId)].paginationData
                }
            }),
            ...mapState('content/index', {
                contentTypeData: state => state.data,
                settings: state => state.settings
            }),
            contentTypeId() {
                return this.$store.getters['contentTypes/index/getIdBySlug'](this.contentTypeSlug)
            },
            fetchTrigger() {
                return {
                    per_page: this.settings && this.settings.indexPageItemsPerPage,
                    sort: this.settings && this.settings.indexPageSortBy,
                    ...this.queryString
                }
            },
            inlineDivHeight() {
                switch (this.settings.indexPageGridColumns) {
                    case 'column-1':
                        return '255px';
                    break;
                    case 'column-2':
                        return '380px';
                    break;
                    case 'column-3':
                        return '310px';
                    break;
                    case 'column-4':
                        return '252px';
                    break;
                    default:
                }
            },
            minFeaturedImageColumnHeight() {
                switch (this.settings.indexPageGridColumns) {
                    case 'column-1':
                        return '300px';
                    break;
                    case 'column-2':
                        return '468px';
                    break;
                    case 'column-3':
                        return '314px';
                    break;
                    case 'column-4':
                        return '237px';
                    break;
                    default:
                }
            }
        },
        watch: {
            fetchTrigger(data, old) {
                if (!this.settings || !Object.keys(this.settings).length) return
                this.setQueryString({data})
                this.fetch()
            },
            'settings.indexPageItemsPerPage'(val, old) {
                if(old && old != val) {
                    let query = {...this.queryString}
                    if(query.hasOwnProperty('page')) {
                        delete query.page
                        this.$router.push({query})
                    }
                }
            }
        },
        methods: {
            ...mapActions({
                setBaseFilterData(dispatch, payload) {
                    return dispatch(getStoreName(this.contentTypeId) + '/setBaseFilterData', payload)
                },
                setQueryString(dispatch, payload) {
                    return dispatch(getStoreName(this.contentTypeId) + '/setQueryString', payload)
                },
                fetch(dispatch, payload) {
                    return dispatch(getStoreName(this.contentTypeId) + '/fetch', payload)
                },
                setContentTypeData: 'content/index/setContentTypeData',
                saveSettings: 'content/index/saveSettings'
            }),
            deleteContent(id) {
                this.$confirm('This action will delete this content, are you sure you want to continue?', 'Warning', {
                    confirmButtonText: 'Delete',
                    showCancelButton: false,
                    confirmButtonClass: 'el-button--danger',
                    type: 'warning'
                }).then(() => {
                    axios.delete(route('api.content.destroy', {contentTypeId: this.contentTypeId, id}))
                        .then((response) => {
                            this.fetch()
                        })
                        .catch((error) => {
                            console.error(error)
                        })
                })
            },
            searchTrigger() {
                let query = {...this.queryString}
                query.search = this.search
                query.filter = this.filter
                delete query.page

                this.$router.push({query})
            },
            imageUrl(url) {
                url = this.settings.indexPageDisplayFeaturedImage ? String(url) : ''
                return `url(${url})`
            },
            saveSettingsTrigger() {
                this.$refs.settingsModal && this.$refs.settingsModal.toggle(false)
                this.saveSettings()
            },
            updateRouteQuery(page) {
                let query = {...this.queryString}
                query.page = page

                this.$router.push({query})
            },
            initState(contentTypeSlug) {
                const contentTypeId = contentTypeSlug
                    ? this.$store.getters['contentTypes/index/getIdBySlug'](contentTypeSlug)
                    : this.contentTypeId

                const store = this.$store,
                    storeName = getStoreName(contentTypeId)

                if(!(store && store.state && store.state[storeName])) {
                    // console.log('REGISTER MODULE', storeName)
                    store.registerModule(storeName, dataList)
                    store.dispatch(`${storeName}/setApiUrl`, {data: getApiUrl(contentTypeId)})
                } else {
                    // console.log(`Reusing module: ${storeName}`)
                }
            },
            initContentType(params) {
                let {contentTypeSlug} = params
                const contentTypeId = this.$store.getters['contentTypes/index/getIdBySlug'](contentTypeSlug)
                contentTypeId && this.$store.dispatch('content/index/fetchContentType', {contentTypeId})
            }
        },
        beforeRouteUpdate (to, from, next) {
            this.initContentType(to.params)
            this.initState(to.params.contentTypeSlug)
            next()
        },
        beforeRouteEnter (to, from, next) {
            next((vm) => {
                vm.initContentType(to.params)
            })
        },
        beforeRouteLeave (to, from, next) {
            this.$store.dispatch('content/index/flushContentType')
            next()
        },
        destroyed() {
            this.$store.dispatch('content/index/flushContentType')
        },
        created() {
            this.initState()
        }
    }
</script>

<style lang="scss" scoped>
    .search_options {
        margin-bottom: 5px;
    }

    .contents {
        position: relative;
        padding: 0;
        margin: 0px;
    }

    .inline_column {
        display: flex;
        &:hover {
            background-color: rgba(0,0,0, 0.1)
        }
    }

    .status-count {
        cursor: pointer;
        color: #3c8dbc;
        font-weight: bold;
    }

    .label-published, .label-draft, .label-schedule, .label-created_at, .label-updated_at {
        background: #3c8dbc;
        border-radius: 0.25em;
        color: #fff;
        display: inline-block;
        font-size: 95%;
        font-weight: 700;
        padding: 3px 8px;
        text-align: center;
    }

    .author {
        padding: 0;
        border: 0;
        position: absolute;
        left: 10px;
        top: 10px;
        font-size: 14px;
        color: white;
        text-shadow: rgba(0, 0, 0, 0.3) 1px 1px 1px;
    }

    .status_list {
        font-size: 14px;
        color: white;
        text-shadow: rgba(0, 0, 0, 0.3) 1px 1px 1px;
    }

    .status_inline {
        padding: 0;
        border: 0;
        position: absolute;
        right: 10px;
        top: 10px;
        font-size: 14px;
        color: white;
        text-shadow: rgba(0, 0, 0, 0.3) 1px 1px 1px;
    }

    .actions {
        padding: 0;
        border: 0;
        position: absolute;
        right: 10px;
        bottom: 10px;
    }
</style>
