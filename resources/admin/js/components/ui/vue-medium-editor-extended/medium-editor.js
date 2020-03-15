import VueMediumEditor from 'vue2-medium-editor/index.js'

export default {
    extends: VueMediumEditor,

    watch: {
        customTag (val) {
            this.$nextTick(() => {
                this.tearDown()
                this.createAndSubscribe()
            })
        }
    }
}
