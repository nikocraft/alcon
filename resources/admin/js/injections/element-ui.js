import Vue from 'vue'
import {
    InputNumber,
    Button,
    Select,
    Option,
    DatePicker,
    Tabs,
    TabPane,
    Popover,
    Loading,
    MessageBox,
    Message,
    Checkbox,
    CheckboxGroup,
    Notification,
    Collapse,
    CollapseItem,
    Steps,
    Step
} from 'element-ui'
import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'

locale.use(lang)

Vue.use(InputNumber)
Vue.component(Tabs.name, {
    extends: Tabs,
    methods: {
        addPanes(item) {
            const index = this.$slots.default.indexOf(item.$vnode)
            if (index >= 0) this.panes.splice(index, 0, item)
            else this.panes.push(item)
        }
    }
})
Vue.use(TabPane)
Vue.use(Popover)
Vue.use(Button)
Vue.use(Select)
Vue.use(Option)
Vue.use(DatePicker)
Vue.use(Checkbox)
Vue.use(CheckboxGroup)
Vue.use(Collapse)
Vue.use(CollapseItem)
Vue.use(Steps)
Vue.use(Step)
Vue.use(Loading.directive)
Vue.prototype.$loading = Loading.service

Vue.prototype.$notify = Notification
Vue.prototype.$msgbox = MessageBox
Vue.prototype.$alert = MessageBox.alert
Vue.prototype.$confirm = MessageBox.confirm
Vue.prototype.$prompt = MessageBox.prompt
Vue.prototype.$message = Message
