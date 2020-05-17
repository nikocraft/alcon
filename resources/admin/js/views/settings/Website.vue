<template>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Website Settings</h3>
                <div class="header-tools">
                    <button @click="save" name="save" class="btn btn-block btn-primary btn-header">Save</button>
                </div>
            </div>
            <div v-if="settings" class="box-body noselect">
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Website Title" v-model="settings.title">
                </div>

                <div class="form-group">
                    <label>Tagline</label>
                    <input type="text" name="tagline" class="form-control" placeholder="Website Tagline" v-model="settings.tagline">
                </div>

                <div class="form-group">
                    <label>Site Url</label>
                    <input type="text" name="url" class="form-control" placeholder="Your Website Url" v-model="settings.url">
                </div>

                <div class="form-group">
                    <label>Frontpage</label>
                    <select name="frontPageType" class="form-control" v-model="settings.frontPageType">
                        <option value="welcome-page">Welcome</option>
                        <option value="single-page">Page</option>
                        <option value="index-page">Content Index</option>
                    </select>
                </div>

                <div v-if="settings.frontPageType=='single-page'" class="form-group">
                    <label>Select Page</label>
                    <select name="frontPageMeta" class="form-control" v-model="settings.frontPageMeta">
                        <option v-for="page in pages" :key="page.id" :value="page.id">{{ page.title }}</option>
                    </select>
                </div>

                <div v-if="settings.frontPageType=='index-page'" class="form-group">
                    <label>Select Content Type</label>
                    <select name="frontPageMeta" class="form-control" v-model="settings.frontPageMeta">
                        <option v-for="contentType in contentTypesFiltered" :key="contentType.id" :value="contentType.id">{{ contentType.name }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pagination Type</label>
                    <select name="paginationType" class="form-control" v-model="settings.paginationType">
                        <option value="simple">Simple</option>
                        <option value="standard">Standard</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Items Per Page</label>
                    <input name="paginationPerPage" type="text" class="form-control" placeholder="Posts Per Page" v-model="settings.paginationPerPage">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                settings: null,
                contentTypes: [],
                pages: []
            }
        },
        computed: {
            contentTypesFiltered() {
                return this.contentTypes.filter(function(item) {
                    return item.type == 2
                });
            }
        },
        methods: {
            save() {
                let formData = {
                    settings: this.settings
                }

                return axios.post(route('api.settings.website.store'), formData)
                    .then((response) => {
                        //
                    })
                    .catch((error) => {
                        //
                    })
            },
            getPages() {
                return axios.get(route('api.content.index', 1))
                    .then(({data}) => {
                        this.pages = data.data
                    })
                    .catch((error) => {
                        console.error('API error', error)
                    })
            },
            getContentTypes() {
                return axios.get(route('api.settings.content-types.index'))
                    .then(({data}) => {
                        this.contentTypes = data.data
                    })
                    .catch((error) => {
                        console.error('API error', error)
                    })
            },
            getWebsiteSettings() {
                return axios.get(route('api.settings.website.index'))
                    .then(({data}) => {
                        this.settings = data.data
                    })
                    .catch((error) => {
                        console.error('API error', error)
                    })
            }
        },
        created() {
            this.getContentTypes()
            this.getPages()
            this.getWebsiteSettings()
        }
    }
</script>
