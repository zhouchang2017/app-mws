<template>
    <div v-loading="loading">
        <el-button type="text" @click="dialogFormVisible = true">邀请供应商</el-button>

        <el-dialog title="推送邀请通知" :visible.sync="dialogFormVisible">
            <div>
                <el-form v-loading="loading" ref="form" :rules="rules" :model="form"
                         label-width="100px">
                    <el-form-item label="标题" prop="title">
                        <el-input v-model="form.title"></el-input>
                    </el-form-item>
                    <el-form-item label="内容" prop="body">
                        <el-input type="textarea" v-model="form.body"></el-input>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="flex">
                    <el-button class="ml-auto mr-3" @click="dialogFormVisible = false">取 消</el-button>
                    <el-button type="primary" @click="submit">确 定</el-button>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script>
  export default {
    name: 'invite-supplier-join-promotion-plan',

    props: {
      resourceId: {
        type: [Number, String],
      },
      uriKey: {
        type: String,
        default: 'promotion-plans'
      }
    },

    data () {
      return {
        loading: false,
        dialogFormVisible: false,
        formName: 'form',
        form: {
          title: null,
          body: null
        },
        rules: {
          title: [
            {required: true, message: `请输标题`, trigger: 'blur'},
            {max: 30, message: `标题长度最大30`, trigger: 'blur'},
          ],
          body: [
            {required: true, message: `请输正文`, trigger: 'blur'},
          ],
        }
      }
    },

    methods: {
      async submit () {
        this.$refs[this.formName].validate(async (valid) => {
          if (valid) {
            this.loading = true
            try {
              const data = await this.createRequest()
              if(data.status === 204){
                this.notify({type: 'success', title: '已发送'})
              }

            } catch (e) {
              this.notify({type: 'error', title: 'ERROR', message: e.response})
            }
            this.dialogFormVisible = false
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
          `/${this.uriKey}/${this.resourceId}/notify`,
          this.createResourceFormData()
        )
      },

      // 创建表单数据
      createResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {

        })
      },
    }
  }
</script>

<style scoped>

</style>