<template>
    <div class="form-group">
        <div style="display: flex;">
            <div class="form-group-item">
                <px :maxAllowed="maxAllowed" v-model="xOffset"></px>
            </div>
            <div class="form-group-item">
                <px :maxAllowed="maxAllowed" v-model="yOffset"></px>
            </div>
            <div class="form-group-item">
                <px :maxAllowed="maxAllowed" v-model="blur"></px>
            </div>
            <div class="form-group-item">
                <px :maxAllowed="maxAllowed" v-model="spread"></px>
            </div>
            <div class="form-group-item" style="flex: 0.7;">
                <color-picker v-model="color"></color-picker>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            uniqueId: {type: Number, default: 0},
            maxAllowed: {type: Number, default: 1},
            value: {type: String, default: ''},
        },
        data() {
            return {
                split: this.value.split(' '),
                xOffset: this.value.split(' ')[0],
                yOffset: this.value.split(' ')[1],
                blur: this.value.split(' ')[2],
                spread: this.value.split(' ')[3],
                color: this.value.split(' ')[4] ? this.value.split(' ')[4] : '',
            }
        },
        computed: {
            shadow: function () {
                return this.xOffset + ' ' + this.yOffset + ' ' + this.blur + ' ' + this.color
            }
        },
        watch: {
            xOffset(val, old) {
                this.$emit('input', this.shadow)
            },
            yOffset(val, old) {
                this.$emit('input', this.shadow)
            },
            blur(val, old) {
                this.$emit('input', this.shadow)
            },
            color(val, old) {
                this.$emit('input', this.shadow)
            },
        },
        methods: {

        },
        mounted : function() {
        },
    }
</script>

<style lang="scss" scoped>
    .form-group-item {
        flex: 0.2;
        margin-right: 2px;
    }

    .form-group-item:last-child {
        flex: 0.7;
        margin: 0px;
    }
</style>
