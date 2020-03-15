<template>
    <div class="col-xs-12">
        <div class="box box-primary index-page">
            <div class="box-header">
                <h3 class="box-title">Widgets</h3>
                <div class="header-tools">
                    <router-link :to="{ name: 'widgets.create' }">
                        <button class="btn btn-block btn-primary btn-header">Create</button>
                    </router-link>
                </div>
            </div>
            <div class="box-body">
                <div>
                    <div class="list">
                        <!-- HEADER -->
                        <div class="row-header">
                            <div class="column title_column">Title</div>
                            <div class="column created_column">Created</div>
                            <div class="column updated_column">Updated</div>
                            <div class="column actions_column">Actions</div>
                        </div>

                        <div v-for="widget in items" class="row" :key="widget.id">
                            <div class="column title_column">
                                <router-link :to="{ name: 'widgets.edit', params: { id: widget.id }}" class="content-title">
                                    {{ widget.title }}
                                </router-link>
                            </div>

                            <div class="column created_column">
                                {{ widget.created_at }}
                            </div>

                            <div class="column updated_column">
                                {{ widget.updated_at }}
                            </div>

                            <div class="column actions_column">
                                <router-link :to="{ name: 'widgets.edit', params: { id: widget.id }}" class="content-title">
                                    <button class="btn btn-primary edit-btn" type="button">
                                        <i class="lo-icon lo-icon-edit"></i>
                                    </button>
                                </router-link>

                                <button @click="deleteWidget(widget.id)" class="btn btn-xs btn-danger action-btn" type="button">
                                    <i class="lo-icon lo-icon-trash"></i>
                                </button>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div class="row-header">
                            <div class="column title_column">Title</div>
                            <div class="column created_column">Created</div>
                            <div class="column updated_column">Updated</div>
                            <div class="column actions_column">Actions</div>
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import dataList from '../../../store/modules/common/dataList'

    const storeName = 'widgetsList'
    const apiUrl = route('api.design.widgets.index')

    export default {
        data() {
            return {
                //
            }
        },
        computed: {
            ...mapState(storeName, {
                items: state => state.items
            })
        },
        methods: {
            ...mapActions(storeName, ['setApiUrl', 'fetch']),
            deleteWidget(id) {
                this.$confirm('This action will delete this widget, are you sure you want to continue?', 'Warning', {
                    confirmButtonText: 'Delete',
                    showCancelButton: false,
                    confirmButtonClass: 'el-button--danger',
                    type: 'warning'
                }).then(() => {
                    axios.delete(route('api.design.widgets.destroy', id))
                        .then((response) => {
                            this.fetch()
                        })
                        .catch((error) => {
                            console.error(error)
                        })
                })
            }
        },
        created() {
            const store = this.$store

            if(!(store && store.state && store.state[storeName])) {
                store.registerModule(storeName, dataList)
            } else {
                console.log(`Reusing module: ${storeName}`)
            }
        },
        mounted() {
            this.setApiUrl({data: apiUrl})
            this.fetch()
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
