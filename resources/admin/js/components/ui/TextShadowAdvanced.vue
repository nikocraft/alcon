<template>
    <div class="form-sub-group">
        <el-tabs value="standard">
            <el-tab-pane label="Standard" name="standard">
                <div class="form-group">
                    <text-shadow :value="standard" @input="$emit('update:standard', $event)"></text-shadow>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Hover" name="hover">
                <div class="form-group">
                    <text-shadow :value="hover" @input="$emit('update:hover', $event)"></text-shadow>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Active" name="active">
                <div class="form-group">
                    <text-shadow :value="active" @input="$emit('update:active', $event)"></text-shadow>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Focus" name="focus">
                <div class="form-group">
                    <text-shadow :value="focus" @input="$emit('update:focus', $event)"></text-shadow>
                </div>
            </el-tab-pane>
        </el-tabs>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex'
    export default {
        props: {
            value: {type: String},
            standard: {type: String},
            hover: {type: String},
            active:  {type: String},
            focus:  {type: String},
            uniqueId: {type: Number, default: 0}
        }
    }
</script>

<style lang="scss" scoped>

    .form-group {
        margin-bottom: 0px;
    }

</style>
