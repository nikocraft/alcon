<template>
    <div class="form-group">
        <input type="text" v-model="input" class="form-control">
    </div>
</template>

<script>
    export default {
        props: {
            value: {type: String, default: ''},
        },
        data() {
            return {
                input: this.value
            }
        },
        watch: {
            input(val, old) {
                this.$emit('input', this.input)
            },
        },

    }
</script>
