<template>
    <form-item :title="title" left-center>
        <div slot="value">
            <a
                    class="inline-block font-bold text-60 text-xs cursor-pointer mr-2 animate-text-color select-none"
                    :class="{ 'text-60': localeKey !== currentLocale, 'text-primary': localeKey === currentLocale }"
                    v-for="(locale, localeKey) in appConfig.locales"
                    :key="`a-${localeKey}`"
                    @click="changeTab(localeKey)"
            >
                {{ locale }}
            </a>
            <template
                    v-for="(locale, localeKey) in appConfig.locales"

            >
                <thumb-image-list
                        :key="`images-${localeKey}`"
                        :images="value[localeKey]"
                        v-show="currentLocale === localeKey"
                ></thumb-image-list>
            </template>

        </div>
    </form-item>
</template>

<script>
  import TranslationField from '../TranslationField'

  export default {
    name: 'translation-images-detail-item',
    extends: TranslationField,
    props: ['title', 'images'],

    data () {
      return {
        value: null
      }
    },

    mounted () {
      this.value = _.groupBy(this.images, 'locale_code')
    }
  }
</script>

<style scoped>

</style>