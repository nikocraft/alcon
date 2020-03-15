<template>
    <div class="form-sub-group">
        <el-tabs value="standard">
            <el-tab-pane label="Standard" name="standard">
                <div class="form-group">
                    <border :value="standard" @input="$emit('update:standard', $event)"></border>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Hover" name="hover">
                <div class="form-group">
                    <border :value="hover" @input="$emit('update:hover', $event)"></border>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Active" name="active">
                <div class="form-group">
                    <border :value="active" @input="$emit('update:active', $event)"></border>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Focus" name="focus">
                <div class="form-group">
                    <border :value="focus" @input="$emit('update:focus', $event)"></border>
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
