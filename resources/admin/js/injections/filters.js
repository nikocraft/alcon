import Vue from 'vue'
import formatSize from '~/utils/filters/formatSize'
import capitalizeFilter from '~/utils/filters/capitalize'

Vue.filter('capitalize', capitalizeFilter)
Vue.filter('formatSize', formatSize)
