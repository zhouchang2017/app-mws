<template>
    <div class="form-list mb-6 p-0" v-loading="loading">
        <div class="p-6">
            <form-item title="计划说明" :value="resource.description"></form-item>
            <form-item title="当前状态" :value="resource.current_state"></form-item>
            <form-item title="运输方式" :value="hasShip"></form-item>
            <form-item title="最后更新时间" :value="resource.updated_at"></form-item>
            <form-item title="接收仓库" :value="order.warehouse.name"></form-item>
            <form-item title="仓库地址" :value="order.warehouse.simple_address"></form-item>
            <form-item title="物流状态" :value="trackStatus"></form-item>
            <form-item title="操作单">
                <product-variant-list slot="value" :items="order.items"></product-variant-list>
            </form-item>

            <form-item title="物流公司">
                <el-select slot="value" v-model="logistic_id" name="logistic_id" value="id" filterable
                           placeholder="请选择">
                    <el-option
                            v-for="item in logistic"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id">
                    </el-option>
                </el-select>
            </form-item>
            <form-item title="物流单号">
                <el-input slot="value" v-model="track_number" name="track_number" placeholder="请输入快递单号"></el-input>
            </form-item>
        </div>
        <div class="bg-30 flex px-8 py-4">
            <button :disable="loading" @click="shipment" type="button"
                    class="ml-auto btn btn-default btn-primary inline-flex items-center relative">
                <span class="">
                    提交
                </span>
            </button>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'pre-inventory-action-order-shipment',
    props: {
      resource: {
        type: Object,
        default: () => {}
      },
      order: {
        type: Object,
        default: () => {}
      },
      logistic: {
        type: Array,
        default: () => []
      },
      postApi: {
        type: String,
        required: true
      }
    },
    data () {
      return {
        logistic_id: null,
        track_number: null,
        loading: false
      }
    },
    methods: {
      shipment () {
        this.loading = true
        axios.post(this.postApi, {
          logistic_id: this.logistic_id,
          track_number: this.track_number
        }).then(({data}) => {
          console.log(data)
          this.loading = false
        })
      }
    },
    computed: {
      hasShip () {
        return _.get(this, 'resource.has_ship', true) ? '物流/快递' : '无需物流'
      },
      trackStatus () {
        return (_.get(this, 'order.tracks', [])).length > 0 ?
          '已发货'
          :
          '待发货'
      }
    }
  }
</script>

<style scoped>

</style>