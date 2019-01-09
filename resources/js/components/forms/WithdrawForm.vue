<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">

                <el-form-item label="入库计划描述" prop="description">
                    <el-input type="textarea" v-model="form.description"></el-input>
                </el-form-item>

                <el-form-item label="仓库" prop="warehouse_id">
                    <remote-select-field
                            @change="item=>form.warehouse_id = item"
                            :default-value="form.warehouse_id"
                            uri-key="warehouses"
                    ></remote-select-field>
                </el-form-item>

                <el-form-item label="产品">
                    
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
          has_ship: true
        },
        items: [],
        marketables: [],
        rules: {
          warehouse_id: [
            {required: true, message: '选择仓库', trigger: 'change'},
          ]
        }
      }
    },
    watch: {
      warehouseId: async function (value) {
        if (value) {
          await this.fetchVariants()
        }
      }
    },
    methods: {
      createResourceFormData () {
        return this.form
      },
      updateResourceFormData () {
        return this.form
      },
      fetchVariants () {
        return axios.get(`/inventories`, {
          params: this.requestVariantParams
        })
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