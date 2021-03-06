/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
window.Vue = require('vue')

import ElementUI from 'element-ui'

Vue.use(ElementUI)

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

const files = require.context('./components', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import notifyable from './notifyable'
import helper from './helper'

require('./filters')
Vue.mixin(notifyable)
Vue.mixin(helper)
const erpConfig = _.cloneDeep(config)
const app = new Vue({
  el: '#app',
  data () {
    return {
      erpConfig: {},
    }
  },

  methods: {
    showNav () {
      if (document.getElementById('side-nav').classList.contains('hidden')) {
        document.getElementById('nav-side-list').classList.add('animated', 'bounceInDown')
        document.getElementById('side-nav').classList.remove('hidden')
      } else {
        document.getElementById('side-nav').classList.add('hidden')
        document.getElementById('nav-side-list').classList.remove('animated', 'bounceInDown')
      }
    },
    setConfig (config) {
      // _.each(config, (value, key) => {
      //   this.$set(this.erpConfig, key, value)
      // })
      this.erpConfig = config
    }
  },

  mounted () {
    this.setConfig(erpConfig)
  }
})
