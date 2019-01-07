import Vue from 'vue'
import VueLazyload from 'vue-lazyload'

Vue.use(VueLazyload,{
  // set observer to true
  observer: true,

  // optional
  observerOptions: {
    rootMargin: '0px',
    threshold: 0.1
  },
  error:require('../../static/images/image_404.png')
})