<template>
    <div class="col-md-12">
        <div class="box box-primary" style="min-height: 525px;">
            <div class="box-header">
                <h3 class="box-title">Admin Settings</h3>
                <div class="header-tools">
                    <button @click="save" name="save" class="btn btn-block btn-primary btn-header">Save</button>
                </div>
            </div>
            <div class="box-body noselect">
                <div v-if="settings">
                    <div class="form-group">
                    <label>Admin Email</label>
                    <input type="text" name="email" class="form-control" v-model="settings.email" placeholder="Enter email">
                    <small>Used for notifications</small>
                    </div>

                    <div class="form-group">
                    <label>Language</label>
                    <select name="language" class="form-control" v-model="settings.language">
                        <option value="english">English</option>
                        <option value="german">German</option>
                        <option value="french">French</option>
                        <option value="italian">Italian</option>
                        <option value="spanish">Spanish</option>
                        <option value="swedish">Swedish</option>
                        <option value="danish">Danish</option>
                    </select>
                    <small>Admin language</small>
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
                    settings: this.settings
                }

                return axios.post(route('api.settings.admin.store'), formData)
                    .then((response) => {
                        //
                    })
                    .catch((error) => {
                        //
                    })
            },
        },
        mounted() {
            return axios.get(route('api.settings.admin.index'))
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
