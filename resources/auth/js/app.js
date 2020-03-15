/*
 * Laraone CMS SPA
 * Copyright (C) Reimagined Works
 * Homepage:  https://laraone.com
 * License: https://laraone.com/license
*/

require('./bootstrap')

import Vue from 'vue'
import VeeValidate from 'vee-validate'
import { Checkbox, Notification } from 'element-ui'

import Auth from './Auth.vue'

const config = {
  events: 'change',
  dictionary: {
      en: {
          messages: {
              email: 'Enter Valid Email',
              required: 'Required Field',
              min: (field, [length]) => `Min ${length} Chars`,
              alpha: 'Only Alphabetic Chars',
              alpha_dash: 'No Spaces',
              confirmed: 'Passwords Must Match'
          }
      }
  }
}

Vue.use(Checkbox)
Vue.use(VeeValidate, config)
Vue.prototype.$notify = Notification

const app = new Vue({
    el: '#auth',
    components: { Auth }
})
