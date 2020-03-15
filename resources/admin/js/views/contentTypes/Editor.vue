<template>
    <div class="col-md-12">
        <settings-explorer/>
        <div class="box box-primary">
            <div class="box-header" style="display: flex; justify-content: space-between; width: 100%;">
                <div style="flex: 1;">
                    <h3 class="box-title">Content Type Editor</h3>
                </div>
                <div style="flex: 1; display: flex; justify-content: flex-end; align-items: center;">
                    <button @click="triggerSave" style="width: 65px; padding: 2px 10px;" class="btn btn-primary">Save</button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group title-group">
                    <label for="title">Name (plural)</label>
                    <input v-model="formData.name"
                        v-validate="'required'"
                        :class="{'form-control':true, 'input': true, 'is-danger': errors.has('name plural') }"
                        name="name plural"
                        type="text"
                        placeholder="Enter plural" />
                    <span v-show="errors.has('name plural')" class="help is-danger">{{ errors.first('name plural') }}</span>
                    <!-- <small>No numbers, no special characters.</small> -->
                </div>

                <div class="form-group title-group">
                    <label for="title">Name (singular)</label>
                    <input v-model="formData.name_singular"
                        v-validate="'required'"
                        name="name singular"
                        :class="{'form-control':true, 'input': true, 'is-danger': errors.has('typeName') }"
                        type="text"
                        placeholder="Enter singular" />
                    <span v-show="errors.has('name singular')" class="help is-danger">{{ errors.first('name singular') }}</span>
                    <!-- <small>No numbers, no special characters.</small> -->
                </div>

                <div class="form-group title-group">
                    <label for="title">Slug</label>
                    <input v-model="formData.slug"
                        name="slug"
                        :class="{'form-control':true, 'input':true}"
                        type="text"
                        placeholder="Enter slug" />
                </div>

                <!-- <div class="form-group">
                    <label>Display Slug</label>
                    <select class="form-control" v-model="formSettingsData.displayContentSlug">
                        <option v-bind:value='true'>Yes</option>
                        <option v-bind:value='false'>No</option>
                    </select>
                </div> -->

                <!-- <div class="form-group title-group">
                    <label for="title">Template path</label>
                    <input v-model="formSettingsData.templatePath"
                        @focus="templatePathWasFocused = true"
                        @blur="templatePathWasFocused = true"
                        name="template path"
                        :class="{'form-control':true, 'input':true}"
                        type="text"
                        placeholder="Enter template path" />
                </div> -->

                <!-- <div class="form-group">
                    <label>Title Minimum Characters Required</label>
                    <el-input-number v-model="formSettingsData.titleMinCharactersRequired" :min="2" :max="6"></el-input-number>
                </div>

                <div class="form-group">
                    <label>Title Max Characters Allowed</label>
                    <el-input-number v-model="formSettingsData.titleMaxCharactersAllowed" :min="7" :max="180"></el-input-number>
                </div> -->

                <div>
                    <component v-for="taxonomy in taxonomies"
                        is="taxonomy"
                        :uniqueId="taxonomy.uniqueId"
                        :id="taxonomy.id"
                        :show-header="showHeaders"
                        :show-content="showContent"
                        @remove-taxonomy="removeTaxonomy"
                        :key="taxonomy.uniqueId">
                    </component>
                </div>

                <div>
                    <button v-on:click="addTaxonomyTrigger(null)" type="button" name="button" class="btn btn-primary btn-sm">Create Taxonomy</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters, mapActions } from 'vuex'

    import SettingsExplorer from './SettingsExplorer.vue'
    import Taxonomy from './taxonomy/Block.vue'
    import TaxonomySettings from './taxonomy/Settings.vue'

    const defaultFormData = {
        name: '',
        name_singular: '',
        slug: ''
    }

    const defaultSettingsData = {
        displayContentSlug: {type: 'boolean', default: false},
        titleMinCharactersRequired: {type: 'number', default: 2},
        titleMaxCharactersAllowed: {type: 'number', default: 120}
    }

    export default {
        components: {
            SettingsExplorer,
            Taxonomy
        },
        data() {
            return {
                formData: null,
                formSettingsData: null,
                templatePathWasFocused: false,

                // some idle as for me
                showHeaders: true,
                showContent: true
            }
        },
        props: {
            id: {type: [Number, String], default: null}
        },
        computed: {
            ...mapGetters('contentTypes/editor', ['taxonomies', 'removedTaxonomies']),
            ...mapState('contentTypes/editor', {
                data: state => state.data,
                storedId: state => state.id
            })
        },
        watch: {
            data(obj, old) {
                this.reinit(obj)
            },
            storedId(val, old) {
                if (!old && val) {
                    this.$router.push({ name: 'content-types.edit', params: { id: val }})
                }
            },
            id(val, old) {
                this.fetchData(val)
            },
            'formData.name'(val, old) {
                if(this.templatePathWasFocused || this.id) return
                this.formSettingsData.templatePath = _.kebabCase(val)
            }
        },
        methods: {
            ...mapActions('contentTypes/editor', ['setItem', 'setTaxonomies', 'addTaxonomy', 'removeTaxonomy', 'fetch', 'save']),
            ...mapActions('contentTypes/index', {
                'fetchContentTypesList': 'fetch'
            }),
            processSettings(rawData) {
                return _(rawData)
                    .map(i => _.pick(i, 'key', 'value', 'type'))
                    .keyBy('key')
                    .mapValues(o => {return (o.type == 'boolean') ? this.stringToBoolean(o.value) : o.value })
                    .value()
            },
            reinit(rawData = null) {
                let generalData = _.defaults({}, defaultFormData)
                let settingsData = _.mapValues(defaultSettingsData, o => (o.default === undefined) ? null : o.default)

                if (rawData) {
                    generalData = _.pick(rawData, _.keys(defaultFormData))
                    if (rawData.settings && rawData.settings.length) {
                        settingsData = this.processSettings(rawData.settings)
                    }
                    if (rawData.taxonomies && rawData.taxonomies.length) {
                        this.setTaxonomies({data: null})
                        _.forEach(rawData.taxonomies, (o, k) => {
                            this.addTaxonomyTrigger(o)
                        })
                    }
                }

                this.formData = generalData
                this.formSettingsData = settingsData
            },
            fetchData(id) {
                this.setItem({id})
                this.fetch()
            },
            triggerSave() {
                this.$validator.validateAll().then( result => {
                    if (result) {
                        this.saveData()
                    }
                })
            },
            saveData() {
                let typeSettings = _(this.formSettingsData)
                    .map((value, key) => {
                        return {
                            key,
                            value,
                            type: (defaultSettingsData[key] && defaultSettingsData[key]['type']) || 'string'
                        }
                    })
                    .keyBy('key')
                    .value()

                let taxonomies = _(this.taxonomies)
                    .map((o, k) => {
                        let data = o.data
                        data.order = k+1

                        return {
                            data,
                            settings: o.settings
                        }
                    })
                    .value()

                let formData = {
                    id: this.id || 0,
                    typeData: this.formData,
                    typeSettings,
                    taxonomies: this.taxonomies,
                    removedTaxonomies: this.removedTaxonomies
                }

                this.save({formData})
                    .then(() => {
                        this.fetchContentTypesList()
                    })
            },
            stringToBoolean(string){
                switch(string.toLowerCase().trim()){
                    case "true": case "yes": case "1": return true;
                    case "false": case "no": case "0": case null: return false;
                    default: return Boolean(string);
                }
            },
            addTaxonomyTrigger(initData = null) {
                let customSettings = TaxonomySettings.customSettings || TaxonomySettings.options.customSettings

                let settings = initData
                    ? _.defaults(
                            initData.settings,
                            _.mapValues(customSettings, object => (object.default === undefined)? null : object.default)
                        )
                    : _.mapValues(customSettings, object => (object.default === undefined) ? null : object.default)

                this.addTaxonomy({
                    data: (initData && _.pick(initData, ['id', 'name', 'name_singular']))
                        || {id: null, name: 'Taxonomy name', name_singular: 'Taxonomy name (singular)'},
                    settingsConfig: customSettings,
                    settings
                })
            },
            onEnd(evt) {
                //
            },
            onMove(evt) {
                return !evt.related.classList.contains('item_disabled')
            },
        },
        created() {
            this.id && this.fetchData(this.id)
            this.reinit()
        }
    }
</script>

<style scoped>
    .btn-primary {
        background-color: rgba(87, 113, 128, 0.09);
        border-color: rgba(54, 127, 169, 0);
    }

    .btn-primary:hover, .btn-primary:active, .btn-primary.hover {
        background-color: rgba(103, 137, 157, 0.19);
    }

    .btn-primary:hover {
        color: #fff;
        background-color: rgba(40, 96, 144, 0.42);
        border-color: rgba(32, 77, 116, 0.17);
    }
</style>

<style>
    .fa-arrows {
        cursor: move;
        font-size: 16px;
        margin-right: 8px;
    }

    .fa-cog, .fa-eye {
        cursor: pointer;
        font-size: 16px;
        margin-right: 8px;
    }

    .content-block {
        margin-bottom: 10px;
    }

    .content-block-header {
        padding: 10px;
        background: rgba(0,0,0, 0.1)
    }
</style>
