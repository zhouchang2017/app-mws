export default {
  methods: {
    go (link) {
      window.location.href = link
    },
    resolveUserType (type) {
      return type === 'App\\Models\\SupplierUser' ? '供应商' : '官方'
    }
  },
  computed: {
    appConfig () {
      return this.$root.erpConfig
    },
    isAdmin(){
      return this.appConfig.userType === 'App\\Models\\User'
    }
  }
}