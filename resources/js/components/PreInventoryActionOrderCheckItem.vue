<template>
    <div>
        <card-title :label="item.variant.code">
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
                <form-item :title="isTake ? '出库数量':'应到货数量'" :value="item.quantity"></form-item>
                <form-item :title="isTake ? '出库记录':'入库记录'">
                    <el-table
                            slot="value"
                            :data="state"
                    >
                        <el-table-column type="expand">
                            <template slot-scope="{row}">
                                <div v-if="row.attachments.length > 0">
                                    <div class="flex items-center m-1" v-for="attach in row.attachments"
                                         :key="attach.id">
                                        <div class="w-2/5 font-semibold text-grey-darker">
                                            {{findAttachmentType(attach.attachment_type_id)}}
                                        </div>
                                        <div class="w-3/5 font-semibold text-primary">{{attach.price}}</div>
                                    </div>
                                </div>
                                <div v-else class="flex items-center">
                                    <p class="font-semibold text-center text-grey-darker">暂无任何附加费用</p>
                                </div>
                            </template>
                        </el-table-column>
                        <el-table-column
                                prop="quantity"
                                label="数量"
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
                        <el-table-column
                                align="right"
                                label="操作"
                        >
                            <template slot-scope="{row}">
                                <el-button size="mini" @click="showAttacheDialog(row)">添加附加费用</el-button>
                            </template>
                        </el-table-column>
                    </el-table>
                </form-item>
            </div>
            <el-dialog
                    :title="type.name"
                    :visible.sync="dialogVisible"
                    width="50%"
            >
                <el-form ref="form" :model="form" :rules="rules" status-icon label-position="left" label-width="120px">
                    <p class="text-xs font-semibold text-50">基本信息</p>
                    <div class="border-30 border-b mb-3"></div>
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
                    <p class="text-xs font-semibold text-50">附加费用</p>
                    <div class="border-30 border-b mb-3"></div>
                    <el-form-item label="附加费用类型">
                        <el-select
                                class="w-full"
                                v-model="form.attachments"
                                multiple
                                value-key="id"
                                placeholder="请选择附加费用类型">
                            <el-option
                                    v-for="item in attachmentTypes"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item
                            v-for="(attach,index) in form.attachments"
                            :key="attach.id"
                            :label="attach.name"
                            :prop="`attachments.${index}.price`"
                            :rules="[
                                {
                                  required: true, message:`${attach.name}金额不能为空`, trigger: 'blur'
                                },
                                {
                                  type: 'number', message:'请输入正确金额', trigger: 'blur'
                                }
                            ]"
                    >
                        <el-input :placeholder="`请输入${attach.name}金额`"
                                  v-model.number="attach.price"></el-input>
                    </el-form-item>

                </el-form>
                <span slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">取消</el-button>
                <el-button type="primary" @click="addCheck">确定</el-button>
              </span>
            </el-dialog>

            <el-dialog
                    title="添加附加费用"
                    :visible.sync="attacheDialogVisible"
                    width="50%"
            >
                <el-form ref="attacheForm" :model="attacheForm" status-icon label-position="left" label-width="120px">
                    <p class="text-xs font-semibold text-50">附加费用</p>
                    <div class="border-30 border-b mb-3"></div>
                    <el-form-item label="附加费用类型">
                        <el-select
                                class="w-full"
                                v-model="attacheForm.attaches"
                                multiple
                                value-key="id"
                                placeholder="请选择附加费用类型">
                            <el-option
                                    v-for="item in attachmentTypes"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item
                            v-for="(attach,index) in attacheForm.attaches"
                            :key="attach.id"
                            :label="attach.name"
                            :prop="`attaches.${index}.price`"
                            :rules="[
                                {
                                  required: true, message:`${attach.name}金额不能为空`, trigger: 'blur'
                                },
                                {
                                  type: 'number', message:'请输入正确金额', trigger: 'blur'
                                }
                            ]"
                    >
                        <el-input :placeholder="`请输入${attach.name}金额`"
                                  v-model.number="attach.price"></el-input>
                    </el-form-item>

                </el-form>
                <span slot="footer" class="dialog-footer">
                <el-button @click="attacheDialogVisible = false">取消</el-button>
                <el-button type="primary" @click="addAttache">确定</el-button>
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
      },
      attachmentTypes: {
        type: Array,
        default: []
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
        attacheDialogVisible: false,
        form: {
          quantity: 0,
          warehouse_area: 'good',
          attachments: []
        },
        rules: {
          quantity: [
            {validator: checkQuantity, trigger: 'blur'}
          ]
        },
        state: [],
        attacheForm: {
          attaches: []
        },
        currentEditState: null
      }
    },
    methods: {
      showAttacheDialog (state) {
        this.attacheForm.attaches = []
        this.attacheDialogVisible = true
        this.currentEditState = state
      },
      addAttache () {
        this.$refs['attacheForm'].validate((valid) => {
          if (valid) {
            const from = this.attacheForm.attaches.map(attach => {
              return {
                attachment_type_id: attach.id,
                price: attach.price
              }
            })
            axios.post(this.createAttachApi, from).then(({data}) => {
              this.currentEditState.attachments = [...data.data, ...this.currentEditState.attachments]
              this.notify(data)
              this.attacheDialogVisible = false
            }).cache(err => {
              this.notify({type: 'error', title: 'error', message: err.response})
            })

          }
        })
      },
      addCheck () {
        this.$refs['form'].validate((valid) => {
          if (valid) {
            axios.post(this.api, this.createResourceFormData()).then(({data}) => {
              this.notify(data)
              this.state.push(data.data)
              if (!_.has(this.state, 'attachments')) {
                this.state.attachments = []
              }
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
      createResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {
          formData.attachments = formData.attachments.map(attach => {
            return {
              attachment_type_id: attach.id,
              price: attach.price
            }
          })
        })
      },
      resetForm () {
        this.$refs['form'].resetFields()
      },
      findAttachmentType (id) {
        return _.get(_.find(this.attachmentTypes, ['id', id]), 'name', 'N/A')
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
      createAttachApi () {
        return `/pre-item-states/${this.currentEditState.id}`
      },
      canAddMax () {
        return this.max - this.checkCount - this.form.quantity
      },
      checked () {
        return this.checkCount === this.max
      },
      isTake () {
        return this.type.action === 'take'
      }
    },
    created () {
      this.state = this.item.state
    }
  }
</script>

<style scoped>

</style>