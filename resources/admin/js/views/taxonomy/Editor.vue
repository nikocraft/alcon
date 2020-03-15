<template>
    <div class="col-xs-12">
        <div class="box box-primary" v-if="taxonomyData">
            <div class="box-header">
                <h3 class="box-title">{{ taxonomyData.name }}</h3>
                <div class="box-tools">
                    <button @click="handleAdd" class="btn btn-block btn-primary btn-header">New</button>
                </div>
            </div>
            <div class="box-body">

                <div class="list terms">
                    <!-- HEADER -->
                    <div class="row-header">
                        <div class="column name_column">Name</div>
                        <div class="column description_column">Description</div>
                        <div class="column actions_column">Actions</div>
                    </div>

                    <div v-for="term in terms" :key="term.id" class="row">
                        <!-- TITLE -->
                        <div class="column name_column">
                            <span>{{ term.name }}</span>
                        </div>

                        <div class="column description_column">
                            {{ term.description }}
                        </div>

                        <!-- ACTIONS -->
                        <div class="column actions_column">
                            <button @click="handleEdit(term)" class="btn btn-xs btn-primary action-btn" type="button">
                                <span style="text-align: center; color: #ffffff; text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);">
                                    <i class="lo-icon lo-icon-edit"></i>
                                </span>
                            </button>

                            <button @click="handleDelete(term)" class="btn btn-xs btn-danger action-btn" type="button">
                                <i class="lo-icon lo-icon-trash"></i>
                            </button>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="row-header">
                        <div class="column name_column">Name</div>
                        <div class="column description_column">Description</div>
                        <div class="column actions_column">Actions</div>
                    </div>
                </div>

                <term-form :taxonomy="taxonomyData"/>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters, mapActions } from 'vuex'
    import TermForm from './forms/TermForm'

    export default {
        components: {
            TermForm
        },
        props: {
            contentTypeSlug: {type: String, default: ''},
            taxonomyId: {type: [Number, String], default: null}
        },
        computed: {
            ...mapState('taxonomy', ['taxonomyData', 'terms', 'termsLoading']),
            contentTypeId() {
                return this.$store.getters['contentTypes/index/getIdBySlug'](this.contentTypeSlug)
            }
        },
        methods: {
            ...mapActions('taxonomy', ['fetch', 'removeTerm', 'flushData']),
            handleAdd() {
                this.$bus.$emit('open-modal:term-form', {
                    cb: () => {
                        this.fetchData()
                    }
                })
            },
            handleEdit(term) {
                this.$bus.$emit('open-modal:term-form', {
                    term,
                    cb: () => {
                        this.fetchData()
                    }
                })
            },
            handleDelete(term) {
                this.$confirm('Are you sure you want to delete this term?', 'Warning', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    cancelButtonClass: 'el-button--info',
                    type: 'warning'
                }).then(() => {
                    this.removeTerm({term})
                        .then(() => {
                            this.fetchData()
                        })
                }).catch(() => {})
            },
            fetchData() {
                return this.fetch({
                    contentTypeId: this.contentTypeId,
                    taxonomyId: this.taxonomyId
                })
            }
        },
        beforeRouteLeave (to, from, next) {
            this.flushData()
            next()
        },
        created() {
            this.fetchData()
        }
    }
</script>

<style lang="scss" scoped>
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
        background: #00c0ef;
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

    .status {
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
