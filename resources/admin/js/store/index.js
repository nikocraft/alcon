import Vue from 'vue'
import Vuex from 'vuex'

import contentTypes from './modules/contentTypes/init'
import content from './modules/content/init'
import taxonomy from './modules/taxonomy/init'
import comments from './modules/comments/init'
import menus from './modules/menus/init'
import widgets from './modules/widgets/init'

Vue.use(Vuex)

const store = new Vuex.Store({
    strict: false,
    modules: {
        contentTypes,
        content,
        taxonomy,
        comments,
        menus,
        widgets
    }
})

export default store
