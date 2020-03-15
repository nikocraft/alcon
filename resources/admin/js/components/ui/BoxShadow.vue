<template>
    <div class="form-group">
        <input type="text" class="form-control" v-model="input">
        <!-- <div style="display: flex;">
            <div class="form-group-item">
                <px :maxAllowed="maxAllowed" :px.sync="xOffset"></px>
            </div>
            <div class="form-group-item">
                <px :maxAllowed="maxAllowed" :px.sync="yOffset"></px>
            </div>
            <div class="form-group-item">
                <px :maxAllowed="maxAllowed" :px.sync="blur"></px>
            </div>
            <div class="form-group-item">
                <px :maxAllowed="maxAllowed" :px.sync="spread"></px>
            </div>
            <div class="form-group-item" style="flex: 0.7;">
                <color-picker v-model="color"></color-picker>
            </div>
        </div> -->
    </div>
</template>

<script>
    export default {
        props: {
            maxAllowed: {type: Number, default: 1},
            value: {type: String, default: ''},
        },
        data() {
            return {
                input: this.value
                // xOffset: this.value.split(' ')[0],
                // yOffset: this.value.split(' ')[1],
                // blur: this.value.split(' ')[2],
                // spread: this.value.split(' ')[3],
                // color: this.value.split(' ')[4] ? this.value.split(' ')[4] : '',
            }
        },
        computed: {
            // shadow: function () {
            //     return this.xOffset + ' ' + this.yOffset + ' ' + this.blur + ' ' + this.spread + ' ' + this.color
            // }
        },
        watch: {
            input(val, old) {
                this.$emit('input', this.input)
            }
            // xOffset(val, old) {
            //     this.$emit('input', this.shadow)
            // },
            // yOffset(val, old) {
            //     this.$emit('input', this.shadow)
            // },
            // blur(val, old) {
            //     this.$emit('input', this.shadow)
            // },
            // color(val, old) {
            //     this.$emit('input', this.shadow)
            // },
            // spread(val, old) {
            //     this.$emit('input', this.shadow)
            // },
        }
    }
</script>

<style lang="scss" scoped>

</style>
