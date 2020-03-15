<template>
    <div>
        <div class="input-group">
            <input type="text" v-model="val" class="form-control"/>
            <span @click="openMediaModal" class="input-group-addon" style="cursor: pointer; border-left: 0;">Select</span>
        </div>
        <!-- <div v-if="val" class="form-group">
            <img :src="'/'+val" style="max-width: 180px; height: auto;">
        </div> -->
    </div>
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
    methods: {
        openMediaModal() {
            var mode = 'insertImage'
            var params = new Object

            params.cb = (image) => {
                this.val = '/' + image.path + image.filename + '.' + image.extension
            }

            this.$bus.$emit('open-media-modal', mode, params)
        },
    },
    mounted() {
        this.val = this.value
    }
}
</script>
