<template>
    <div class="col-md-12">
        <div class="box box-primary" style="min-height: 525px;">
            <div class="box-header">
                <h3 class="box-title">Permalinks</h3>
                <div class="header-tools">
                    <button @click="save" class="btn btn-block btn-primary btn-header">Save</button>
                </div>
            </div>
            <div class="box-body noselect">
                <div class="multilevel-form">
                    <template v-if="data">
                        <div v-for="(contentType, typeIndex) in data" :key="typeIndex" class="form-group-separated">
                            <div class="form-group">
                                <label>Content Type "{{ contentType.name }}"</label>
                                <input :name="'contentType-' + typeIndex" type="text" class="form-control" v-model="contentType.front_slug" placeholder="Enter slug">
                            </div>

                            <div v-if="contentType.taxonomies && contentType.taxonomies.length" class="form-group">
                                <div v-for="(taxonomy, taxonomyIndex) in contentType.taxonomies" :key="taxonomyIndex" class="form-group-sub">
                                    <label>Taxonomy "{{ taxonomy.name }}"</label>
                                    <input :name="'taxonomyIndex-' + taxonomyIndex" type="text" class="form-control" v-model="taxonomy.slug" placeholder="Enter slug">
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                data: null
            }
        },
        methods: {
            save() {
                let content = this.data.map(({id, front_slug}) => ({id, front_slug}))
                let taxonomies = _.reduce(this.data, (result, value) => {
                        let outputData = value.taxonomies.map(({id, slug}) => ({id, slug}))
                        return result.concat( outputData )
                    }, [])

                let formData = {
                    content: content,
                    taxonomies: taxonomies
                }

                return axios.post(route('api.settings.urls.store'), formData)
                    .then((response) => {
                    })
                    .catch((error) => {
                        //
                    })
            }
        },
        mounted() {
            return axios.get(route('api.settings.urls.index'))
                .then(({data: responseData}) => {
                    let { data } = responseData
                    this.data = data
                })
                .catch((error) => {
                    console.error('API error', error)
                })
        }
    }
</script>
