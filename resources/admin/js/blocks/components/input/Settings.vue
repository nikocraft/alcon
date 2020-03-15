<template>
    <div class="block-settings">
        <div class="form-group">
            <label>Block Title</label>
            <input type="text" class="form-control" v-model="settings.blockTitle">
        </div>

        <div class="form-group">
            <label>Input Class</label>
            <input type="text" class="form-control" v-model="settings.inputClass">
        </div>

        <div class="form-group">
            <label>Enable Label</label>
            <select class="form-control" v-model="settings.renderLabel">
                <option v-bind:value='true'>Yes</option>
                <option v-bind:value='false'>No</option>
            </select>
            <small>Show label on the frontend.</small>
        </div>

        <div v-if="settings.renderLabel" class="form-group">
            <label>Label</label>
            <input type="text" class="form-control" v-model="inputContent.label">
        </div>

        <div v-if="settings.renderLabel" class="form-group">
            <label>Render Label Style</label>
            <select class="form-control" v-model="settings.renderLabelStyle">
                <option value='above'>Above</option>
                <option value='inline'>Inline</option>
            </select>
        </div>

        <div v-if="settings.renderLabel" class="form-group">
            <label>Label Class</label>
            <input type="text" class="form-control" v-model="settings.labelClass">
        </div>

        <!-- <div class="form-group">
            <label>Required</label>
            <select class="form-control" v-model="settings.required">
                <option v-bind:value='true'>Yes</option>
                <option v-bind:value='false'>No</option>
            </select>
        </div> -->

        <!-- <div v-show="settings.required" class="form-group">
            <label>Minimum Characters Required</label>
            <el-input-number v-model="settings.minCharactersRequired" :min="1" :max="6"></el-input-number>
        </div> -->

        <!-- <div class="form-group">
            <label>Max Characters Allowed</label>
            <el-input-number v-model="settings.maxCharactersAllowed" :min="7" :max="180"></el-input-number>
        </div> -->
    </div>
</template>

<script>
    import SettingsMixin from '../../mixins/SettingsMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [SettingsMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Input'},
            renderTitle: {type: Boolean, default: false},
            required: {type: Boolean, default: false},
            inputClass: {type: String, default: ''},
            minCharactersRequired: {type: Number, default: 1},
            maxCharactersAllowed: {type: Number, default: 120},
            renderLabel: {type: Boolean, default: false},
            renderLabelStyle: {type: String, default: 'above'},
            labelClass: {type: String, default: 'bold'},
        },
        data() {
            return {
                inputContent: {
                    label: '',
                    value: '',
                }
            }
        },
        computed: {
            content() {
                return this.$store.getters[`${this.storePath}/itemContent`](this.uniqueId)
            }
        },
        watch: {
            inputContent: {
                handler(val) {
                    this.updateItemContent({content: val, uniqueId: this.uniqueId})
                },
                deep: true
            }
        },
        methods: {
            processContent(content) {
                this.inputContent = new Object()
                this.inputContent.label = content.label
                this.inputContent.value = content.value
            }
        },
        mounted() {
            if(this.content) {
                try {
                    this.processContent(JSON.parse(this.content))
                } catch (e) { this.processContent(this.content) }
            }
        },
    }
</script>
