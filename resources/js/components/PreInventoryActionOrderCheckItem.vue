<template>
    <div>
        <card-title :label-name="item.variant.code">
            <div class="ml-3 w-full flex items-center">
                <div class="flex w-full justify-end items-center"></div>
                <div class="ml-3"><!----> <!----></div>
                <button v-if="!checked" title="addCheck"
                        class="btn btn-default btn-icon btn-primary"
                        @click="dialogVisible = true"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                         aria-labelledby="edit" role="presentation" class="fill-current text-white"
                         style="margin-top: -2px; margin-left: 3px;">
                        <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                    </svg>
                </button>
            </div>
        </card-title>
        <div class="card w-full mb-6" :key="item.id">
            <div class="form-list">
                <form-item title="商品名称" :value="item.variant.variantName"></form-item>
                <form-item title="商品编码" :value="item.variant.code"></form-item>
                <form-item title="应到货数量" :value="item.quantity"></form-item>
                <form-item title="入仓记录">
                    <el-table
                            slot="value"
                            :data="state"
                    >
                        <el-table-column
                                prop="quantity"
                                label="批次到货数量"
                        >
                        </el-table-column>
                        <el-table-column
                                label="货品状态"
                        >
                            <template slot-scope="{row}">
                                <span>{{ row.warehouse_area === 'good' ? '良品' : '不良品' }}</span>
                            </template>
                        </el-table-column>
                        <el-table-column
                                prop="updated_at"
                                label="更新时间"
                        >
                        </el-table-column>
                    </el-table>
                </form-item>
            </div>
            <el-dialog
                    :title="type.name"
                    :visible.sync="dialogVisible"
                    width="50%"
            >
                <el-form ref="form" :model="form" :rules="rules" status-icon label-position="left" label-width="80px">
                    <div class="mb-3">
                        <el-alert
                                :title="`最大还能添加${canAddMax}个`"
                                type="info"
                                show-icon>
                        </el-alert>
                    </div>
                    <el-form-item label="数量" prop="quantity">
                        <el-input type="number" :placeholder="`最大还能添加${canAddMax}个`"
                                  v-model.number="form.quantity"></el-input>
                    </el-form-item>
                    <el-form-item label="货品状态" prop="warehouse_area">
                        <el-radio-group v-model="form.warehouse_area">
                            <el-radio label="good">良品</el-radio>
                            <el-radio label="bad">不良品</el-radio>
                        </el-radio-group>
                    </el-form-item>
                </el-form>
                <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="addCheck">确 定</el-button>
              </span>
            </el-dialog>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'pre-inventory-action-order-check-item',
    props: {
      item: {
        type: Object,
        default: () => {}
      }
    },
    inject: ['type'],
    data () {
      const checkQuantity = (rule, value, callback) => {
        if (!value) {
          return callback(new Error('数量不能为空'))
        }
        if (value <= 0) {
          return callback(new Error('最小数量不能小于0'))
        }
        if (!Number.isInteger(value)) {
          callback(new Error('请输入数字值'))
        } else {
          if (value + this.checkCount > this.item.quantity) {
            callback(new Error(`超出最大数量`))
          } else {
            callback()
          }
        }
      }
      return {
        dialogVisible: false,
        form: {
          quantity: 0,
          warehouse_area: 'good'
        },
        rules: {
          quantity: [
            {validator: checkQuantity, trigger: 'blur'}
          ]
        },
        state:[]
      }
    },
    methods: {
      addCheck () {
        this.$refs['form'].validate((valid) => {
          if (valid) {
            axios.post(this.api, this.form).then(({data}) => {
              this.notify(data)
              this.state.push(data.data)
              this.resetForm()
              this.dialogVisible = false
            }).catch(err => {
              this.notify({type: 'error', title: 'error', message: err.response})
            })
          } else {
            this.notify({type: 'error', title: '表单数据不合法'})
            return false
          }
        })
      },
      resetForm () {
        this.$refs['form'].resetFields()
      }
    },
    computed: {
      checkCount () {
        return _.sumBy(this.state, 'quantity')
      },
      max () {
        return _.get(this.item, 'quantity')
      },
      api () {
        return `/pre-inventory-action-order-items/${this.item.id}/check`
      },
      canAddMax () {
        return this.max - this.checkCount - this.form.quantity
      },
      checked () {
        return this.checkCount === this.max
      }
    },
    created(){
      this.state = this.item.state
    }
  }
</script>

<style scoped>

</style>