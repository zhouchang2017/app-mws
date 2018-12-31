<template>
    <div>
        <card-title label-name="入库单发货">
            <div v-if="routeName==='detail'" class="ml-3 w-full flex items-center">
                <div class="flex w-full justify-end items-center"></div>
                <div class="ml-3"><!----> <!----></div>
                <button slot="reference" title="Update" type="button"
                        @click="routeName = 'update'"
                        class="btn btn-default btn-icon btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                         aria-labelledby="edit" role="presentation" class="fill-current text-white"
                         style="margin-top: -2px; margin-left: 3px;">
                        <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                    </svg>
                </button>

            </div>
        </card-title>
        <div class="form-list mb-6 p-0" v-loading="loading">
            <div class="p-6">
                <form-item title="计划说明" :value="staticResource.description"></form-item>
                <form-item title="当前状态" :value="staticResource.current_state"></form-item>
                <form-item title="运输方式" :value="hasShip"></form-item>
                <form-item title="最后更新时间" :value="staticResource.updated_at"></form-item>
                <form-item title="接收仓库" :value="staticOrder.warehouse.name"></form-item>
                <form-item title="仓库地址" :value="staticOrder.warehouse.simple_address"></form-item>
                <form-item title="物流状态" :value="trackStatus"></form-item>
                <form-item title="产品明细">
                    <product-variant-list slot="value" :items="staticOrder.items"></product-variant-list>
                </form-item>
                <template v-if="routeName === 'detail'">
                    <template v-for="track in staticOrder.tracks">
                        <form-item title="物流公司" :value="getLogistic(track.id).name"></form-item>
                        <form-item title="物流单号" :value="track.tracking_number"></form-item>

                    </template>
                </template>
                <template v-else>
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
                        <el-input slot="value" v-model="tracking_number" name="tracking_number"
                                  placeholder="请输入快递单号"></el-input>
                    </form-item>
                </template>
            </div>
            <div v-if="routeName === 'create'" class="bg-30 flex px-8 py-4">
                <button :disable="loading" @click="shipment" type="button"
                        class="ml-auto btn btn-default btn-primary inline-flex items-center relative">
                <span class="">
                    提交
                </span>
                </button>
            </div>
            <div v-if="routeName === 'update'" class="bg-30 flex px-8 py-4">
                <button class="ml-auto btn text-80 font-normal h-9 px-3 mr-3 btn-link"
                        @click="routeName = 'detail'">取消
                </button>
                <button :disable="loading" @click="shipment" type="button"
                        class="btn btn-default btn-primary inline-flex items-center relative">
                <span class="">
                    更新
                </span>
                </button>
            </div>
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
      },
    },
    data () {
      return {
        logistic_id: null,
        tracking_number: null,
        loading: false,
        update: false,
        routeName: '',
        staticResource: {},
        staticOrder: {}
      }
    },
    methods: {
      shipment () {
        this.loading = true
        axios.post(this.postApi, {
          logistic_id: this.logistic_id,
          tracking_number: this.tracking_number
        }).then(({data}) => {
          this.notify(data)
          this.staticOrder = data.data.order
          this.staticResource = data.data.resource
          this.routeName = 'detail'
        })
        this.loading = false
      },
      getLogistic (id) {
        return _.find(this.logistic, ['id', +id])
      }
    },
    computed: {
      hasShip () {
        return _.get(this, 'staticResource.has_ship', true) ? '物流/快递' : '无需物流'
      },
      trackStatus () {
        return this.shipped ?
          '已发货'
          :
          '待发货'
      },
      shipped () {
        return (_.get(this, 'staticOrder.tracks', [])).length > 0
      }
    },
    created () {
      this.staticResource = this.resource
      this.staticOrder = this.order
    },
    mounted () {

      this.routeName = this.shipped ? 'detail' : 'create'
    }
  }
</script>

<style scoped>

</style>