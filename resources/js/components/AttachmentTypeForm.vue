<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">
                <el-form-item label="费用调整类型名称" prop="name">
                    <el-input placeholder="请输入费用调整类型名称" v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="费用调整比率" prop="rate">
                    <el-slider :min="1" :max="200" :format-tooltip="formatTooltip" v-model="form.rate"></el-slider>
                </el-form-item>
            </el-form>
        </div>
        <div class="bg-30 flex px-8 py-4">
            <div class="ml-auto"
            >
                <button @click="submit" type="button"
                        class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        {{createPage ? '创建费用调整类型' : '更新费用调整类型'}}
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'attachment-type-form',
    props: {
      resourceId: {
        type: [String, Number]
      },
      uriKey: {
        type: String,
        default: 'products'
      },
      resource: {
        type: Object,
      }
    },
    data () {
      return {
        loading: false,
        form: {
          name: null,
          rate: 100,
        },
        rules: {
          name: [
            {required: true, message: '请输费用调整类型名称', trigger: 'blur'},
          ]
        }
      }
    },
    methods: {
      formatTooltip (value) {
        return value + '%'
      },
      async submit () {
        this.$refs['form'].validate(async (valid) => {
          if (valid) {
            this.loading = true
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
            this.loading = false
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
          this.createResourceFormData()
        )
      },

      createResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {

        })
      },
      resetForm () {
        this.$refs['form'].resetFields()
      },
    },
    computed: {
      updatePage () {
        return !_.isUndefined(this.resourceId)
      },

      createPage () {
        return !this.updatePage
      }
    },
    mounted () {
      if (this.updatePage) {
        this.form.name = this.resource.name
        this.form.rate = this.resource.rate
      }
    }
  }
</script>

<style scoped>

</style>