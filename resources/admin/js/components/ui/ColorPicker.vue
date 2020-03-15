<template>
    <div class="input-group colorpicker-component">
        <el-popover
            ref="colorpicker-popover"
            placement="bottom-end"
            width="auto"
            v-model="pickerVisible"
            popper-class="-padding-free"
        >
            <chrome-picker :value="color" @input="processColor" class="vc-sketch_in-tooltip"/>
        </el-popover>
        <input type="text" v-model="color" placeholder="None" class="form-control"/>
        <span class="input-group-addon" v-popover:colorpicker-popover>
            <span class="color" :style="{ backgroundColor: color||null }">&nbsp;</span>
        </span>
    </div>
</template>

<script>
import Chrome from '~/components/ui/vue-color-extended/Chrome.vue'

export default {
    components: {
        'chrome-picker': Chrome
    },
    props: {
        value: {type: String}
    },
    data() {
        return {
            pickerVisible: false,
            color: this.value || ''
        }
    },
    watch: {
        value (value, old) {
            this.color = value || ''
        },
        color (value, old) {
            this.$emit('input', value)
        }
    },
    methods: {
        processColor (val) {
            this.color = this.processColorObj(val)
        },
        processColorObj (o) {
            if (!o) return ''
            if (!_.isObject(o)) return o

            let val = ''
            if (o.a == 1) {
                val = o.hex
            } else if (o.a == 0) {
                val = 'transparent'
            } else if (o.rgba) {
                val = `rgba(${o.rgba.r},${o.rgba.g},${o.rgba.b},${o.rgba.a})`
            }

            return val
        }
    }
}
</script>


<style>
    .vc-chrome.vc-sketch_in-tooltip {
        box-shadow: none;
    }

    .-padding-free {
        padding: 1px;
    }

    .input-group .input-group-addon {
        color: #b8c7ce;
        border-color: rgb(31, 38, 42);
        background-color: #1e292e;
    }

    .colorpicker-component .input-group-addon {
        padding: 0;
        background-image: linear-gradient(45deg, #a0a0a0 25%, transparent 25%), linear-gradient(-45deg, #a0a0a0 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #a0a0a0 75%), linear-gradient(-45deg, transparent 75%, #a0a0a0 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    }

    .colorpicker-component .input-group-addon .color {
        display: inline-block;
        cursor: pointer;
        height: 31px;
        vertical-align: text-top;
        width: 35px;
        border: 5px solid #222d32 !important;
    }
</style>
