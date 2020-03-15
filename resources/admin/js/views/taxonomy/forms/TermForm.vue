<template>
    <v-modal ref="termModal"
        name="term-modal"
        :classes="['v--modal', 'modal-content']"
        :overlay="true"
        transition="nice-modal-fade"
        :clickToClose="true"
        :width="600"
        :height="'75%'"
        :delay="100"
        :adaptive="true"
        :resizable="false">
        <div class="modal-header">
            <h4 class="modal-title">{{ modalTitle }} Term</h4>
        </div>
        <div class="modal-body" v-if="formData">
            <div class="form-group">
                <label>Name</label>
                <input type="text"
                    name="name"
                    v-validate="'required'"
                    v-model="formData.name"
                    class="form-control input"
                    :class="{'is-danger': errors.has('name') }"
                >
                <span v-if="errors.has('name')" class="help is-danger">{{ errors.first('name') }}</span>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" class="form-control" v-model="formData.description">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary pull-right"
                @click="processTrigger()"
                v-text="(modalMode=='update') ? 'Update' : 'Create'"
            ></button>
            <button type="button" class="btn btn-default pull-right" @click="$modal.hide('term-modal')">Cancel</button>
        </div>
    </v-modal>
</template>

<script>
    import { mapState, mapGetters, mapActions } from 'vuex'

    export default {
        data() {
            return {
                modalMode: 'create',
                formData: null,
                callback: null
            }
        },
        props: {
            taxonomy: {type: Object, default: null},
        },
        computed: {
            ...mapState('taxonomy', ['termProcessLoading']),
            modalTitle() {
                return (this.modalMode == 'create') ? 'Create' : 'Edit'
            }
        },
        methods: {
            ...mapActions('taxonomy', ['updateTerm', 'removeTerm']),
            reinitForm(term) {
                this.formData = term || {
                    id: 0,
                    name: '',
                    description: ''
                }

                this.modalMode = term ? 'update' : 'create'
            },
            processTrigger() {
                this.$validator.validateAll().then(result => {
                    result && this.process()
                })
            },
            process() {
                this.updateTerm({term: this.formData})
                    .then(() => {
                        this.$modal.hide('term-modal')
                        this.callback && this.callback()
                    })
            }
        },
        created() {
            this.$bus.$on('open-modal:term-form', (params) => {
                let {term = null, cb = null} = params

                this.reinitForm(term)
                this.callback = cb

                this.$modal.show('term-modal')
            })
            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('open-modal:term-form')
            })
        }
    }
</script>
