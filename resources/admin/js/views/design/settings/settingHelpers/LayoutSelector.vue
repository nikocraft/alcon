<template>
    <div class="form-group">
        <div class="website-layouts">
            <div
                v-for="layout in options"
                :key="layout"
                :class="['website-layout', { 'selected': val && (val == layout) }]"
                @click="setLayout(layout)">
                <template v-if="layout=='header-main-footer'">
                    <div style="flex: 1.5; background-color: rgba(0,0,0,0.17);">
                        header
                    </div>
                    <div style="flex: 3; background-color: rgba(0,0,0,0.1);">
                        main
                    </div>
                    <div style="flex: 1.5; background-color: rgba(0,0,0,0.17);">
                        footer
                    </div>
                </template>
                <template v-else-if="layout=='header-main'">
                    <div style="flex: 1; background-color: rgba(0,0,0,0.17);">
                        header
                    </div>
                    <div style="flex: 3; background-color: rgba(0,0,0,0.1);">
                        main
                    </div>
                </template>
                <template v-else-if="layout=='aside-main'">
                    <div style="flex: 1; background-color: rgba(49,57,74,0.2);">
                        aside
                    </div>
                    <div style="flex: 3; background-color: rgba(0,0,0,0.1);">
                        main
                    </div>
                </template>
                <template v-else-if="layout=='header-aside-main'">
                    <div style="flex: 1; background-color: rgba(0,0,0,0.17);">
                        header
                    </div>
                    <div style="flex: 3;">
                        <div style="flex: 1; height: 100%; background-color: rgba(49,57,74,0.2); display: flex; justify-content: center; align-items: center;">
                            aside
                        </div>
                        <div style="flex: 3; height: 100%; background-color: rgba(0,0,0,0.1); display: flex; justify-content: center; align-items: center;">
                            main
                        </div>
                    </div>
                </template>
                <template v-else-if="layout=='header-aside-main-footer'">
                    <div style="flex: 1; background-color: rgba(0,0,0,0.17);">
                        header
                    </div>
                    <div style="flex: 3;">
                        <div style="flex: 1; height: 100%; background-color: rgba(49,57,74,0.2); display: flex; justify-content: center; align-items: center;">
                            aside
                        </div>
                        <div style="flex: 3; height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                            <div style="flex: 3; width: 100%; background-color: rgba(0,0,0,0.1); display: flex; justify-content: center; align-items: center;">
                                main
                            </div>
                            <div style="flex: 1.2; width: 100%; background-color: rgba(0,0,0,0.17); display: flex; justify-content: center; align-items: center;">
                                footer
                            </div>
                        </div>
                    </div>
                </template>
                <template v-else-if="layout=='aside-header-main-footer'">
                    <div style="flex: 1; height: 100%; background-color: rgba(49,57,74,0.2); display: flex; justify-content: center; align-items: center;">
                        aside
                    </div>
                    <div style="flex: 3; flex-direction: column;">
                        <div style="flex: 1.5; width: 100%; background-color: rgba(0,0,0,0.17); display: flex; justify-content: center; align-items: center;">
                            header
                        </div>
                        <div style="flex: 3; width: 100%; background-color: rgba(0,0,0,0.1); display: flex; justify-content: center; align-items: center;">
                            main
                        </div>
                        <div style="flex: 1.2; width: 100%; background-color: rgba(0,0,0,0.17); display: flex; justify-content: center; align-items: center;">
                            footer
                        </div>
                    </div>
                </template>
                <template v-else-if="layout=='aside-header-main'">
                    <div style="flex: 1; height: 100%; background-color: rgba(49,57,74,0.2); display: flex; justify-content: center; align-items: center;">
                        aside
                    </div>
                    <div style="flex: 3; flex-direction: column;">
                        <div style="flex: 1.2; width: 100%; background-color: rgba(0,0,0,0.2); display: flex; justify-content: center; align-items: center;">
                            header
                        </div>
                        <div style="flex: 3; width: 100%; background-color: rgba(0,0,0,0.1); display: flex; justify-content: center; align-items: center;">
                            main
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: {type: [String, Number]},
        options: {}
    },
    data() {
        return {
            val: null
        }
    },
    watch: {
        val(val) { this.$emit('input', val) }
    },
    methods: {
        supportsLayout(layout) {
            return this.val.includes(layout)
        },
        setLayout(layout) {
            this.val = layout
        }
    },
    mounted() {
        this.val = this.value
    }
}
</script>

<style lang="scss">
    .website-layout.selected {
        border: 2px solid #20a0ff78;
    }
</style>
