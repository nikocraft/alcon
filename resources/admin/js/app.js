/*
 * Laraone CMS SPA
 * Copyright (C) Reimagined Works
 * Homepage:  https://laraone.com
 * License: https://laraone.com/license
*/

require('./bootstrap')
require('./injections/interceptors')

import App from './layouts/App'
import store from './store'
import router from './router'
import VueBus from '~/vue-bus'

import './injections/filters'
import './injections/element-ui'
import './injections/ui'
import './injections/custom-components'
import './injections/validation'

Vue.use(VueBus)

new Vue({
    router,
    store,
    ...App
})
