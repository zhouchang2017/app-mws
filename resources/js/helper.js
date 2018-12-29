export default {

  methods: {
    go (link) {
      window.location.href = link
    },
    resolveUserType (type) {
      return type === 'App\\Models\\SupplierUser' ? '供应商' : '官方'
    }

  }
}