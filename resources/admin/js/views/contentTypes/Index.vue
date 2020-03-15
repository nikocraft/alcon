<template>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title">Content Types</h3>
                <div class="box-tools">
                    <button
                        class="btn btn-block btn-primary"
                        type="button"
                        style="padding: 2px 10px; min-width: 65px;"
                        @click="$router.push({name: 'content-types.create'})">Create</button>
                </div>
            </div>
            <div class="box-body">
                <template v-if="items && items.length">
                    <div class="column title_column" v-for="item in items" :key="item.id">
                        <router-link :to="{ name: 'content-types.edit', params: { id: item.id }}" class="content_title">
                            {{ item.name }}
                        </router-link>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex'
    import dataList from '../../store/modules/common/dataList'

    const storeName = 'contentTypesList',
          apiUrl = route('api.settings.content-types.index')

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
