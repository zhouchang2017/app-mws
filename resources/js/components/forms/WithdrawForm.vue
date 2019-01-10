<template>
    <div>
        <div class="p-6">
            <el-form  v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">

                <el-form-item label="退仓申请说明" prop="description">
                    <el-input type="textarea" v-model="form.description"></el-input>
                </el-form-item>

                <el-form-item label="仓库" prop="warehouse_id">
                    <remote-select-field
                            init
                            @change="item=>form.warehouse_id = item"
                            :default-value="form.warehouse_id"
                            uri-key="warehouses"
                    >
                        <div slot-scope="{item}" class="flex items-bottom justify-between">
                            <div class="font-semibold text-xs text-80">{{item.name}}</div>
                            <div class="text-xs  text-70"><span>存量:</span>{{item.inventories_count}}</div>
                        </div>
                    </remote-select-field>
                </el-form-item>

                <el-form-item label="产品">
                    <el-table
                            ref="listsTable"
                            row-key="id"
                            @selection-change="handleSelectionChange"
                            tooltip-effect="dark"
                            :data="lists"
                    >
                        <el-table-column
                                type="selection"
                                width="55">
                        </el-table-column>
                        <el-table-column
                                prop="variant.code"
                                label="变体编码"
                        >
                        </el-table-column>
                        <el-table-column
                                prop="variant.name"
                                label="变体名称"
                        >
                        </el-table-column>
                        <el-table-column
                                prop="quantity"
                                label="当前库存数量">
                        </el-table-column>
                        <el-table-column
                                prop="warehouse_area"
                                label="仓库区域">
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
                </el-form-item>

                <el-form-item label="以选中退仓产品(变体)" prop="items">
                    <el-table
                            row-key="id"
                            :data="form.items"
                    >
                        <el-table-column
                                prop="variant.code"
                                label="变体编码"
                        >
                        </el-table-column>
                        <el-table-column
                                prop="variant.name"
                                label="变体名称"
                        >
                        </el-table-column>
                        <el-table-column
                                prop="stock"
                                label="当前库存数量">
                        </el-table-column>
                        <el-table-column
                                label="退仓数量">
                            <template slot-scope="{row,$index}">
                                <el-form-item  :prop="`items.${$index}.quantity`"  :rules="[
                                     { required: true, message: '请输退仓数量', trigger: 'blur' },
                                     { max:row.stock,type:'number', message:'数量溢出' , trigger: 'blur'}
                                ]">
                                    <el-input v-model.number="row.quantity" placeholder="请输入数量"></el-input>
                                </el-form-item>
                            </template>
                        </el-table-column>
                        <el-table-column
                                label="操作">
                            <template slot-scope="{row,$index}">
                                <el-popover
                                        placement="top"
                                        width="160"
                                        v-model="row.visible">
                                    <p>确定删除吗？</p>
                                    <div style="text-align: right; margin: 0">
                                        <el-button size="mini" type="text" @click="row.visible = false">取消</el-button>
                                        <el-button type="primary" size="mini" @click="removeSelection(row,$index)">确定
                                        </el-button>
                                    </div>
                                    <el-button size="small" slot="reference" type="danger">移除</el-button>
                                </el-popover>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-form-item>


                <el-form-item label="运输方式">
                    <el-radio-group v-model="form.has_ship">
                        <el-radio :label="true">物流运输</el-radio>
                        <el-radio disabled :label="false">无需物流</el-radio>
                    </el-radio-group>
                </el-form-item>

            </el-form>
        </div>
        <div class="bg-30 flex px-8 py-4">
            <div class="ml-auto"
            >
                <button @click="submit" type="button"
                        class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        {{submitText}}
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  import BaseForm from '../../baseForm'
  import Pageable from '../../pageable'

  export default {
    name: 'withdraw-form',
    mixins: [BaseForm, Pageable],

    data () {
      return {
        form: {
          description: null,
          warehouse_id: null,
          has_ship: true,
          items: []
        },
        selections: [],
        rules: {
          warehouse_id: [
            {required: true, message: '选择仓库', trigger: 'change'},
          ],
          items:[
            {required: true, message: '请选择退仓商品', trigger: 'change'},
            {type:'array',len:1,message:'至少选中一件商品',trigger:['change']}
          ]
        },
        response: {},
        lists: [],
        infiniteId: +new Date(),
      }
    },

    watch: {
      warehouseId: function (value) {
        this.currentPage = 1
        this.lists = []
        this.infiniteId += 1
        this.form.items = []
      }
    },

    methods: {
      validateEvent(prop){
        console.log(prop)
      },
      // 创建表单数据
      createResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {
            formData.items.forEach(item=>{
              delete item.variant
              delete item.stock
              delete item.visible
            })
        })
      },
      updateResourceFormData () {
        return this.form
      },
      infiniteHandler ($state) {
        if (!this.warehouseId) {
          $state.complete()
          return
        }
        axios.get(`/inventories`, {params: this.requestVariantParams})
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

      handleSelectionChange (val) {
        val.forEach(select => {
          if (!_.find(this.form.items, ['inventory_id', select.id])) {
            const {id, warehouse_id, warehouse_area, product_id, variant_id, quantity,variant} = select
            this.form.items.push({inventory_id:id,variant, warehouse_id, warehouse_area, product_id, variant_id, stock: quantity, quantity})
          }
        })
        this.form.items = this.form.items.filter(item => val.map(v => v.id).includes(item.inventory_id))
      },

      removeSelection (row, index) {
        console.log(this.lists[index])
        this.$refs.listsTable.toggleRowSelection(this.lists[index])
        this.form.items.splice(index, 1)
      }
    },
    computed: {
      warehouseId () {
        return _.get(this, 'form.warehouse_id')
      },
      requestVariantParams () {
        return {
          warehouse_id: this.warehouseId,
          page: this.currentPage,
        }
      }
    },
    async created () {

      if (this.updatePage) {

      }
    }
  }
</script>

<style scoped>

</style>