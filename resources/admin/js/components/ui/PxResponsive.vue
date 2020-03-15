<template>
    <div class="form-sub-group">
        <el-tabs value="extraLarge">
            <el-tab-pane label="XL" name="extraLarge">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Default"
                        :value="extraLarge" @input="$emit('update:extraLarge', $event.target.value)"/>
                    <small>Extra large devices (large desktops)</small>
                </div>
            </el-tab-pane>

            <el-tab-pane label="LG" name="large">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Default"
                        :value="large" @input="$emit('update:large', $event.target.value)"/>
                    <small>Large devices (desktops, less than 1200px)</small>
                </div>
            </el-tab-pane>

            <el-tab-pane label="MD" name="medium">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Default"
                        :value="medium" @input="$emit('update:medium', $event.target.value)"/>
                    <small>Medium devices (tablets, less than 992px)</small>
                </div>
            </el-tab-pane>

            <el-tab-pane label="SM" name="small">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Default"
                        :value="small" @input="$emit('update:small', $event.target.value)"/>
                    <small>Small devices (landscape phones, less than 768px)</small>
                </div>
            </el-tab-pane>

            <el-tab-pane label="XS" name="extraSmall">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Default"
                        :value="extraSmall" @input="$emit('update:extraSmall', $event.target.value)"/>
                    <small>Extra small devices (portrait phones, less than 576px)</small>
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
            extraLarge: {type: String},
            large: {type: String},
            medium:  {type: String},
            small:  {type: String},
            extraSmall: {type: String},
            uniqueId: {type: Number, default: 0},
            maxAllowed: {type: Number, default: 4}
        }
    }
</script>

<style lang="scss" scoped>

    .form-group {
        margin-bottom: 0px;
    }

</style>
