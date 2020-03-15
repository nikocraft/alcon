<template>
    <div v-if="data.settings">
        <div v-for="setting in data.settings" :key="setting.key">
            <div v-if="setting.type == 'section'">
                <div class="form-subheader">
                    {{ setting.label }}
                </div>
                <settings-renderer
                    class="form-group-sub"
                    :data="setting"
                    :settings.sync="settings[setting.key]"
                />
            </div>
            <dependency-analyser
                v-else
                :dependency="setting.meta ? setting.meta.dependency : null"
                :value="getDependencyValue(setting.meta ? setting.meta.dependency : {})" >
                <wrapper
                    :componentName="getHelperName(setting)"
                    :label="setting.label"
                    v-model="settings[setting.key]['value']"
                    v-bind="setting.meta||{}" />
            </dependency-analyser>
        </div>
    </div>
</template>

<script>
    import dependencyAnalyser from './DependencyAnalyser.js'
    import wrapper from './Wrapper.vue'

    export default {
        name: 'settings-renderer',
        components: {
            dependencyAnalyser,
            wrapper
        },
        inject: ['rootSettings'],
        props: {
            data: {},
            settings: {}
        },
        methods: {
            getHelperName(item) {
                let realName = item.meta && item.meta.uiType && item.meta.uiType+'Helper' || 'inputHelper'
                return realName
            },
            getDependencyValue({on} = {}) {
                if(!on) return null

                let valuePath = on.split('.')
                valuePath.push('value')

                return _.get(
                    (valuePath.length <= 2) ? this.settings : this.rootSettings,
                    valuePath,
                    null)
            }
        }
    }
</script>
