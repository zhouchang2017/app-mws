export default {
  methods: {
    fillTranslationField (attribute, translations, path) {
      _.set(this, path,
        translations.reduce((res, cur) => {
          return _.tap(res, res => {
            _.set(res, cur.locale_code, cur[attribute])
          })
        }, {})
      )
    },
    formatTranslationValue(attribute, translations){
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