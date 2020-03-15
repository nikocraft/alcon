import Vue from 'vue'
import { VueMasonryPlugin } from '~/components/ui/vue-masonry-extended/masonry'
import vmodal from '~/components/ui/modal/index'
import ColorPicker from '~/components/ui/ColorPicker'
import MediaLibrary from '~/components/MediaLibrary'

import VueSelect from '~/components/ui/vue-select-extended/index.vue'
import SceneExplorer from '~/components/SceneExplorer'
import ComponentsTree from '~/components/ComponentsTree'
import ShadowPicker from '~/components/ui/ShadowPicker'
import Border from '~/components/ui/Border'
import BorderAdvanced from '~/components/ui/BorderAdvanced'
import TextShadow from '~/components/ui/TextShadow'
import TextShadowAdvanced from '~/components/ui/TextShadowAdvanced'
import BoxShadow from '~/components/ui/BoxShadow'
import BoxShadowAdvanced from '~/components/ui/BoxShadowAdvanced'
import Px from '~/components/ui/Px'
import PxResponsive from '~/components/ui/PxResponsive'
import SelectResponsive from '~/components/ui/SelectResponsive'
import Color from '~/components/ui/Color'
import Pagination from '~/components/ui/Pagination'

// Prismjs injection
import 'prismjs'
import 'prismjs/themes/prism-okaidia.css'
import PrismEditor from 'vue-prism-editor'
import 'vue-prism-editor/dist/VuePrismEditor.css'
Vue.component('prism-editor', PrismEditor)

Vue.use(VueMasonryPlugin)
Vue.use(vmodal)

Vue.component('v-select', VueSelect)
Vue.component('color-picker', ColorPicker)
Vue.component('media-library', MediaLibrary)
Vue.component('scene-explorer', SceneExplorer)
Vue.component('components-tree', ComponentsTree)
Vue.component('shadow-picker', ShadowPicker)
Vue.component('border', Border)
Vue.component('border-advanced', BorderAdvanced)
Vue.component('text-shadow', TextShadow)
Vue.component('text-shadow-advanced', TextShadowAdvanced)
Vue.component('box-shadow', BoxShadow)
Vue.component('box-shadow-advanced', BoxShadowAdvanced)
Vue.component('px', Px)
Vue.component('px-responsive', PxResponsive)
Vue.component('select-responsive', SelectResponsive)
Vue.component('color', Color)
Vue.component('vue-pagination', Pagination)
