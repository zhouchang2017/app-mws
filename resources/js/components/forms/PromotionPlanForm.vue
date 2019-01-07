<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">
                <p class="text-xs font-semibold text-50">促销计划基本信息</p>
                <div class="border-30 border-b mb-3"></div>
                <el-form-item label="所属促销活动" prop="promotion_id">
                    <remote-select-field
                            :disabled="viaRelationship"
                            :defaultValue="+viaRelationId"
                            :defaultOptions="promotions"
                            uriKey="promotions"
                            @change="value=>form.promotion_id = value"
                    ></remote-select-field>
                </el-form-item>
                <el-form-item label="供应商" prop="supplier_id">
                    <remote-select-field
                            uriKey="suppliers"
                            @change="value=>form.supplier_id = value"
                    ></remote-select-field>
                </el-form-item>

                <el-form-item label="请选择参加促销计划变体">
                    <select-product-variant-list
                            :supplier-id="form.supplier_id"
                            uri-key="product-variants"
                    ></select-product-variant-list>
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
  import Relationable from '../../relationable'
  import BaseForm from '../../baseForm'

  export default {
    name: 'promotion-plan-form',

    mixins: [Relationable, BaseForm],

    data () {
      return {
        form: {
          promotion_id: null,
          supplier_id: null
        },
        rules: {
          promotion_id: [
            {required: true, message: '请选择促销活动', trigger: 'change'},
          ],
          supplier_id: [
            {required: true, message: '请选择供应商', trigger: 'change'},
          ],
        },
        promotions: [],
        response: {},
        query: null,
        options: [],
        loading: false
      }
    },



    methods: {

      findPromotion (id) {
        return axios.get(`/promotions/${id}`).then(({data}) => {
          this.promotions.push(data)
        })
      },

    },

    async mounted () {
      if (this.viaRelationship) {
        await this.findPromotion(this.viaRelationId)
      }
      if (this.updatePage) {

      }
      this.loading = false
    }

  }
</script>

<style scoped>

</style>