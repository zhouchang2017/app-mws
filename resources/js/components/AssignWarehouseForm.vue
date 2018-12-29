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
                        prop="quantity"
                        label="数量"
                >
                </el-table-column>
                <el-table-column
                        label="仓库"
                >
                    <template slot-scope="{row}">

                    </template>
                </el-table-column>
            </el-table>
        </div>

        <div class="bg-30 flex px-8 py-4">
            <button type="button" class="btn btn-default btn-primary inline-flex items-center relative ml-auto">
                <span class="">
                    生成操作单
                </span>
            </button>
        </div>
    </div>
</template>

<script>
  import Resourceable from '../resourceable'

  export default {
    name: 'assign-warehouse-form',
    mixins: [Resourceable],
    data () {
      return {
        warehouses: []
      }
    },
    methods: {
      fetchWarehouses () {
        return axios.get('/warehouses').then(({data}) => {
          this.warehouses = data
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