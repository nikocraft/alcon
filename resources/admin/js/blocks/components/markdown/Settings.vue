<template>
    <div slot="block-settings">
        <div class="form-group">
            <label>Block Title</label>
            <input type="text" class="form-control" v-model="settings.blockTitle">
        </div>
        <div class="form-group">
            <label>Show Toolbar</label>
            <select class="form-control" v-model="settings.showToolbar">
                <option :value='true'>Yes</option>
                <option :value='false'>No</option>
            </select>
        </div>
    </div>
</template>

<script>
    import SettingsMixin from '../../mixins/SettingsMixin'
    import { mapGetters, mapActions } from 'vuex'

    export default {
        mixins: [SettingsMixin],
        customSettings: {
            blockTitle: {type: String, default: 'Markdown'},
            renderTitle: {type: Boolean, default: false},
            showToolbar: {type: Boolean, default: true},
        }
    }
</script>
