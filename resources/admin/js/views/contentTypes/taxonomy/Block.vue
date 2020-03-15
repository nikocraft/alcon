<template>
    <div :id="uniqueId" class="content-block">
        <div v-show="showHeader" class="content-block-header">
            <i class="lo-icon lo-icon-cog" @click="openSettingsModal()" aria-hidden="true"></i>
            {{ data.name }} <required v-if="settings.required=='1'" class="required"></required>
            <button v-on:click="removeBlock" type="button" class="close"><span aria-hidden="true">×</span></button>
        </div>

        <div class="content-block-body" style="margin-top: 1px;">
            <div class="form-group">
                <label v-show="!showHeader">{{ data.name }}</label> <required v-if="!showHeader && settings.required=='1'" class="required"></required>
                <div>
                    - {{ data.name }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapGetters } from 'vuex'
    import TaxonomySettings from './Settings.vue'

    export default {
        data() {
            return {
                //
            }
        },
        props: {
            uniqueId: {type: [String, Number], default: null},
            showHeader: {type: Boolean, default: false},
            showContent: {type: Boolean, default: false}
        },
        computed: {
            ...mapGetters('contentTypes/editor', ['taxonomyData', 'taxonomySettings']),
            data() { return this.taxonomyData(this.uniqueId) },
            settings() { return this.taxonomySettings(this.uniqueId) }
        },
        watch: {
            //
        },
        methods: {
            removeBlock() {
                this.$emit('remove-taxonomy', {uniqueId: this.uniqueId})
            },
            openSettingsModal() {
                let params = {
                    uniqueId: this.uniqueId
                }
                this.$bus.$emit('open-settings-modal', TaxonomySettings, params)
            }
        }
    }
</script>

<style lang="scss">
    //
</style>
