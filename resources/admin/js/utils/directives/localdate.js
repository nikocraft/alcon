import { format } from 'date-fns'

// Example of usage:
// <span v-localdate :format="'yyyy-MM'">{{ $content->published_at }}</span>
// <span v-localdate="'{{ $content->published_at }}'" :format="'yyyy-MM'"></span>

export default {
    bind: function(el, binding, vnode) {
        let date = binding.value || el.textContent
        let formatStr = (vnode.data.attrs && vnode.data.attrs.format) || 'yyyy-MM-dd HH:mm'
        let output = format(new Date(`${date} UTC`), formatStr)
        el.innerHTML = output
    }
}
