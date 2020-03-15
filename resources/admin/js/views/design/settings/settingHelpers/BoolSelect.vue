<template>
    <select class="form-control" v-model="val">
        <option v-for="{value, label} in options" v-bind:value="value">
            {{ label }}
        </option>
    </select>
</template>

<script>
export default {
    props: {
        value: {},
        options: {type: [Object, Array], default: () => [{
            value: true,
            label: "Yes"
        },{
            value: false,
            label: "No"
        }]}
    },
    data() {
        return {
            val: null
        }
    },
    watch: {
        val(val) { this.$emit('input', val) }
    },
    created() {
        this.val = !!+this.value
    }
}
</script>
