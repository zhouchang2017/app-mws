<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">
                <p class="text-xs font-semibold text-50">促销计划基本信息</p>
                <div class="border-30 border-b mb-3"></div>
                <el-form-item label="所属促销活动" prop="promotion_id">
                    <remote-select-field
                            ref="promotion"
                            :disabled="viaRelationship"
                            :defaultValue="+viaRelationId"
                            :defaultOptions="promotions"
                            uriKey="promotions"
                            @change="value=>form.promotion_id = value"
                    >
                        <template slot-scope="{item}">
                            <div class="flex items-bottom justify-between">
                                <div class="font-semibold text-xs text-80">{{item.name}}</div>
                                <div class="text-xs  text-70">{{item.code}}</div>
                            </div>
                        </template>
                    </remote-select-field>
                </el-form-item>
                <div class="mb-3" v-if="channel">
                    <el-alert
                            :title="`活动所属DP频道${channel.name}`"
                            type="info"
                            show-icon>
                    </el-alert>
                </div>

                <el-form-item label="供应商" prop="supplier_id">
                    <remote-select-field
                            uriKey="suppliers"
                            @change="value=>form.supplier_id = value"
                    ></remote-select-field>
                </el-form-item>

                <el-form-item label="请选择参加促销计划变体">
                    <select-product-variant-list
                            :supplier-id="form.supplier_id"
                            :channel-id="channelId"
                            uri-key="product-variants"
                            @change="item=>variants = item"
                    ></select-product-variant-list>
                </el-form-item>

                <el-form-item label="设置变体">
                    <selected-product-variant-list
                            :channel-id="channelId"
                            :selections="variants"
                    ></selected-product-variant-list>
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
        variants: [],
        promotions: [],
        response: {},
        query: null,
        options: [],
        loading: false,
        promotion: null
      }
    },

    methods: {

      findPromotion (id) {
        return axios.get(`/promotions/${id}`).then(({data}) => {
          this.promotions.push(data)
        })
      },

      // 创建表单数据
      createResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {
            formData.variants = this.variants.map(variant=>{
              const {id,product_id} = variant
              return Object.assign({},{variant_id:id,product_id},variant.fill())
            })
        })
      },

    },

    computed: {
      channel () {
        return _.head(_.get(this, 'promotion.selected.channels', []))
      },
      channelId () {
        return _.get(this, 'channel.id', null)
      }
    },

    async created () {
      if (this.viaRelationship) {
        await this.findPromotion(this.viaRelationId)
      }
    },

    async mounted () {
      if (this.updatePage) {

      }
      this.$nextTick(() => {
        const {promotion} = this.$refs
        this.promotion = promotion
      })
      this.loading = false
    }

  }
</script>

<style scoped>

</style>