import Vue from 'vue'
import autosize from 'autosize'

export default {
	bind: function(el, binding) {
		var tagName = el.tagName
		if (tagName == 'TEXTAREA') {
			Vue.nextTick(() => {
				autosize(el)
			})
		}
	},

	componentUpdated: function(el, binding, vnode) {
		var tagName = el.tagName
		if (tagName == 'TEXTAREA') {
			Vue.nextTick(() => {
				autosize.update(el)
			})
		}
	},

	unbind: function(el) {
		autosize.destroy(el)
	}
}
