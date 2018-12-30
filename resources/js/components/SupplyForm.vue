<template>
    <el-form ref="form" :model="form" label-position="left" label-width="180px">

        <el-form-item label="入库计划描述">
            <el-input type="textarea" v-model="form.description"></el-input>
        </el-form-item>

        <el-form-item label="选择即将入库产品(变体)">
            <el-table
                    v-loading="loading"
                    :data="variants"
            >
                <el-table-column
                        prop="code"
                        label="变体编码"
                >
                </el-table-column>
                <el-table-column
                        prop="variantName"
                        label="变体名称"
                >
                </el-table-column>
                <el-table-column
                        prop="stock"
                        label="当前库存数量">
                </el-table-column>
                <el-table-column
                        label="操作">
                    <template slot-scope="{row}">
                        <el-button type="primary" @click="handleSelectionChange(row)" size="mini">添加</el-button>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex my-3">
                <el-pagination
                        background
                        :current-page.sync="currentPage"
                        :page-size="perPage"
                        layout="prev, pager, next"
                        :total="total">
                </el-pagination>
            </div>

        </el-form-item>


        <el-form-item label="以选中入库产品(变体)">
            <el-table
                    :data="form.items"
            >
                <el-table-column
                        prop="variant.code"
                        label="变体编码"
                >
                </el-table-column>
                <el-table-column
                        prop="variant.variantName"
                        label="变体名称"
                >
                </el-table-column>
                <el-table-column
                        prop="variant.stock"
                        label="当前库存数量">
                </el-table-column>
                <el-table-column
                        label="供货数量">
                    <template slot-scope="{row}">
                        <el-input v-model="row.quantity" placeholder="请输入数量"></el-input>
                    </template>
                </el-table-column>
                <el-table-column
                        label="操作">
                    <template slot-scope="{row}">
                        <el-popover
                                placement="top"
                                width="160"
                                v-model="visible">
                            <p>确定删除吗？</p>
                            <div style="text-align: right; margin: 0">
                                <el-button size="mini" type="text" @click="visible = false">取消</el-button>
                                <el-button type="primary" size="mini" @click="removeSelection(row)">确定</el-button>
                            </div>
                            <el-button size="small" slot="reference" type="danger">移 除</el-button>
                        </el-popover>
                    </template>
                </el-table-column>
            </el-table>
        </el-form-item>

        <el-form-item label="运输方式">
            <el-radio-group v-model="form.has_ship">
                <el-radio :label="true">物流运输</el-radio>
                <el-radio :label="false">无需物流</el-radio>
            </el-radio-group>
        </el-form-item>

        <el-form-item>
            <el-button type="primary" @click="onSubmit">{{btnTitle}}</el-button>
            <el-button>返回</el-button>
        </el-form-item>

        <el-dialog
                title="填写供货数量"
                :visible.sync="centerDialogVisible"
                width="50%"
                center>
            <div class="card p-6 w-full">
                <div class="flex border-b border-40 text-80 items-center">
                    <div class="w-1/5 py-6 px-8">
                        商品名称
                    </div>
                    <div class="w-4/5 py-6 px-8">
                        {{currentVariant.variantName}}
                    </div>
                </div>
                <div class="flex border-b border-40 text-80 items-center">
                    <div class="w-1/5 py-6 px-8">
                        商品编码
                    </div>
                    <div class="w-4/5 py-6 px-8">
                        {{currentVariant.code}}
                    </div>
                </div>
                <div class="flex border-b border-40 text-80 items-center">
                    <div class="w-1/5 py-6 px-8">
                        当前库存
                    </div>
                    <div class="w-4/5 py-6 px-8">
                        {{currentVariant.stock}}
                    </div>
                </div>
                <div class="flex  text-80 items-center">
                    <div class="w-1/5 py-6 px-8">
                        供货数量
                    </div>
                    <div class="w-4/5 py-6 px-8">
                        <el-input v-model="currentVariant.quantity" placeholder="请输入数量"></el-input>
                    </div>
                </div>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="centerDialogVisible = false">取 消</el-button>
                <el-button type="primary" @click="pushToSelections">确 定</el-button>
            </span>
        </el-dialog>

    </el-form>
</template>

<script>
  import Pageable from '../pageable'

  export default {
    name: 'supply-form',

    mixins: [Pageable],

    props: {
      origin: {
        type: Object,
        default: null
      }
    },
    data () {
      return {
        form: {
          description: '',
          has_ship: true,
          items: []
        },
        resources: {},
        loading: true,
        centerDialogVisible: false,
        currentVariant: {},
        isUpdate: false,
        visible: false
      }
    },
    methods: {
      updatePageInit () {
        if (!_.isNull(this.origin)) {
          const {description, has_ship, items} = this.origin
          this.form = Object.assign({}, this.form, {description, has_ship, items})
          this.isUpdate = true
        }
      },
      formatRequestData () {
        const {description, has_ship} = this.form
        return {
          description,
          has_ship,
          items: this.form.items.map(item => {
            const {variant, ...data} = item
            return data
          })
        }
      },
      onSubmit () {
        this.isUpdate ? this.update().then(({data}) => {
          this.notify(data)
          this.go(`/supplies/${this.origin.id}`)
        }).catch(err => {
          console.error(err.response)
        }) : this.store().then(({data}) => {
          this.notify(data)
          this.go(`/supplies/${data.data.id}`)
        }).catch(err => {
          console.error(err.response)
        })
      },
      store () {
        return axios.post('/supplies', this.formatRequestData())
      },
      update () {
        return axios.patch(`/supplies/${this.origin.id}`, this.formatRequestData())
      },
      fetchVariants () {
        this.loading = true
        return axios.get(this.variantsApi).then(({data}) => {
          this.resources = data
          this.setOptions(data)
          this.loading = false
        })
      },
      handleSelectionChange (val) {
        this.centerDialogVisible = true
        const variant = _.cloneDeep(val)
        this.currentVariant = Object.assign({}, variant, {quantity: 0})
      },
      pushToSelections () {
        const {product_id, id, quantity} = this.currentVariant
        this.form.items.push(
          Object.assign({}, {product_id, variant_id: id, quantity}, {variant: this.currentVariant})
        )
        const name = this.currentVariant.variantName || 'N/A'
        this.notify({title: '已加入到下方表格', message: `${name}以加入${quantity}件`, type: 'success'})
        this.centerDialogVisible = false
      },
      removeSelection (row) {
        const index = this.form.items.findIndex(item => item === row)
        this.form.items.splice(index, 1)
        this.visible = false
      }
    },

    watch: {
      currentPage: async function (val) {
        await this.fetchVariants()
      }
    },

    computed: {
      variants () {
        return _.get(this, 'resources.data', [])
      },
      btnTitle () {
        return this.isUpdate ? '更新' : '保存创建'
      },
      variantsApi () {
        return this.appConfig.userType === 'App\\Models\\User' ?
          `/product-variants?supplier=${this.supplier.id}&page=${this.currentPage}` :
          `/product-variants?page=${this.currentPage}`
      },
      supplier () {
        return _.get(this, 'origin.origin')
      }
    },
    async mounted () {
      await this.fetchVariants()
      this.updatePageInit()
    }
  }
</script>

<style scoped>

</style>