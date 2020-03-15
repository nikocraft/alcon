<template>
    <div class="block-settings">
        <div class="form-group">
            <label for="">Taxonomy Name (plural)</label>
            <input type="text" class="form-control" v-model="data.name" :disabled='data.id'>
        </div>

        <div class="form-group">
            <label for="">Taxonomy Name (singular)</label>
            <input type="text" class="form-control" v-model="data.name_singular" :disabled='data.id'>
        </div>

        <div class="form-group">
            <label for="">Can Have Children</label>
            <select class="form-control" v-model="settings.canHaveChildren" :disabled='data.id'>
                <option v-bind:value='true'>Yes</option>
                <option v-bind:value='false'>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="">Allow Multiple</label>
            <select class="form-control" v-model="settings.allowMultiple">
                <option v-bind:value='true'>Yes</option>
                <option v-bind:value='false'>No</option>
            </select>
        </div>

        <div v-show="settings.allowMultiple" class="form-group">
            <label for="">Max Multiple</label>
            <el-input-number v-model="settings.maxAllowed" :min="1" :max="30"></el-input-number>
        </div>

        <div class="form-group">
            <label for="">Allow Create in place</label>
            <select class="form-control" v-model="settings.allowCreate">
                <option v-bind:value='true'>Yes</option>
                <option v-bind:value='false'>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="">Allow Filterable</label>
            <select class="form-control" v-model="settings.allowFilterable">
                <option v-bind:value='true'>Yes</option>
                <option v-bind:value='false'>No</option>
            </select>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'

    export default {
        props: {
            uniqueId: {type: Number, default: 0},
        },
        customSettings: {
            required: {type: Boolean, default: false},
            maxAllowed: {type: Number, default: 2},
            allowMultiple: {type: Boolean, default: true},
            allowCreate: {type: Boolean, default: true},
            allowFilterable: {type: Boolean, default: true},
            canHaveChildren: {type: Boolean, default: false}
        },
        computed: {
            ...mapGetters('contentTypes/editor', ['taxonomyData', 'taxonomySettings']),
            data() { return this.taxonomyData(this.uniqueId) },
            settings() { return this.taxonomySettings(this.uniqueId) }
        },
        watch: {
            data: {
                handler(val) {
                    this.updateTaxonomyData({data: val, uniqueId: this.uniqueId})
                },
                deep: true
            },
            settings: {
                handler(val) {
                    this.updateTaxonomySettings({settings: val, uniqueId: this.uniqueId})
                },
                deep: true
            }
        },
        methods: {
            ...mapActions('contentTypes/editor', [
                'updateTaxonomy',
                'updateTaxonomyData',
                'updateTaxonomySettings'
            ])
        }
    }
</script>
