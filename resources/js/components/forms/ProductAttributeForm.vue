<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">

                <el-form-item :label="singularLabel+'名称'" prop="name">
                    <translation-field :attribute="singularLabel" v-model="form.name"></translation-field>
                </el-form-item>
                <el-form-item :label="`${singularLabel}编码`" prop="code">
                    <el-input :disabled="updatePage" :placeholder="`请输入${singularLabel}编码`"
                              v-model="form.code"></el-input>
                </el-form-item>
                <el-form-item label="所属分类" prop="taxon">
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
                <el-form-item label="排序权重" prop="position">
                    <el-slider :min="0" :max="100" v-model="form.position"></el-slider>
                </el-form-item>
                <template v-if="!isOption">
                    <el-form-item label="Type" prop="type">
                        <el-radio disabled v-model="form.type" label="text">文本</el-radio>
                    </el-form-item>
                    <el-form-item label="StorageType" prop="storage_type">
                        <el-radio disabled v-model="form.storage_type" label="text">文本</el-radio>
                    </el-form-item>
                </template>


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
  import Translatable from '../../translatable'

  export default {
    name: 'product-attribute-form',

    mixins: [BaseForm, Translatable],

    props: {
      isOption: {
        type: Boolean,
        default: false
      }
    },

    data () {
      return {
        optionConfig: {
          value: 'id',
          label: 'name'
        },
        form: {
          name: {},
          code: null,
          position: 0,
          type: 'text',
          storage_type: 'text',
          taxon: []
        },
        taxons: [],
        rules: {
          name: [
            {required: true, message: `请输${this.singularLabel}名称`, trigger: 'blur'},
            {validator: this.checkName, trigger: 'blur'},
          ],
          code: [
            {required: true, message: `请输${this.singularLabel}编码`, trigger: 'blur'},
            {max: 255, message: '长度溢出', trigger: 'blur'},
          ],
        }
      }
    },
    methods: {
      createResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {
          delete formData.taxon
          formData.taxon_id = this.taxon
          if (this.isOption) {
            delete formData.type
            delete formData.storage_type
          }
        })
      },
      updateResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {
          delete formData.taxon
          formData.taxon_id = this.taxon
          if (this.isOption) {
            delete formData.type
            delete formData.storage_type
          }
        })
      },
      fetchTaxons () {
        return axios.get('/taxons').then(({data}) => {
          this.taxons = data
        })
      },
      fillTaxon () {
        this.form.taxon = _.get(this, 'resource.taxon.ancestors')
      },
    },
    computed: {
      // 当前所选分类
      taxon () {
        return _.last(this.form.taxon)
      },
    },
    async created () {
      await this.fetchTaxons()
      if (this.updatePage) {

        this.fillTranslationField('name', _.get(this, 'resource.translations'), 'form.name')
        this.form.code = _.get(this, 'resource.code')
        this.fillTaxon()
        this.form.position = _.get(this, 'resource.position')
        if (!this.isOption) {
          this.form.storage_type = _.get(this, 'resource.storage_type')
          this.form.type = _.get(this, 'resource.type')
        }

      }
    }
  }
</script>

<style scoped>

</style>