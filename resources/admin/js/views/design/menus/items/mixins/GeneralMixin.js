export default {
  props: {
    uniqueId: {type: Number, default: 0},
    type: {type: String},
    showContent: {type: Boolean, default: false},
    config: {type: Object}
  },
  methods: {
      openSettingsModal () {
        let mode = 'open-settings-modal'
        let params = {
          uniqueId: this.uniqueId,
          type: this.type
        }
        this.$bus.$emit('open-settings-modal', mode, params)
      }
  }
}
