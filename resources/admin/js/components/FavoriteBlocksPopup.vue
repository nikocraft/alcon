<template>
    <v-modal ref="favoritesModal"
        name="favorites-modal"
        :classes="['v--modal', 'modal-content', 'modal-content_auto-responsive', 'modal-content_content-blocks']"
        :overlay="true"
        transition="nice-modal-fade"
        :clickToClose="false"
        :customWidth="true"
        :height="'96%'"
        :minHeight="300"
        :delay="100"
        :adaptive="true"
        :resizable="false"
        :widthPadding="15">
        <div class="modal-header grabbable">
            <button type="button" class="close" aria-label="Close" @click="$modal.hide('favorites-modal')">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title animated">{{ modalTitle }}</h4>
        </div>
        <div class="modal-body">
            <perfect-scrollbar class="b-comp-list b-comp-list_favorites">
                <label v-for="item in standardBlocksList" :key="item.id" :class="['b-comp-list_item', { 'selected': checkedComponents && checkedComponents.length && (checkedComponents.indexOf(item.block)+1) }]">
                    <input type="checkbox" autocomplete="off" :value="item.block" :name="item.block + 'FavoriteBlock'" v-model="checkedComponents">
                    {{item.title}}
                </label>
                <div class="clearfix"></div>
            </perfect-scrollbar>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="saveTrigger" name="saveFavoriteComponent" :disabled="componentsSaving" v-text="(componentsSaving)?'Saving...':'Save'"></button>
            <button type="button" class="btn btn-primary" @click="$modal.hide('favorites-modal')" name="cancelFavoriteComponentModal">Cancel</button>
        </div>
    </v-modal>
</template>

<script>
    import { mapActions, mapState, mapGetters } from 'vuex'
    import PerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'
    import { getComponentsList } from '~/utils/helpers.js'

    export default {
        components: {
            PerfectScrollbar
        },
        data() {
            return {
                modalTitle: 'Favorites',
                standardBlocksList: null,
                tpBlocksList: null,
                checkedComponents: [],
                componentsSaving: false
            }
        },
        computed: {
            ...mapGetters('content/editor', ['favoriteBlocks'])
        },
        watch: {
            favoriteBlocks(val, old) {
                this.checkedComponents = val
            }
        },
        methods: {
            ...mapActions('content/editor', [
                'saveEditorSettings'
            ]),
            saveTrigger() {
                this.componentsSaving = true
                this.saveEditorSettings({
                    data: {
                        favoriteBlocks: this.checkedComponents.join(',')
                    }
                }).then(() => {
                    this.componentsSaving = false
                    this.$modal.hide('favorites-modal')
                })
            }
        },
        mounted() {
            this.$bus.$on('open-favorites-modal', (params = {}) => {
                this.standardBlocksList = getComponentsList(params)
                this.tpBlocksList = getComponentsList({componentsType: 'tp'})
                this.$modal.show('favorites-modal')
            })
            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('open-favorites-modal')
            })
        }
    }
</script>
