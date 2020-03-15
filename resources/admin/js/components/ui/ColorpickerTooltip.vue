<template>
    <div>
        <el-popover
            ref="colorpicker-popover"
            placement="bottom"
            width="auto"
            v-model="pickerVisible"
        >
            <div v-if="pickerVisible">
                <sketch-picker v-model="color" class="vc-sketch_in-tooltip"/>
                <div style="text-align: right; padding: 10px 0 0; border-top: 1px solid #eee;">
                    <el-button size="mini" type="primary" @click="okTrigger">ok</el-button>
                    <el-button size="mini" type="info" @click="cancelTrigger">cancel</el-button>
                </div>
            </div>
        </el-popover>
        <button
            class="medium-editor-action"
            :class="{'medium-editor-button-active': active}"
            v-popover:colorpicker-popover
            title="Text color"
        >
            <i class="lo-icon lo-icon-tint" aria-hidden="true"></i>
        </button>
    </div>
</template>

<script>
import Vue from 'vue'
import { Sketch } from 'vue-color'

export default {
    props: {
        value: {type: String, default: ''},
        active: {type: Boolean, default: false}
    },
    components: {
        'sketch-picker': Sketch
    },
    data() {
        return {
            pickerVisible: false,
            color: this.value || '',
            initialColor: this.value || '',
            initialColorExpected: true
        }
    },
    watch: {
        value(val, old) {
            this.color = val
            if(this.initialColorExpected) {
                this.initialColor = val
                this.initialColorExpected = false
            }
        },
        color(val, old) {
            this.$emit('change-color', val)
        },
        pickerVisible (val, old) {
            if(val && !old) {
                this.initialColorExpected = true
                this.color = this.value
                this.$emit('open-picker')
            }
        }
    },
    methods: {
        chooseColor() {
            this.$emit('change-color', this.color)
        },
        okTrigger() {
            this.chooseColor()
            this.$nextTick(() => {
                this.pickerVisible = false
            })
        },
        cancelTrigger() {
            this.$emit('close-picker', this.initialColor)
            this.$nextTick(() => {
                this.pickerVisible = false
            })
        }
    }
}
</script>

<style>
    .vc-sketch.vc-sketch_in-tooltip {
        box-shadow: none;
        padding: 0;
    }
</style>
