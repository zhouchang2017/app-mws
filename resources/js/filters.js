import Vue from 'vue'

Vue.filter('ago', function (value) {
  if (!value) return '-'
  return dayjs(value).fromNow()
})