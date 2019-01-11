<template>
    <el-table
            ref="listsTable"
            row-key="id"
            tooltip-effect="dark"
            :data="lists"
    >
        <el-table-column
                prop="id"
                label="ID"
        >

        </el-table-column>
        <el-table-column
                label="商品名称"
        >
            <template slot-scope="{row}">
                <el-tooltip effect="dark" :content="variantName(row)" placement="top">
                    <a target="_blank" :href="`/product-variants/${row.variant.id}`"
                       class="text-primary no-underline dim text-primary font-bold">{{variantName(row)}}</a>
                </el-tooltip>
            </template>
        </el-table-column>

        <el-table-column
                label="良品/不良品"
        >
            <template slot-scope="{row}">
                <div class="flex items-center">
                    <div class="rounded-full mr-2" style="height: 10px; width: 10px;" :class="stateClass(row)"></div>
                    <div>{{stateText(row)}}</div>
                </div>
            </template>
        </el-table-column>

        <el-table-column
                prop="quantity"
                label="变动数量">
            <template slot-scope="{row}">
                <div class="flex items-center">
                    <p class="text-80 font-bold">
                        <svg v-if="isDecrease(row)" class="text-success fill-current mr-2" width="20"
                             height="12">
                            <path d="M2 3a1 1 0 0 0-2 0v8a1 1 0 0 0 1 1h8a1 1 0 0 0 0-2H3.414L9 4.414l3.293 3.293a1 1 0 0 0 1.414 0l6-6A1 1 0 0 0 18.293.293L13 5.586 9.707 2.293a1 1 0 0 0-1.414 0L2 8.586V3z"/>
                        </svg>

                        <svg v-if="isIncrease(row)" class="text-danger fill-current mr-2"
                             width="20" height="12">
                            <path d="M20 15a1 1 0 0 0 2 0V7a1 1 0 0 0-1-1h-8a1 1 0 0 0 0 2h5.59L13 13.59l-3.3-3.3a1 1 0 0 0-1.4 0l-6 6a1 1 0 0 0 1.4 1.42L9 12.4l3.3 3.3a1 1 0 0 0 1.4 0L20 9.4V15z"/>
                        </svg>
                        <span>{{row.quantity}}</span>
                    </p>
                </div>
            </template>
        </el-table-column>

        <el-table-column
                prop="action_type_name"
                label="变动类型">
        </el-table-column>

        <el-table-column
                prop="origin.name"
                label="变动来源">
        </el-table-column>

        <el-table-column
                align="right"
                prop="updated_at"
                label="更新时间">
        </el-table-column>

        <infinite-loading
                spinner="waveDots"
                :identifier="infiniteId"
                slot="append"
                @infinite="infiniteHandler"
                force-use-infinite-wrapper=".el-table__body-wrapper">
            <span slot="no-more"></span>
            <div slot="no-results"></div>
        </infinite-loading>
    </el-table>
</template>

<script>
  import Pageable from '../../pageable'

  export default {
    name: 'inventory-history',

    mixins: [Pageable],

    props: {
      variantId: {
        type: [Number, String]
      },
      warehouseId: {
        type: [Number, String]
      }
    },

    data () {
      return {
        lists: [],
        response: {},
        infiniteId: +new Date(),
      }
    },

    watch: {
      warehouseId: function (value) {
        this.currentPage = 1
        this.lists = []
        this.infiniteId += 1
      },
      variantId: function (value) {
        this.currentPage = 1
        this.lists = []
        this.infiniteId += 1
      }
    },

    methods: {
      isIncrease (row) {
        return _.get(row, 'type.action') === 'put'
      },
      isDecrease (row) {
        return _.get(row, 'type.action') === 'take'
      },
      variantName (row) {
        return _.get(row, 'variant.name', _.get(row, 'variant.variantName', '-'))
      },
      stateClass (row) {
        return row.warehouse_area === 'good' ? 'bg-green' : 'bg-red'
      },
      stateText (row) {
        return row.warehouse_area === 'good' ? '良品' : '不良品'
      },
      infiniteHandler ($state) {
        if (!this.warehouseId) {
          $state.complete()
          return
        }
        axios.get(`/inventory-actions`, {params: this.requestVariantParams})
          .then(({data}) => {
            this.response = data
            data.current_page++
            this.setOptions(data)
            this.lists.push(...data.data)
            if (data.next_page_url) {
              $state.loaded()
            } else {
              $state.complete()
            }
          })
      },
    },

    computed: {
      requestVariantParams () {
        return {
          warehouse_id: this.warehouseId,
          variant_id: this.variantId,
          page: this.currentPage,
          include: 'type,origin'
        }
      }
    }

  }
</script>

<style scoped>

</style>