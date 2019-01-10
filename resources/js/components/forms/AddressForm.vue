<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     :label-width="width">
                <el-form-item label="联系人" prop="name">
                    <el-input placeholder="请输入联系人"
                              v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="座机" prop="tel">
                    <el-input placeholder="请输入座机"
                              v-model="form.tel"></el-input>
                </el-form-item>
                <el-form-item label="手机" prop="phone">
                    <el-input placeholder="请输入手机"
                              v-model="form.phone"></el-input>
                </el-form-item>
                <el-form-item label="传真" prop="fax">
                    <el-input placeholder="请输入传真"
                              v-model="form.fax"></el-input>
                </el-form-item>
                <el-form-item label="邮编" prop="zip">
                    <el-input placeholder="请输入邮编"
                              v-model="form.zip"></el-input>
                </el-form-item>

                <el-form-item label="省市区" prop="district">
                    <area-select-field :default-value="defaultArea" @change="areaChange"></area-select-field>
                </el-form-item>

                <el-form-item label="详细地址" prop="address">
                    <el-input placeholder="请输入详细地址"
                              v-model="form.address"></el-input>
                </el-form-item>
            </el-form>
        </div>
        <slot :form="form">
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
        </slot>
    </div>

</template>

<script>
  import BaseForm from '../../baseForm'

  export default {
    name: 'address-form',

    mixins: [BaseForm],

    props: {
      collectionName: {
        type: String,
        default: null
      },
      width: {
        type: String,
        default: '180px'
      }
    },
    data () {
      return {
        form: {
          collection_name: null,
          name: null,
          tel: null,
          phone: null,
          fax: null,
          zip: null,
          country: '中国',
          province: null,
          city: null,
          district: null,
          address: null
        },
        rules: {
          name: [
            {required: true, message: `请输${this.singularLabel}联系人`, trigger: 'blur'},
          ],
          phone: [
            {required: true, message: `请输${this.singularLabel}手机`, trigger: 'blur'},
            {validator: this.checkPhone, trigger: 'blur'},
          ],
          district: [
            {required: true, message: `请输选择地区`, trigger: 'change'},
          ]
        },
        defaultArea: []
      }
    },

    methods: {
      areaChange ({province, city, district}) {
        this.form = {...this.form, province, city, district}
      },
      fillForm () {
        if (this.updatePage) {
          this.form = this.resource
          this.defaultArea.push(...[
            _.get(this.resource,'province'),
            _.get(this.resource,'city'),
            _.get(this.resource,'district'),
          ])
        }
      }
    },

    async mounted () {
      if (this.collectionName) {
        this.form.collection_name = this.collectionName
      }
      this.fillForm()
    }
  }
</script>

<style scoped>

</style>