export default {
  methods: {
    fillAttribute (attribute, translations, path) {
      _.set(this, path,
        translations.reduce((res, cur) => {
          return _.tap(res, res => {
            _.set(res, cur.locale_code, cur[attribute])
          })
        }, {})
      )
    }
  }
}