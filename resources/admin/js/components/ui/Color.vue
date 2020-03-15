<template>
    <div class="form-sub-group">
        <el-tabs value="standard">
            <el-tab-pane label="Standard" name="standard">
                <div class="form-group">
                    <color-picker :value="standard" @input="updateProp('standard', $event)"></color-picker>
                    <!-- <input type="text" class="form-control"
                        :value="standard" @input="$emit('update:standard', $event.target.value)"
                    /> -->
                </div>
            </el-tab-pane>

            <el-tab-pane label="Hover" name="hover">
                <div class="form-group">
                    <color-picker :value="hover" @input="updateProp('hover', $event)"></color-picker>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Active" name="active">
                <div class="form-group">
                    <color-picker :value="active" @input="updateProp('active', $event)"></color-picker>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Focus" name="focus">
                <div class="form-group">
                    <color-picker :value="active" @input="updateProp('focus', $event)"></color-picker>
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
            active: {type: String},
            visited:  {type: String},
            focus:  {type: String},
            uniqueId: {type: Number, default: 0},
            maxAllowed: {type: Number, default: 4}
        },
        data() {
            return {
            }
        },
        methods: {
            updateProp (name, value) {
                this.$emit(`update:${name}`, value)
            }
        }
    }
</script>

<style lang="scss" scoped>

    .form-group {
        margin-bottom: 0px;
    }

</style>
