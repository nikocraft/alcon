<template>
    <div>
        <div v-if="responsiveModeVisible">
            <el-tabs value="xl">
                <el-tab-pane v-for="item in responsiveTabData" :label="item.label" :name="item.name" :key="item.name">
                    <div class="form-group">
                        <component
                            v-bind:is="component"
                            v-model="val[item.name]" />
                        <small>{{ item.desc }}</small>
                    </div>
                </el-tab-pane>
            </el-tabs>
        </div>
        <div v-else>
            <component
                v-bind:is="component"
                v-model="val" />
        </div>
    </div>
</template>

<script>

const responsiveTabData = [{
    label: 'XL',
    name: 'xl',
    desc: 'Extra large devices (large desktops)'
}, {
    label: 'LG',
    name: 'l',
    desc: 'Large devices (desktops, less than 1200px)'
}, {
    label: 'MD',
    name: 'm',
    desc: 'Medium devices (tablets, less than 992px)'
}, {
    label: 'SM',
    name: 's',
    desc: 'Small devices (landscape phones, less than 768px)'
}, {
    label: 'XS',
    name: 'xs',
    desc: 'Extra small devices (portrait phones, less than 576px)'
}]

const blankResponsiveObject = {
    xl: '',
    l: '',
    m: '',
    s: '',
    xs: ''
}

export default {
    props: {
        value: {type: [String, Number, Object]},
        label: {type: String},
        component: {}
    },
    data() {
        return {
            val: null,
            responsiveModeVisible: false,
            responsiveTabData: responsiveTabData
        }
    },
    watch: {
        val(val) { this.$emit('input', val) },
        responsiveModeVisible(val, old) {
            if(val) {
                this.val = this.transformValue(this.val, 'object')
            } else {
                this.val = this.transformValue(this.val, 'string')
            }
        }
    },
    methods: {
        toggleMode(value) {
            this.responsiveModeVisible = _.isUndefined(value)
                ? !this.responsiveModeVisible
                : !!value
        },
        transformValue(value, type='string') {
            let isPlainObject = _.isPlainObject(value)
            if(type == 'string' && isPlainObject) {
                return value['xl']
            } else if(type == 'object' && !isPlainObject) {
                return _.mapValues(blankResponsiveObject, v => { return value })
            }
            return value
        },
        parseValue(value, init = false) {
            if(_.isPlainObject(value)) {
                this.val = _.merge(blankResponsiveObject, value)
                this.toggleMode(true)
            } else {
                this.val = value
            }
        }
    },
    created() {
        this.parseValue(this.value)
    }
}
</script>
