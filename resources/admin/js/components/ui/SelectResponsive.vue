<template>
    <div class="form-sub-group">
        <el-tabs value="extraLarge">
            <el-tab-pane label="XL" name="extraLarge">
                <div class="form-group">
                    <!-- <label>Extra Large</label> -->
                    <select class="form-control" :value="extraLarge" @input="$emit('update:extraLarge', $event.target.value)">
                        <option v-for="option in options" :key="option.key" :value="option.key">{{ option.value }}</option>
                    </select>
                    <small>Extra large devices (large desktops)</small>
                </div>
            </el-tab-pane>

            <el-tab-pane label="LG" name="large">
                <div class="form-group">
                    <select class="form-control" :value="large" @input="$emit('update:large', $event.target.value)">
                        <option v-for="option in options" :key="option.key" :value="option.key">{{ option.value }}</option>
                    </select>
                    <small>Large devices (desktops, less than 1200px)</small>
                </div>
            </el-tab-pane>

            <el-tab-pane label="MD" name="medium">
                <div class="form-group">
                    <select class="form-control" :value="medium" @input="$emit('update:medium', $event.target.value)">
                        <option v-for="option in options" :key="option.key" :value="option.key">{{ option.value }}</option>
                    </select>
                    <small>Medium devices (tablets, less than 992px)</small>
                </div>
            </el-tab-pane>

            <el-tab-pane label="SM" name="small">
                <div class="form-group">
                    <select class="form-control" :value="small" @input="$emit('update:small', $event.target.value)">
                        <option v-for="option in options" :key="option.key" :value="option.key">{{ option.value }}</option>
                    </select>
                    <small>Small devices (landscape phones, less than 768px)</small>
                </div>
            </el-tab-pane>

            <el-tab-pane label="XS" name="extraSmall">
                <div class="form-group">
                    <select class="form-control" :value="extraSmall" @input="$emit('update:extraSmall', $event.target.value)">
                        <option v-for="option in options" :key="option.key" :value="option.key">{{ option.value }}</option>
                    </select>
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
            options: {type: Array},
            uniqueId: {type: Number, default: 0},
        }
    }
</script>

<style lang="scss" scoped>

    .form-group {
        margin-bottom: 0px;
    }

</style>
