<template>
    <div class="form-group">
        <div class="content-layouts">
            <div @click="setLayout(layout)" v-for="layout in options" :key="layout" :class="['content-layout', { 'selected': val && (val == layout) }]">
                <template v-if="layout=='content-fullwidth'">
                    <div class="content-fullwidth">
                        Content
                    </div>
                </template>
                <template v-else-if="layout=='content-sidebar'">
                    <div class="content-item">
                        Content
                    </div>
                    <div class="sidebar-item">
                        Sidebar
                    </div>
                </template>
                <template v-else-if="layout=='sidebar-content'">
                    <div class="sidebar-item">
                        Sidebar
                    </div>
                    <div class="content-item">
                        Content
                    </div>
                </template>
                <template v-else-if="layout=='none'">
                    <div style="display: flex; flex: 1; justify-content: center; align-items: center;">
                        None
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        value: { type: [String, Number] }
    },
    data() {
        return {
            val: null,
            options: [ "content-fullwidth", "content-sidebar", "sidebar-content", "none" ]
        }
    },
    watch: {
        val(val) { this.$emit('input', val) }
    },
    methods: {
        setLayout(layout) {
            this.val = layout
        }
    },
    mounted() {
        this.val = this.value
    }
}
</script>

<style lang="scss" scoped>
    .content-layout {
        height: 60px !important;
    }
    .content-layout.selected {
        border: 2px solid #20a0ff78;
    }
</style>