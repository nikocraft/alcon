<template>
    <textarea class="form-control" v-model="val"></textarea>
</template>

<script>
export default {
    props: {
        value: {type: String}
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
