<template>
    <el-table
            :data="items"
    >
        <el-table-column
                label="商品名称"
        >
            <template slot-scope="{row}">
                <el-tooltip  effect="dark" :content="variantName(row)" placement="top">
                    <a target="_blank" :href="`/product-variants/${row.variant.id}`"
                       class="text-primary no-underline dim text-primary font-bold">{{variantName(row)}}</a>
                </el-tooltip>
            </template>
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
                v-if="hasWarehouse"
                label="仓库"
        >
            <template slot-scope="{row}">
                <a target="_blank" :href="`/warehouses/${row.warehouse_id}`"
                   class="text-primary no-underline dim text-primary font-bold">{{row.warehouse.name || '-'}}</a>
            </template>
        </el-table-column>
        <el-table-column
                v-if="hasWarehouseArea"
                label="良品/不良品"
        >
            <template slot-scope="{row}">
                <div class="flex items-center">
                    <div class="rounded-full mr-2" style="height: 10px; width: 10px;" :class="statuClass(row)"></div>
                    <div>{{statuText(row)}}</div>
                </div>
            </template>
        </el-table-column>
        <slot/>
    </el-table>
</template>

<script>
  export default {
    name: 'product-variant-list',
    props: {
      items: {
        type: Array,
        default: () => []
      }
    },

    methods: {
      statuClass (row) {
        return row.warehouse_area === 'good' ? 'bg-green' : 'bg-red'
      },
      statuText (row) {
        return row.warehouse_area === 'good' ? '良品' : '不良品'
      },
      variantName (row) {
        return _.get(row, 'variant.name', _.get(row, 'variant.variantName', '-'))
      }
    },
    computed: {
      hasWarehouse () {
        return this.items.every(item => item.hasOwnProperty('warehouse'))
      },
      hasWarehouseArea () {
        return this.items.every(item => item.hasOwnProperty('warehouse_area'))
      }
    }
  }
</script>

<style scoped>

</style>