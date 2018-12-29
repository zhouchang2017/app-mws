export default {
  methods: {
    notify (data) {
      const {title,message,type} = data
      this.$notify({title,message,type})
    }
  }
}