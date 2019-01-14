export default {
  methods: {
    fillTranslationField (attribute, translations, path, image = false) {
      _.set(this, path,
        translations.reduce((res, cur) => {
          return _.tap(res, res => {
            if (image) {
              _.set(res, cur.locale_code, cur)
            } else {
              _.set(res, cur.locale_code, cur[attribute])
            }
          })
        }, {})
      )
    },

    resolveTranslationImagesField (translations) {
      return _.groupBy(translations,'locale_code')
    },

    formatTranslationValue (attribute, translations) {
      return translations.reduce((res, cur) => {
        return _.tap(res, res => {
          _.set(res, cur.locale_code, cur[attribute])
        })
      }, {})
    },
    checkName (rule, value, callback) {
      _.each(this.appConfig.locales, (locale, key) => {
        if (!value[key]) {
          return callback(new Error(`${locale}名称不能为空`))
        }
      })
      callback()
    },
  }
}