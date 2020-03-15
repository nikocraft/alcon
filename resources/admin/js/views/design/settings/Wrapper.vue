<template>
    <div class="form-group">
        <label v-if="label">{{ label }}</label>
        <i v-if="responsive" @click="$refs.responsiveSpreader && $refs.responsiveSpreader.toggleMode()" class="lo-icon lo-icon-desktop" title="Responsive"></i>
        <component
            v-if="!responsive"
            v-bind:is="component"
            :value="value"
            v-bind="$attrs"
            v-on="$listeners" />
        <responsiveSpreader
            v-else
            ref="responsiveSpreader"
            :component="component"
            :value="value"
            v-bind="$attrs"
            v-on="$listeners" />
    </div>
</template>

<script>
    import helpers from './settingHelpers'
    import responsiveSpreader from './ResponsiveSpreader.vue'

    export default {
        components: {
            responsiveSpreader
        },
        props: {
            value: {},
            label: {type: String},
            responsive: {type: Boolean, default: false},
            componentName: {type: String}
        },
        data() {
            return {
                component: undefined
            }
        },
        watch: {
            componentName: {
                immediate: true,
                handler(val, old) {
                    this.component = helpers[val]
                }
            }
        }
    }
</script>
