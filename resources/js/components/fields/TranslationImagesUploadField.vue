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
        <template
                v-for="(locale, localeKey) in appConfig.locales"

        >
            <images-upload
                    v-show="currentLocale === localeKey"
                    :key="`images-${localeKey}`"
                    :multiple="multiple"
                    :actionUrl="actionUrl"
                    :name="name"
                    :images="images[localeKey]"
                    :option="option"
                    @change="item=>value[localeKey] = item"
            ></images-upload>
        </template>

    </div>
</template>

<script>
  import TranslationField from '../TranslationField'

  export default {
    name: 'translation-images-upload-field',
    extends: TranslationField,

    props:{
      attribute:{
        type:String,
        default:'image'
      },
      actionUrl: {
        type: String,
        default: '/fs/upload/image'
      },
      multiple: {
        type: Boolean,
        default: false
      },
      name: {
        type: String,
        default: 'image'
      },
      images: {
        type: Object,
        default: () => {}
      },
      option: {
        type: Object,
        default: () => {
          return {
            name: 'image',
            url: 'image'
          }
        }
      }
    },
  }
</script>

<style scoped>

</style>