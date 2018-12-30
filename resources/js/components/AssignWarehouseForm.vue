<template>
    <div>
        <div class="p-6">
            <el-table
                    v-loading="loading"
                    :data="resources"
            >
                <el-table-column
                        prop="variant.variantName"
                        label="商品名称"
                >
                </el-table-column>
                <el-table-column
                        prop="variant.code"
                        label="商品编码"
                >
                </el-table-column>
                <el-table-column
                        prop="quantity"
                        label="数量"
                >
                </el-table-column>
                <el-table-column
                        label="仓库"
                >
                    <template slot-scope="{row}">
                        <el-select v-model="row.warehouse_id" placeholder="请选择">
                            <el-option
                                    v-for="item in warehouses"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                    </template>
                </el-table-column>
            </el-table>
        </div>

        <div class="bg-30 flex px-8 py-4">
            <button :disabled="!canOpen" @click="dialogVisible = true" type="button"
                    class="btn btn-default btn-primary inline-flex items-center relative ml-auto">
                    <span class="">
                        生成操作单
                    </span>
            </button>
        </div>

        <el-dialog
                v-loading="loading"
                fullscreen
                title="操作单"
                @open="open"
                :visible.sync="dialogVisible"
                width="70%"
        >

            <template v-for="(item,index) in form">
                <!--<card-title :label-name="`操作单${index}`"></card-title>-->
                <!--<h5 class="text-90 font-normal text-xl flex-no-shrink">操作单{{ index }}</h5>-->
                <div class="form-list border border-40 mb-6" :key="index">
                    <form-item title="仓库" :value="getWarehouseById(item.warehouse_id).name"></form-item>
                    <form-item title="操作单说明">
                        <el-input
                                slot="value"
                                type="textarea"
                                :rows="2"
                                placeholder="请输入操作单说明"
                                v-model="item.description">
                        </el-input>
                    </form-item>
                    <form-item title="商品明细">
                        <el-table slot="value"
                                  :data="item.items"
                        >
                            <el-table-column
                                    prop="variant.variantName"
                                    label="商品名称"
                            >
                            </el-table-column>
                            <el-table-column
                                    prop="variant.code"
                                    label="商品编码"
                            >
                            </el-table-column>
                            <el-table-column
                                    prop="quantity"
                                    label="数量"
                            >
                            </el-table-column>
                        </el-table>
                    </form-item>
                </div>
            </template>

            <span slot="footer" class="dialog-footer">
                <button class="btn text-80 font-normal h-9 px-3 mr-3 btn-link"
                        @click="dialogVisible = false">取消</button>
                <button :disabled="loading" class="btn btn-default btn-primary" @click="submit">提交</button>
            </span>
        </el-dialog>

    </div>

</template>

<script>
  import Resourceable from '../resourceable'

  export default {
    name: 'assign-warehouse-form',
    mixins: [Resourceable],
    data () {
      return {
        warehouses: [],
        dialogVisible: false,
        form: [],
        loading:false
      }
    },
    methods: {
      fetchWarehouses () {
        return axios.get('/warehouses').then(({data}) => {
          this.warehouses = data
        })
      },
      listGroupByWarehouse () {
        return _.map(_.groupBy(this.resources, 'warehouse_id'), (items, key) => {
          return {
            warehouse_id: key,
            description: '',
            items: items.map(item => {
              const {product_id, variant_id, quantity, variant} = item
              return {product_id, variant_id, quantity, variant}
            })
          }
        })
      },
      open () {
        this.form = this.listGroupByWarehouse()
      },
      getWarehouseById (id) {
        return _.find(this.warehouses, ['id', +id])
      },
      submit () {
        this.loading = true
        axios.post(`/${this.resourceName}/${this.resourceId}/assigned`, this.requestFormFormat).then(({data}) => {
          this.notify(data)
          this.loading = false
          this.go(`/${this.resourceName}/${this.resourceId}`)
        })
      }
    },
    computed: {
      canOpen () {
        return this.resources.every(item => item.warehouse_id)
      },
      requestFormFormat () {
        return this.form.map(order => {
          const clone = _.cloneDeep(order)
          clone.items = order.items.map(item => {
            const {variant, ...data} = item
            return data
          })
          return clone
        })
      }
    },
    async mounted () {
      await this.fetchWarehouses()
    }
  }
</script>

<style scoped>

</style>