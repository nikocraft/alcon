import Vue from 'vue'

_.each(window.customComponents, (o, k) => {
    Vue.component(o.block.name, {
        ...o.block,
        mixins: [TPMixin]
    })
    Vue.component(o.settings.name, {
        ...o.settings,
        mixins: [TPSettingsMixin]
    })
})
