<template>
    <color-picker v-model="val"></color-picker>
</template>

<script>
export default {
    props: {
        value: {type: [String, Number]}
    },
    data() {
        return {
            val: null
        }
    },
    watch: {
        val(val) { this.$emit('input', val) }
    },
    mounted() {
        this.val = this.value
    }
}
</script>
