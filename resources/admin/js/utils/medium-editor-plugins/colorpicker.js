import Vue from 'vue'
import MediumEditor from 'medium-editor'
import ColorpickerTooltip from '~/components/ui/ColorpickerTooltip'

class ColorPicker {
    constructor() {
        this.currentTextSelection
        this.pickerInstance = undefined
        this.pickerExtension = undefined

        this.initPickerExtension()
    }

    setColor(color) {
        let finalColor = color ? color : ''

        // var toolbar = this.getExtensionByName('toolbar')
        // if (toolbar) {
        //     toolbar.checkState()
        // }

        this.pickerExtension.base.restoreSelection()
        this.pickerExtension.base.checkSelection()
        this.pickerExtension.document.execCommand("styleWithCSS", false, true)
        this.pickerExtension.document.execCommand("foreColor", false, finalColor)
    }

    initPicker(params) {
        let self = this
        this.pickerInstance = new Vue({
            components: { ColorpickerTooltip },
            data: function() {
                return {
                    color: (params && params.color) || null,
                    active: false
                }
            },
            render: function (createElement) {
                return createElement(ColorpickerTooltip, {
                    props: {
                        value: this.color,
                        active: this.active
                    },
                    on: {
                        'change-color': this.changeColorHandler,
                        'open-picker': this.openPickerHandler,
                        'close-picker': this.closePickerHandler,
                        'input': this.inputHandler
                    }
                })
            },
            methods: {
                inputHandler(val) {
                    this.color = val
                },
                changeColorHandler(val) {
                    self.setColor((val && val.hex) ? val.hex : val)
                },
                openPickerHandler(val) {
                    // self.pickerExtension.handleClick.apply(self.pickerExtension)

                    self.pickerExtension.base.saveSelection()
                    let styleObj = getComputedStyle( self.pickerExtension.base.getSelectedParentElement() )
                    let currentTextColor = styleObj.color
                    this.color = currentTextColor
                },
                closePickerHandler(val) {
                    self.setColor((val && val.hex) ? val.hex : val)
                },
                setActive(val) {
                    let active = (val === undefined)
                        ? !this.active
                        : !!val

                    Vue.set(this, 'active', active)
                }
            }
        }).$mount()
    }

    initPickerExtension() {
        let self = this
        let ColorPickerExtension = MediumEditor.Extension.extend({
            name: "colorPicker",
            action: "applyForeColor",
            aria: "color picker",

            init: function () {
                self.initPicker()
            },
            destroy: function () {
                self.pickerInstance.$destroy()
            },
            getButton: function () {
                return self.pickerInstance.$el
            },
            handleClick: function (event) {
                self.currentTextSelection = this.base.exportSelection()
                let styleObj = getComputedStyle( this.base.getSelectedParentElement() )
                let currentTextColor = styleObj.color

                self.pickerInstance.color = currentTextColor

                // event.preventDefault()
                // event.stopPropagation()

                // let action = this.getAction()
                // action && this.execAction(action)
            },
            isAlreadyApplied: function (node) {
                return node.nodeName.toLowerCase() === 'span'
            },
            isActive: function () {
                return self.pickerInstance.active
            },
            setInactive: function () {
                self.pickerInstance.setActive(false)
            },
            setActive: function () {
                self.pickerInstance.setActive(true)
            }
        })

        this.pickerExtension = new ColorPickerExtension()
    }
}

export default ColorPicker
