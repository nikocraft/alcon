import { processSettingsConfig } from '~/utils/helpers.js'
import { mapActions } from 'vuex'

export default {
  props: {
    storePath: {type: String, default: 'content/editor'},
    uniqueId: {type: Number, default: 0},
    type: {type: String},
    ancestorSettings: {type: Object},
    showHeaders: {type: Boolean, default: false},
    showLabels: {type: Boolean, default: true},
    allowDraggable: {type: Boolean, default: true},
    containerPreview: {type: Boolean, default: true},
    config: {type: Object}
  },
  data () {
    return {
      activeTab: 'general',
      optionsAlignSelf: [
        {key: 'auto', value: 'Auto'},
        {key: 'flex-start', value: 'Start'},
        {key: 'center', value: 'Center'},
        {key: 'flex-end', value: 'End'},
        {key: 'baseline', value: 'Baseline'},
        {key: 'stretch', value: 'Stretch'},
      ]
    }
  },
  computed: {
    propagatedSettingsList () {
      return _(processSettingsConfig(this.type))
        .pickBy('childPropagation')
        .keysIn()
        .value()
    },
    propagatedSettings () {
      let settings = this.settings
      let propagatedSettingsList = this.propagatedSettingsList
      if (!settings) return undefined

      let output = {}
      _.forEach(propagatedSettingsList, k => {
        output[k] = settings[k]
      })
      return output
    }
  },
  methods: {
    ...mapActions({
      updateItemContent(dispatch, payload) {
        return dispatch(`${this.storePath}/updateItemContent`, payload)
      },
      updateItemSettings(dispatch, payload) {
        return dispatch(`${this.storePath}/updateItemSettings`, payload)
      },

      updateItemsList(dispatch, payload) {
        return dispatch(`${this.storePath}/updateItemsList`, payload)
      },
      addItem(dispatch, payload) {
        return dispatch(`${this.storePath}/addItem`, payload)
      },
      removeItem(dispatch, payload) {
        return dispatch(`${this.storePath}/removeItem`, payload)
      },
      addTemplateItem(dispatch, payload) {
        return dispatch(`${this.storePath}/addTemplateItem`, payload)
      },
    }),
    openSettingsModal () {
      let mode = 'open-settings-modal'
      let params = {
        storePath: this.storePath,
        uniqueId: this.uniqueId,
        type: this.type,
        ancestorSettings: this.ancestorSettings
      }
      this.$bus.$emit('open-settings-modal', mode, params)
    }
  }
}
