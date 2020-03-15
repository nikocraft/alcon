<template>
    <v-modal ref="settingsModal"
        name="settings-modal"
        :classes="['v--modal', 'modal-content', 'modal-content_settings-modal']"
        :overlay="true"
        transition="nice-modal-fade"
        :clickToClose="false"
        :width="400"
        :height="500"
        :min-width="250"
        :min-height="300"
        :docked-width="300"
        :delay="100"
        :adaptive="false"
        :resizable="true"
        :draggable="'.modal-header'">

        <div class="modal-header grabbable">
            <button type="button" class="close" aria-label="Close" @click="$modal.hide('settings-modal')">
                <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title animated">Settings</h4>
        </div>

        <VuePerfectScrollbar class="modal-body" style="padding-top 5px; !important">
            <component v-if="settingsComponent"
                v-bind:is="settingsComponent"
                :uniqueId="uniqueId"
                :key="uniqueId" />
        </VuePerfectScrollbar>

        <div class="modal-footer">
            <button type="button" class="btn btn-primary" @click="closeModal">Close</button>
        </div>
    </v-modal>
</template>

<script>
    import VuePerfectScrollbar from '~/components/ui/perfect-scrollbar-extended'

    export default {
        components: {
            VuePerfectScrollbar
        },
        data() {
            return {
                mode: 'scene-explorer-all',
                uniqueId: 0,
                settingsComponent: undefined,
            }
        },
        methods: {
            closeModal() {
                this.$modal.hide('settings-modal')
                this.settingsComponent = undefined
                this.uniqueId = null
            }
        },
        mounted () {
            this.$bus.$on('open-settings-modal', (component, params) => {
                this.settingsComponent = component
                this.uniqueId = params.uniqueId
                this.$modal.show('settings-modal')
            })
            this.$once('hook:beforeDestroy', () => {
                this.$bus.$off('open-settings-modal')
            })
        }
    }
</script>

<style lang="scss">
    .modal-content_settings-modal {
        .modal-body {
            overflow: hidden;
        }

        &.docked {
            width: 250px !important;
        }
    }
</style>
