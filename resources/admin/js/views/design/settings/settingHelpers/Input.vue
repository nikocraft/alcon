<template>
    <input type="text" class="form-control" v-model="val">
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
