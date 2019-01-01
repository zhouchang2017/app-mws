<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">
                <p class="text-xs font-semibold text-50">产品基本信息</p>
                <div class="border-30 border-b mb-3"></div>
                <el-form-item label="产品名称" prop="name">
                    <translation-field attribute="产品名称" v-model="form.name"></translation-field>
                </el-form-item>
                <el-form-item label="产品编码" prop="code">
                    <el-input placeholder="请输入产品编码" v-model="form.code"></el-input>
                </el-form-item>
                <el-form-item label="产品分类" prop="taxon">
                    <el-cascader
                            :show-all-levels="false"
                            :options="taxons"
                            :props="optionConfig"
                            placeholder="请选择产品分类"
                            v-model="form.taxon"
                            filterable
                            clearable
                    ></el-cascader>
                </el-form-item>
                <p class="text-xs font-semibold text-50">产品属性</p>
                <div class="border-30 border-b mb-3"></div>
                <div class="mb-3" v-if="attributes.length === 0">
                    <el-alert
                            :title="emptyAttributesTips"
                            type="error"
                            :closable="false">
                    </el-alert>
                </div>

                <template v-for="(attr,index) in attributes">
                    <el-form-item :label="attr.name"
                                  :prop="`attributes.${index}.value`"
                    >
                        <translation-field :attribute="attr.name"
                                           v-model="form.attributes[index].value"></translation-field>
                    </el-form-item>
                </template>

                <p class="text-xs font-semibold text-50">产品销售属性</p>
                <div class="border-30 border-b mb-3"></div>
                <div class="mb-3" v-if="options.length === 0">
                    <el-alert
                            :title="emptyOptionsTips"
                            type="error"
                            :closable="false">
                    </el-alert>
                </div>
                <el-form-item label="可选销售属性" v-else prop="options">
                    <el-checkbox-group v-model="form.options">
                        <el-checkbox v-for="option in options" :label="option.id"
                                     :key="option.id">
                            <el-tooltip effect="dark" placement="top">
                                <div slot="content">
                                    <div v-for="translation in option.translations" :key="translation.id">
                                        <span>{{ toLocaleCode(translation.locale_code) }}</span>:
                                        <span>{{ translation.name }}</span>
                                    </div>
                                </div>
                                <span>{{option.name}}</span>
                            </el-tooltip>
                        </el-checkbox>
                    </el-checkbox-group>
                </el-form-item>
            </el-form>
        </div>
        <div class="bg-30 flex px-8 py-4">
            <div class="ml-auto"
            >
                <button @click="submit" type="button"
                        class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        创建产品
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'product-form',
    data () {
      return {
        optionConfig: {
          value: 'id',
          label: 'name'
        },
        loading: false,
        form: {
          name: {},
          code: '',
          taxon: [],
          attributes: {},
          options: [],
        },
        taxons: [],
        attributes: [],
        options: [],
        rules: {
          name: [
            {validator: this.checkName, trigger: 'blur'}
          ],
          code: [{required: true, message: '请输入产品编码', trigger: 'blur'}],
          options: [
            {required: true, type: 'array', message: '请输入产品销售属性', trigger: 'change'},
            {type: 'array', min: 1, message: '至少选择一项销售属性', trigger: 'change'}
          ]
        }
      }
    },

    watch: {
      taxon: async function (value, oldValue) {
        if (value && value !== oldValue) {
          await Promise.all([
            this.fetchAttributes(),
            this.fetchOptions()
          ])
        }
      }
    },
    methods: {
      checkName (rule, value, callback) {
        console.log(rule)
        _.each(this.appConfig.locales, (locale, key) => {
          if (!value[key]) {
            return callback(new Error(`${locale}名称不能为空`))
          }
        })
        callback()
      },
      formData () {
        const clone = _.cloneDeep(this.form)
        delete clone.taxon
        clone.taxon_id = this.taxon
        return clone
      },
      submit () {
        this.$refs['form'].validate((valid) => {
          if (valid) {
            this.loading = true
            axios.post('/products', this.formData()).then(({data}) => {
              console.log(data)
              this.notify(data)
              // this.state.push(data.data)
              // this.resetForm()
            })
            this.loading = false
          } else {
            this.notify({type: 'error', title: '表单数据不合法'})
            return false
          }
        })
      },
      resetForm () {
        this.$refs['form'].resetFields()
      },
      fetchTaxons () {
        return axios.get('/taxons').then(({data}) => {
          this.taxons = data
        })
      },
      fetchAttributes () {
        this.form.attributes = []
        return axios.get('/product-attributes?taxon=' + this.taxon).then(({data}) => {
          this.attributes = data
          this.form.attributes = data.map(item => {
            return {
              id: item.id,
              value: {}
            }
          })
        })
      },
      fetchOptions () {
        return axios.get('/product-options?taxon=' + this.taxon).then(({data}) => {
          this.options = data
        })
      },
      toLocaleCode (code) {
        return _.get(this, `appConfig.locales.${code}`)
      }
    },

    computed: {
      // 当前所选分类
      taxon () {
        return _.last(this.form.taxon)
      },

      emptyAttributesTips () {
        if (_.isUndefined(this.taxon)) {
          return '请选择产品分类'
        }
        if (this.attributes.length === 0 && this.taxon) {
          return '该分类暂无匹配属性'
        }
      },
      emptyOptionsTips () {
        if (_.isUndefined(this.taxon)) {
          return '请选择产品分类'
        }
        if (this.options.length === 0 && this.taxon) {
          return '该分类暂无匹配销售属性'
        }
      }
    },
    async created () {
      await this.fetchTaxons()
    }
  }
</script>

<style scoped>

</style>