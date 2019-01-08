<template>
    <div>
        <el-table
                :data="value"
                style="width: 100%">
            <el-table-column
                    label="吊牌价"
                    prop="original_price">
            </el-table-column>
            <el-table-column
                    label="售价"
                    prop="price"
            >
            </el-table-column>
            <el-table-column
                    label="DP渠道"
                    >
                <template slot-scope="{row}">
                    {{getChannelName(row.channel_id)}}
                </template>
            </el-table-column>
            <el-table-column
                    align="right">
                <template slot="header" slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="handleAdd(scope.$index, scope.row)"
                    >添加
                    </el-button>
                </template>
                <template slot-scope="scope">
                    <el-button
                            size="mini"
                            @click="handleEdit(scope.$index, scope.row)">编辑
                    </el-button>
                    <el-popover
                            placement="top"
                            width="160"
                            v-model="visibleDelete">
                        <p>这是一段内容这是一段内容确定删除吗？</p>
                        <div style="text-align: right; margin: 0">
                            <el-button size="mini" type="text" @click="visibleDelete = false">取消</el-button>
                            <el-button type="danger" size="mini" @click="handleDelete(scope.$index, scope.row)">确定
                            </el-button>
                        </div>
                        <el-button type="danger" size="mini" slot="reference">删除</el-button>
                    </el-popover>
                </template>
            </el-table-column>
        </el-table>
        <!--form-->
        <el-dialog title="渠道价格" :visible.sync="dialogFormVisible">
            <div>
                <el-form v-loading="loading" ref="form" :rules="rules" :model="form"
                         label-width="100px">
                    <el-form-item style="margin-bottom:22px" label="吊牌价" prop="original_price">
                        <el-input v-model.number="form.original_price"></el-input>
                    </el-form-item>
                    <el-form-item style="margin-bottom:22px" label="售价" prop="price">
                        <el-input v-model.number="form.price"></el-input>
                    </el-form-item>
                    <el-form-item style="margin-bottom:22px" label="渠道" prop="channel_id">
                        <el-select v-model="form.channel_id" filterable placeholder="请选择">
                            <el-option
                                    v-for="item in channels"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                            </el-option>
                        </el-select>
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
    name: 'edit-dp-channel-price',
    props: {
      defaultValue: {
        type: Array,
        default: () => []
      },
      resourceId: {
        type: [Number, String],
      },
      uriKey: {
        type: String,
        default: 'product-variants'
      }
    },

    data () {
      return {
        loading: false,
        channels: [],
        value: [],
        dialogFormVisible: false,
        visibleDelete: false,
        form: {
          id: null,
          variant_id: null,
          original_price: null,
          price: null,
          channel_id: null
        },
        current: null,
        currentIndex: null,
        rules: {
          price: [
            {required: true, message: `请输商品售价`, trigger: 'blur'},
            {type: 'number', trigger: 'blur'},
          ],
          channel_id: [
            {required: true, message: `请输选择渠道`, trigger: 'change'},
          ],
        },
        formName: 'form'
      }
    },

    methods: {
      initValue (value) {
        this.value = value
      },
      fetchChannels (params = null) {
        return axios.get('/channels?withoutPage', {params})
      },
      async fillChannels () {
        const {data} = await this.fetchChannels()
        this.channels = data
      },
      fillForm (item) {
        this.form.id = _.get(item, 'id')
        this.form.variant_id = this.resourceId
        this.form.original_price = _.toNumber(_.get(item, 'original_price', 0))
        this.form.price = _.toNumber(_.get(item, 'price', 0))
        this.form.channel_id = _.get(item, 'channel_id')
      },
      resetForm () {
        this.form = {
          id: null,
          variant_id: null,
          original_price: null,
          price: null,
          channel_id: null
        }
      },
      handleAdd (index, item) {
        this.current = null
        this.currentIndex = null
        this.fillForm(item)
        this.dialogFormVisible = true
      },
      handleEdit (index, item) {
        this.current = item
        this.fillForm(item)
        this.currentIndex = index
        this.dialogFormVisible = true
      },
      handleDelete (index, item) {
        axios.delete(`/${this.uriKey}/${this.resourceId}/prices/${item.id}`).then((data) => {
          if (data.status === 204) {
            this.value.splice(index, 1)
            this.notify({type: 'success', title: '删除成功'})
          }
        })
      },
      getChannelName (id) {
        const {name} = _.find(this.channels, ['id', id])
        return name
      },
      async submit () {
        this.$refs[this.formName].validate(async (valid) => {
          if (valid) {
            this.loading = true
            try {
              if (this.createPage) {
                const {data} = await this.createRequest()
                this.notify(data)
                this.value.push(data.data)
              } else {
                const {data} = await this.updateRequest()
                this.notify(data)
                this.value.splice(this.currentIndex, 1, data.data)
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
          `/${this.uriKey}/${this.resourceId}/prices`,
          this.createResourceFormData()
        )
      },
      /**
       * Send a update request for this resource
       */
      updateRequest () {
        return axios.patch(
          `/${this.uriKey}/${this.resourceId}/prices/${this.form.id}`,
          this.updateResourceFormData()
        )
      },
      // 创建表单数据
      createResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {
          delete formData.id
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
      createPage () {
        return _.isNull(this.current)
      }
    },
    async mounted () {
      await this.fillChannels()
      this.initValue(this.defaultValue)
    }
  }
</script>

<style scoped>

</style>