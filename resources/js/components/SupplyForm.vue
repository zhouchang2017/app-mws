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
            </el-table>
        </el-form-item>

        <el-form-item label="运输方式">
            <el-radio-group v-model="form.has_ship">
                <el-radio :label="true">物流运输</el-radio>
                <el-radio :label="false">无需物流</el-radio>
            </el-radio-group>
        </el-form-item>

        <el-form-item>
            <el-button type="primary" @click="onSubmit">立即创建</el-button>
            <el-button>取消</el-button>
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
        currentVariant: {}
      }
    },
    methods: {
      formatRequestData () {
        const {description, has_ship} = this.form
        return {
          description,
          has_ship,
          items: this.form.items.map(item => {
            const {variant,...data} = item
            return data
          })
        }
      },
      onSubmit () {
        axios.post('/supplies', this.formatRequestData()).then(({data}) => {
          console.log(data)
        })
      },
      fetchVariants () {
        this.loading = true
        return axios.get(`/product-variants?page=${this.currentPage}`).then(({data}) => {
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
        this.centerDialogVisible = false
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
    },
    async mounted () {
      await this.fetchVariants()
    }
  }
</script>

<style scoped>

</style>