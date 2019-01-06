<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">
                <p class="text-xs font-semibold text-50">变体基本信息</p>
                <div class="border-30 border-b mb-3"></div>
                <el-form-item label="所属产品" prop="product_id">
                    <el-select
                            :disabled="viaRelationship"
                            class="w-full"
                            v-model="form.product_id"
                            filterable
                            remote
                            placeholder="请输产品编码"
                            :remote-method="remoteProducts"
                            :loading="remoteLoading">
                        <el-option
                                v-for="item in products"
                                :key="item.code"
                                :label="item.name"
                                :value="item.id">
                            <div class="flex items-bottom justify-between">
                                <div class="font-semibold text-xs text-80">{{item.name}}</div>
                                <div class="text-xs  text-70">{{item.code}}</div>
                            </div>
                        </el-option>
                        <infinite-loading @infinite="infiniteHandler">
                        </infinite-loading>
                    </el-select>
                </el-form-item>
                <el-form-item label="变体名称" prop="name">
                    <translation-field attribute="变体名称" v-model="form.name"></translation-field>
                </el-form-item>
                <el-form-item label="变体编码" prop="code">
                    <el-input :disabled="updatePage" placeholder="请输入变体编码" v-model="form.code"></el-input>
                </el-form-item>
                <el-form-item label="宽度" prop="width">
                    <el-input placeholder="请输入变体宽度" v-model.number.trim="form.width">
                        <template slot="append">Cm(厘米)</template>
                    </el-input>
                </el-form-item>
                <el-form-item label="高度" prop="height">
                    <el-input placeholder="请输入变体高度" v-model.number.trim="form.height">
                        <template slot="append">Cm(厘米)</template>
                    </el-input>
                </el-form-item>
                <el-form-item label="长度" prop="length">
                    <el-input placeholder="请输入变体长度" v-model.number.trim="form.length">
                        <template slot="append">Cm(厘米)</template>
                    </el-input>
                </el-form-item>
                <el-form-item label="重量" prop="weight">
                    <el-input placeholder="请输入变体重量" v-model.number.trim="form.weight">
                        <template slot="append">Kg(公斤)</template>
                    </el-input>
                </el-form-item>
                <p class="text-xs font-semibold text-50">变体销售属性</p>
                <div class="border-30 border-b mb-3"></div>
                <el-form-item v-for="(option,index) in options"
                              :label="option.name"
                              :key="option.id"
                              :prop="`options.${index}.value`"
                              :rules="[
                                {required: true, message: `请输${option.name}销售属性值`, trigger: 'blur' },
                                {validator: checkName, trigger: 'blur'}
                              ]"
                >
                    <translation-field :attribute="option.name + '销售属性值'"
                                       v-model="form.options[index].value"></translation-field>
                    <!--<el-radio  v-for="value in option.values"-->
                    <!--:key="value.id"-->
                    <!--v-model="options[index].value" :label="value">{{ value.value }}</el-radio>-->
                </el-form-item>
                <el-form-item label="销售价格" prop="price">
                    <el-input placeholder="请输入销售价格" v-model.number="form.price">
                        <template slot="append">$(美元)</template>
                    </el-input>
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
  import Relationable from '../relationable'
  import BaseForm from '../baseForm'
  import Pageable from '../pageable'
  import InfiniteLoading from 'vue-infinite-loading'
  import Translatable from '../translatable'

  export default {
    name: 'product-variant-form',

    mixins: [Relationable, BaseForm, Pageable, Translatable],

    components: {
      InfiniteLoading,
    },

    data () {
      return {
        form: {
          product_id: null,
          name: {},
          code: null,
          options: [],
          width: null,
          height: null,
          length: null,
          weight: null,
          price: null
        },
        rules: {
          product_id: [
            {required: true, message: '请输选择所属产品', trigger: 'blur'},
          ],
          name: [
            {required: true, message: '请输变体名称', trigger: 'blur'},
            {validator: this.checkName, trigger: 'blur'},
          ],
          width: [
            {required: true, message: '请输变体宽度', trigger: 'blur'},
            {type: 'number', message: '必须为数字类型', trigger: 'blur'},
          ],
          height: [
            {required: true, message: '请输变体高度', trigger: 'blur'},
            {type: 'number', message: '必须为数字类型', trigger: 'blur'},
          ],
          length: [
            {required: true, message: '请输变体长度', trigger: 'blur'},
            {type: 'number', message: '必须为数字类型', trigger: 'blur'},
          ],
          weight: [
            {required: true, message: '请输变体重量', trigger: 'blur'},
            {type: 'number', message: '必须为数字类型', trigger: 'blur'},
          ],
          code: [
            {required: true, message: '请输变体编码', trigger: 'blur'},
            {max: 255, message: '长度溢出', trigger: 'blur'},
            {validator: this.checkCode, trigger: 'blur'},
          ],
          price: [
            {required: true, message: '请输变体价格', trigger: 'blur'},
          ]
        },
        products: [],
        remoteLoading: false,
        response: {},
        query: null,
        options: []
      }
    },

    watch: {
      'form.product_id': async function (value) {
        if (value) {
          await this.fetchProductOptions(value)
        }
      }
    },

    methods: {
      checkCode (rule, value, callback) {
        if (!value) {
          return callback(new Error('变体编码不能为空'))
        }
        if (this.updatePage) {
          return callback()
        }
        axios.get(`/product-variants/check/code/${value}`).then(({data}) => {
          if (data) {
            return callback()
          }
          return callback(new Error('该编码已被占用'))
        })
      },
      remoteProducts (query) {
        if (query !== '') {
          this.query = query
          this.currentPage = 1
          this.remoteLoading = true
          axios.get(`/products?search=${query}&page=${this.currentPage}`).then(({data}) => {
            this.products = data.data
            data.current_page++
            this.response = data
            this.setOptions(data)
            this.remoteLoading = false

          })
        }
      },
      infiniteHandler ($state) {
        axios.get(`/products?search=${this.query}&page=${this.currentPage}`)
             .then(({data}) => {
               this.response = data
               data.current_page++
               this.setOptions(data)
               this.products.push(...data.data)
               if (data.next_page_url) {
                 $state.loaded()
               } else {
                 $state.complete()
               }
             })
      },
      findProduct (id) {
        return axios.get(`/products/${id}`).then(({data}) => {
          this.products.push(data)
        })
      },
      fetchProductOptions (id) {
        return axios.get(`/products/${id}/options`).then(({data}) => {
          this.options = data
          this.form.options = data.map(item => {
            if (this.updatePage && _.get(this, 'resource.product.id') === +this.viaRelationId) {
              const optionValues = _.get(this, 'resource.option_values', [])
              const target = _.find(optionValues, ['option_id', item.id])
              if (target) {
                return {
                  id: target.id,
                  option_id: item.id,
                  value: this.formatTranslationValue('value', target.translations)
                }
              }
            }
            return {
              option_id: item.id,
              value: {}
            }
          })
        })
      }
    },
    async mounted () {
      if (this.viaRelationship) {
        await this.findProduct(this.viaRelationId)
        this.form.product_id = +this.viaRelationId
      }
      if (this.updatePage) {
        this.fillTranslationField('name', _.get(this, 'resource.translations'), 'form.name')
        this.form.code = _.get(this, 'resource.code')
        this.form.width = _.get(this, 'resource.width')
        this.form.height = _.get(this, 'resource.height')
        this.form.length = _.get(this, 'resource.length')
        this.form.weight = _.get(this, 'resource.weight')
        this.form.price = _.get(this, 'resource.price.price', null)

      }
    }

  }
</script>

<style scoped>

</style>