<template>
    <select class="form-control" v-model="val">
        <option v-for="option in options" v-bind:value="option">
            {{ option }}
        </option>
    </select>
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
    mounted() {
        this.val = this.value
    }
}
</script>
