<template>
    <el-select slot="value" v-model="id" name="logistic_id" value="id" filterable placeholder="请选择">
        <el-option
                v-for="item in items"
                :key="item.id"
                :label="item.name"
                :value="item.id">
        </el-option>
    </el-select>
</template>

<script>
  export default {
    name: 'logistic-select',
    props: {
      items: {
        type: Array,
        default: () => []
      },
      itemName: {
        type: String,
        default: 'logistic_id'
      },
      placeholder: {
        type: String,
        default: '请选择物流公司'
      }
    },
    data () {
      return {
        id: null
      }
    }
  }
</script>

<style scoped>

</style>