export default {
  methods: {
    go (link) {
      window.location.href = link
    },
    resolveUserType (type) {
      return type === 'App\\Models\\SupplierUser' ? '供应商' : '官方'
    },
    checkPhone (rule, value, callback) {
      if (!value) {
        return callback(new Error('手机号码不能为空'))
      }
      if(!(/^1[34578]\d{9}$/.test(value))){
        return callback(new Error('手机号码有误，请重填'))
      }
      callback()
    }
  },
  computed: {
    appConfig () {
      return this.$root.erpConfig
    },
    isAdmin () {
      return this.appConfig.userType === 'App\\Models\\User'
    }
  }
}