export default {
  props: {
    resourceId: {
      type: [String, Number]
    },
    uriKey: {
      type: String,
      required: true
    },
    resource: {
      type: Object,
    },
    // 复数名称
    label: {
      type: String
    },
    // 单数名称
    singularLabel: {
      type: String
    }
  },
  data () {
    return {
      loading: false,
      form: {},
      rules: {},
      formName: 'form'
    }
  },
  methods: {
    startLoading(){
      this.loading = true
    },
    endLoading(){
      this.loading = false
    },
    validateForm(){
      return this.$refs[this.formName].validate()
    },
    async submit () {
      this.$refs[this.formName].validate(async (valid) => {

        if (valid) {
          this.startLoading()
          try {
            if (this.createPage) {
              const {data} = await this.createRequest()
              this.notify(data)
              this.go(`/${this.uriKey}/${data.data.id}`)
            } else {
              const {data} = await this.updateRequest()
              this.notify(data)
              this.go(`/${this.uriKey}/${this.resourceId}`)
            }
          } catch (e) {
            this.notify({type: 'error', title: 'ERROR', message: e.response})
          }
          this.endLoading()
        } else {
          this.notify({type: 'error', title: '表单数据不合法'})
          return false
        }
      })
    },
    /**
     * Send a create request for this resource
     */
    createRequest () {
      return axios.post(
        `/${this.uriKey}`,
        this.createResourceFormData()
      )
    },
    /**
     * Send a update request for this resource
     */
    updateRequest () {
      return axios.patch(
        `/${this.uriKey}/${this.resourceId}`,
        this.updateResourceFormData()
      )
    },
    // 创建表单数据
    createResourceFormData () {
      return _.tap(_.cloneDeep(this.form), formData => {

      })
    },
    // 更新表单数据
    updateResourceFormData () {
      return _.tap(_.cloneDeep(this.form), formData => {

      })
    },
    // 重置表单
    resetForm () {
      this.$refs[this.formName].resetFields()
    },
  },
  computed: {
    updatePage () {
      return !_.isUndefined(this.resourceId)
    },

    createPage () {
      return !this.updatePage
    },

    submitText () {
      return this.createPage ? `创建${this.singularLabel}` : `更新${this.singularLabel}`
    },

    refForm () {
      return this.$refs[this.formName]
    }
  }
}