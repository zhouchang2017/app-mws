<template>
    <div>
        <a
                class="inline-block font-bold text-60 text-xs cursor-pointer mr-2 animate-text-color select-none"
                :class="{ 'text-60': localeKey !== currentLocale, 'text-primary': localeKey === currentLocale }"
                v-for="(locale, localeKey) in appConfig.locales"
                :key="`a-${localeKey}`"
                @click="changeTab(localeKey)"
        >
            {{ locale }}
        </a>
        <p class="text-90 mt-1">{{ value[currentLocale] }}</p>
    </div>
</template>

<script>
  import Translatable from '../../translatable'

  export default {
    name: 'translation-detail-value',

    mixins: [Translatable],
    props: {
      attribute: {
        type: String,
        default: 'name'
      },
      translations: {
        type: Array
      }
      /*
      * value:{
      *     zh-CN:'',
      *     en-US:''
      * }*/
    },

    data () {
      return {
        currentLocale: 'zh-CN',
        value: {}
      }
    },

    methods: {
      changeTab (locale) {
        if (this.currentLocale !== locale) {
          this.currentLocale = locale
        }
      },

    },

    computed: {
      language () {
        return _.get(this, `appConfig.locales.${this.currentLocale}`)
      }
    },
    created () {
      this.currentLocale = _.get(this, 'appConfig.indexLocale', 'zh-CN')
      this.value = this.formatTranslationValue(this.attribute,this.translations)
    },
  }
</script>

<style scoped>

</style>