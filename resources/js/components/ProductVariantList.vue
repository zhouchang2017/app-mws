<template>
    <el-table
            :data="items"
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
    }
  }
</script>

<style scoped>

</style>