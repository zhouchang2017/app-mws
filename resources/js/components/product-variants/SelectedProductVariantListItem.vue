<template>
    <div class="w-full rounded-lg overflow-hidden border border-50 text-80 bg-white px-3 flex items-center h-full"
    >
        <img class="h-12 w-12 rounded"
             v-lazy="bgUrl"/>

        <div class="ml-4 flex items-center w-full p-1 overflow-hidden">
            <div class="w-1/6">
                <div class="font-semibold text-80 text-sm">{{resourceTitle}}</div>
                <div class="mt-2 flex items-bottom items-center justify-between">
                    <p class="text-grey-darkest font-semibold">
                        售价:{{ price }}
                    </p>
                    <p class="text-xs text-grey-darkest">
                        余量:{{ currentStock }}
                    </p>
                </div>
            </div>
            <div class="flex w-full justify-between items-center">
                <div class="flex items-center justify-center w-1/6 ">
                    <el-tooltip class="item" effect="dark" content="活动价" placement="top-start">
                        <div class="font-semibold text-80 text-sm text-primary">{{afterPrice}}</div>
                    </el-tooltip>
                </div>
                <div>
                    <el-tooltip class="item" effect="dark" content="设置让利率" placement="top-start">
                        <el-input style="width: 150px" type="number" :min="1" :max="100" size="mini"
                                  v-model="form.surrender_rate"
                                  placeholder="请输入让利率">
                            <template slot="append">%</template>
                        </el-input>
                    </el-tooltip>
                </div>
                <div>
                    <el-tooltip class="item" effect="dark" content="设置活动库存" placement="top-start">
                        <el-input style="width: 150px" type="number" :min="1" :max="currentStock" size="mini"
                                  v-model="form.stock"
                                  placeholder="活动库存">
                        </el-input>
                    </el-tooltip>
                </div>
                <div>
                    <el-tooltip class="item" effect="dark" content="设置活动时间" placement="top-start">
                        <el-date-picker
                                @change="dateTimeChange"
                                value-format="yyyy-MM-dd HH:mm:ss"
                                size="mini"
                                v-model="discountDatetime"
                                type="datetimerange"
                                :picker-options="pickerOptions2"
                                range-separator="至"
                                start-placeholder="开始日期"
                                end-placeholder="结束日期"
                                align="right">
                        </el-date-picker>
                    </el-tooltip>
                </div>
                <div>
                    <el-tooltip class="item" effect="dark" content="设置单次最大购买数量，0=不限制" placement="top-start">
                        <el-input style="width: 80px" type="number" size="mini" v-model="form.quantity_limit"
                                  placeholder="单次最大购买数量">
                        </el-input>
                    </el-tooltip>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'selected-product-variant-list-item',

    props: {
      resource: {
        type: Object,
        required: true
      },
      channelId: {
        type: [String, Number]
      },
    },

    watch: {
      'form.stock': function (value) {
        if (value > this.currentStock) {
          this.notify({type: 'error', message: '参加活动库存溢出'})
          this.form.stock = this.currentStock
        }
      }
    },

    data () {
      return {
        discountDatetime: '',
        pickerOptions2: {
          shortcuts: [{
            text: '最近一周',
            onClick (picker) {
              const end = new Date()
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 7)
              picker.$emit('pick', [start, end])
            }
          }, {
            text: '最近一个月',
            onClick (picker) {
              const end = new Date()
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 30)
              picker.$emit('pick', [start, end])
            }
          }, {
            text: '最近三个月',
            onClick (picker) {
              const end = new Date()
              const start = new Date()
              start.setTime(start.getTime() - 3600 * 1000 * 24 * 90)
              picker.$emit('pick', [start, end])
            }
          }]
        },

        form: {
          surrender_rate: null, //供应商让利率, 让利率 30%, 则存储0.3
          quantity_limit: 0, // 购买数量限制
          stock: null, // 活动提供库存
          began_at: null,
          ended_at: null
        }
      }
    },

    methods: {
      dateTimeChange (value) {
        const [began_at, ended_at] = value
        this.form.began_at = began_at
        this.form.ended_at = ended_at
      },

      setInitialValue (value) {
        this.form = value
      },

      fill () {
        return this.form
      }
    },

    computed: {
      bgUrl () {
        return 'https://media.wired.com/photos/5b22c5c4b878a15e9ce80d92/master/pass/iphonex-TA.jpg'
      },
      resourceTitle () {
        return _.get(this, 'resource.variantName', '-')
      },
      price () {
        return _.get(_.find(this.resource.dp_prices, ['channel_id', this.channelId]), 'price', 'N/A')
      },
      currentStock () {
        return _.get(this, 'resource.stock')
      },
      afterPrice () {
        return _.floor(this.price * (100 - this.form.surrender_rate) * 0.01, 2).toFixed(2)
      }
    },
    mounted () {
      this.resource.fill = this.fill
    }
  }
</script>

<style scoped>

</style>