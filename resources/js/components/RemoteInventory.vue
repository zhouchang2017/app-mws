<template>
    <el-select
            remote
            :remote-method="remoteMethod(item.variant_id)"
            :loading="loading"
            v-model="item.warehouse_id" placeholder="请选择">
        <el-option
                v-for="inventory in inventories"
                :key="inventory.id"
                :label="inventory.warehouse.name"
                :value="inventory.warehouse.id">
            <span style="float: left">{{ inventory.warehouse.name }}</span>
            <span style="float: right; color: #8492a6; font-size: 13px">余量:{{ inventory.quantity }}</span>
        </el-option>
    </el-select>
</template>

<script>
  export default {
    name: 'remote-inventory',
    props: {
      item: {
        type: Object,
        default: () => {}
      }
    },
    data () {
      return {
        loading: false,
        inventories: [],
        isOk: false
      }
    },
    methods: {
      remoteMethod (variant_id) {
        if (!this.isOk) {
          this.searchInventory(variant_id).then(({data}) => {
            this.inventories = data
          })
          this.isOk = true
        }
      },
      searchInventory (variant_id = null, warehouse_id = null) {
        return axios.get('/inventories/search', {
          params: {
            variant_id,
            warehouse_id
          }
        })
      },
    }
  }
</script>

<style scoped>

</style>