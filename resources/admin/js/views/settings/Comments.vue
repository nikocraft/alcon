<template>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Comments Settings</h3>
                <div class="header-tools">
                    <button @click="save" class="btn btn-block btn-primary btn-header">Save</button>
                </div>
            </div>
            <div v-if="settings" class="box-body noselect">
                <div class="form-group">
                    <label>Comments Type</label>
                    <select name="type" class="form-control" v-model="settings.type">
                        <option value="off">Turn Off</option>
                        <option value="native">Native</option>
                        <option value="disqus">Disqus</option>
                    </select>
                </div>
                <!-- <div v-if="settings.type=='native'" class="form-group">
                    <label>Commentable Content</label>
                    <textarea class="form-control" rows="5">Pages</textarea>
                    <small>Select content that should support comments.</small>
                </div> -->

                <div v-if="settings.type=='native'">
                    <div class="form-group">
                        <label>Logged in to Comment</label>
                        <select name="loggedInToComment" class="form-control" v-model="settings.loggedInToComment">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Nested Comments</label>
                        <select name="allowNested" class="form-control" v-model="settings.allowNested">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                        </select>
                    </div>

                    <div v-if="settings.allowNested" class="form-group">
                        <label>Nested Depth</label>
                        <select name="nestedDepth" class="form-control" v-model="settings.nestedDepth">
                            <option value=1>1</option>
                            <option value=2>2</option>
                            <option value=3>3</option>
                            <option value=4>4</option>
                            <option value=5>5</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Comments Order</label>
                        <select name="order" class="form-control" v-model="settings.order">
                            <option value="asc">Oldest</option>
                            <option value="desc">Newest</option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label>Moderate Comments</label>
                        <select name="moderation" class="form-control" v-model="settings.moderation">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                        </select>
                    </div> -->

                    <!-- <div v-if="moderation" class="form-group">
                        <label>Moderation for approved users</label>
                        <select name="moderateApprovedUsers" class="form-control" v-model="moderateApprovedUsers">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                        </select>
                    </div> -->

                    <!-- <div v-if="settings.moderation" class="form-group">
                    <label>Email admin when comments need to be moderated.</label>
                    <select name="notifyOnModeration" class="form-control" v-model="settings.notifyOnModeration">
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                    </div>

                    <div class="form-group">
                    <label>Email admin when new comments are posted.</label>
                    <select name="notifyOnComment" class="form-control" v-model="settings.notifyOnComment">
                        <option :value="true">Yes</option>
                        <option :value="false">No</option>
                    </select>
                    </div> -->
                </div>

                <div v-if="settings.type=='disqus'">
                    <div class="form-group">
                        <label>Disqus Channel</label>
                        <input name="disqusChannel" class="form-control" v-model="settings.disqusChannel" type="text" placeholder="Enter Channel Name">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                settings: null
            }
        },
        methods: {
            save() {
                let formData = {
                    settings: this.settings,
                }

                return axios.post(route('api.settings.comments.store'), formData)
                    .then((response) => {
                        //
                    })
                    .catch((error) => {
                        //
                    })
            }
        },
        mounted() {
            return axios.get(route('api.settings.comments.index'))
                .then(({data}) => {
                    let settingsResponse = data.data
                    this.settings = settingsResponse
                })
                .catch((error) => {
                    console.error('API error', error)
                })
        }
    }
</script>
