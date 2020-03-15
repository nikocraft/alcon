<template>
    <div class="color-picker-block">
        <label>{{label}}</label>
        <el-popover
            ref="colorpicker-popover"
            placement="bottom-end"
            width="auto"
            v-model="pickerVisible"
            popper-class="-padding-free">
            <chrome-picker :value="color" @input="processColor" class="vc-sketch_in-tooltip"/>
        </el-popover>

        <div class="color-wrapper" style="cursor: pointer;">
            <div class="color" :style="{ backgroundColor: color||null }" v-popover:colorpicker-popover></div>
        </div>
    </div>
</template>

<script>
import Chrome from '~/components/ui/vue-color-extended/Chrome.vue'

export default {
    components: {
        'chrome-picker': Chrome
    },
    props: {
        value: {type: String},
        label: {type: String, default: 'Label'},
    },
    data() {
        return {
            pickerVisible: false,
            color: this.value
        }
    },
    watch: {
        value (value, old) {
            this.color = value
        }
    },
    methods: {
        processColor (val) {
            this.color = this.processColorObj(val)
            this.$emit('input', this.color)
        },
        processColorObj (o) {
            if (!o) return ''
            if (!_.isObject(o)) return o

            let val = ''
            if (o.a == 1) {
                val = o.hex
            } else if (o.rgba) {
                val = `rgba(${o.rgba.r},${o.rgba.g},${o.rgba.b},${o.rgba.a})`
            }

            return val
        }
    }
}
</script>


<style lang="scss" scoped>
    .color-picker-block {
        margin-right: 8px;
        margin-bottom: 15px;
        width: 12%;
        height: 60px;
    }

    .color {
        height: 30px;
    }

    .color-wrapper {
        background-image: linear-gradient(45deg, #a0a0a0 25%, transparent 25%), linear-gradient(-45deg, #a0a0a0 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #a0a0a0 75%), linear-gradient(-45deg, transparent 75%, #a0a0a0 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    }

    .vc-chrome.vc-sketch_in-tooltip {
        box-shadow: none;
    }

    .-padding-free {
        padding: 1px;
    }

    .colorpicker-component .input-group-addon i {
        display: inline-block;
        cursor: pointer;
        height: 16px;
        vertical-align: text-top;
        width: 16px;
    }
</style>
