<template>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Comments</h3>
            </div>

            <div class="box-body">
                <div style="margin-bottom: 2px;">
                    <div class="pull-left">
                        <template v-for="filter in ['all', 'approved', 'pending', 'spam']">
                            <a class="btn btn-primary btn-dark btn-header m-capitalize"
                                :class="{'active': currentStatus == filter}"
                                :key="filter"
                                @click="changeFilter(filter)"
                            >{{ filter }} [{{ counters[filter] }}]</a>
                            <slot> </slot>
                        </template>
                    </div>

                    <div class="pull-right"><small>Found ({{ counters.all }})</small></div>
                    <div class="clearfix"></div>
                </div>
                <div style="margin-bottom: 4px;">
                    <div style="border: 0px blue solid; " class="pull-right">
                        <pagination v-if="comments" :data="pagination" v-on:pagination-change-page="pageChange" :limit="2"></pagination>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <table class="table table-content noselect">
                    <thead>
                        <tr>
                            <th style="width: 138px;">Author</th>
                            <th style="width: 530px;">Comment</th>
                            <th style="width: 250px;">In Response To</th>
                            <th style="width: 150px;">Submitted On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="comments">
                            <tr v-for="(comment, index) in comments" :key="index">
                                <td>
                                    <div v-if="comment.author && comment.author.firstname && comment.author.lastname">
                                        {{ comment.author.firstname }} {{ comment.author.lastname }}
                                    </div>
                                    <div v-if="comment.author">
                                        {{ comment.author.username }}
                                    </div>
                                    <div v-else>{{ comment.name }}</div>

                                    <div v-if="comment.author">{{ comment.author.email }}</div>
                                    <div v-else>{{ comment.email }}</div>

                                    <div>{{ comment.visitor_ip }}</div>
                                </td>

                                <td>
                                    <div class="" style="margin-bottom: 10px;">
                                        {{ comment.body }}
                                    </div>

                                    <div class="">
                                        <span class="approve" v-show="comment.status != 1 && comment.status != 3"
                                        :data-comment-id="comment.id" style="cursor: pointer; color: #2489C5" @click="statusUpdate(comment.id, 1)">approve</span>
                                        <span class="unapprove" v-show="comment.status != 2 && comment.status != 3"
                                        :data-comment-id="comment.id" style="cursor: pointer; color: #2489C5" @click="statusUpdate(comment.id, 2)">unapprove</span>
                                        <span class="not-spam" v-show="comment.status == 3"
                                        :data-comment-id="comment.id" style="cursor: pointer; color: #2489C5" @click="statusUpdate(comment.id, 1)">not spam</span>

                                        <span v-show="comment.status == 1 || comment.status == 2" class="spam" :data-comment-id="comment.id" style="cursor: pointer; color: #2489C5" @click="statusUpdate(comment.id, 3)"><span style="color: #fff; line-height: 17px;">&nbsp;·&nbsp;</span> spam</span>

                                        <span class="delete" :data-comment-id="comment.id" style="cursor: pointer; color: #2489C5" @click="deleteComment(comment.id)"><span style="color: #fff; line-height: 17px;">&nbsp;·&nbsp;</span> delete</span>
                                    </div>
                                </td>

                                <td data-keyboard="false">
                                    {{ comment.title }}
                                </td>

                                <td>
                                    {{ comment.created_at }}
                                </td>
                            </tr>
                        </template>

                        <tr v-if="comments && comments.length == 0">
                            <td colspan="4">No comments.</td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters, mapActions } from 'vuex'
    import pagination from 'laravel-vue-pagination'

    export default {
        components: {
            pagination
        },
        data() {
            return {
                searchForm: {
                    search: undefined,
                    filter: undefined
                }
            }
        },
        computed: {
            ...mapState('comments/index', [
                'queryParams',
                'comments',
                'pagination',
                'commentsLoading',
                'counters'
            ]),
            ...mapGetters('comments/index', [
                'currentStatus'
            ])
        },
        watch: {
            $route(route) {
                this.updateQueryFromRouter()
                this.fetchComments()
            }
        },
        methods: {
            ...mapActions('comments/index', [
                'fetchComments',
                'updateQueryParams'
            ]),
            statusUpdate(comment_id, status){
                axios.patch(route('api.comments.update', comment_id), {status})
                    .then((response) => {
                        this.fetchComments()
                    })
            },
            deleteComment(comment_id){
                this.$confirm('Are you sure you want to delete this comment? This action cannot be undone!', 'Delete Comment', {
                    confirmButtonText: 'Delete',
                    showCancelButton: false,
                    confirmButtonClass: 'el-button--danger',
                    type: 'warning'
                }).then(() => {
                    axios.delete(route('api.comments.destroy', comment_id))
                        .then((response) => {
                            this.fetchComments()
                        })
                }).catch(() => {})
            },
            pageChange(page) {
                this.$router.push({query: { page }})
            },
            changeFilter(status) {
                this.updateQueryParams({page: undefined})
                this.$router.push({query: { status }})
            },
            updateQueryFromRouter() {
                let query = this.$route.query
                this.updateQueryParams(query)
            },
            pushToRouter (obj = {}) {
                this.$router.push({ query: { ...this.$route.query, ...obj }})
            },
        },
        created() {
            this.updateQueryFromRouter()
            this.fetchComments()
        }
    }
</script>
