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
        <el-input :placeholder="placeholder" v-model="value[currentLocale]"></el-input>
    </div>
</template>

<script>
  export default {
    name: 'translation-field',
    props: {
      attribute: {
        type: String,
        default: '内容'
      },
      value: {
        type: Object,
      },
      /*
      * value:{
      *     zh-CN:'',
      *     en-US:''
      * }*/
    },
    model: {
      prop: 'value',
      event: 'input'
    },

    data () {
      return {
        currentLocale: 'zh-CN'
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
      placeholder () {
        return `请输入${this.language}${this.attribute}`
      },
      language () {
        return _.get(this,`appConfig.locales.${this.currentLocale}`)
      }
    },
    mounted () {
      this.currentLocale = _.get(this, 'appConfig.indexLocale', 'zh-CN')
    },
  }
</script>

<style scoped>

</style>