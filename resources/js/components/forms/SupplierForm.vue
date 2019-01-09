<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">

                <el-form-item :label="singularLabel+'名称'" prop="name">
                    <el-input  :placeholder="`请输入${singularLabel}名称`"
                              v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item :label="`${singularLabel}编码`" prop="code">
                    <el-input :disabled="updatePage" :placeholder="`请输入${singularLabel}编码`"
                              v-model="form.code"></el-input>
                </el-form-item>

                <el-form-item :label="`${singularLabel}描述`" prop="description">
                    <el-input type="textarea" :placeholder="`请输入${singularLabel}描述`"
                              v-model="form.description"></el-input>
                </el-form-item>

                <el-form-item label="店长" prop="supplier_user_id">
                    <remote-select-field
                            :default-value="form.supplier_user_id"
                            @change="item=>form.supplier_user_id = item"
                            uri-key="supplier-users"
                    ></remote-select-field>
                </el-form-item>

                <el-form-item  label="官方小二" prop="user_id">
                    <remote-select-field
                            :default-value="form.user_id"
                            @change="item=>form.user_id = item"
                            uri-key="users"></remote-select-field>
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

  export default {
    name: 'supplier-form',

    mixins: [BaseForm],

    props: {

    },

    data () {
      return {
        optionConfig: {
          value: 'id',
          label: 'name'
        },
        form: {
          name: null,
          code: null,
          user_id: null,
          supplier_user_id:null,
          description:null,

        },
        rules: {
          name: [
            {required: true, message: `请输${this.singularLabel}名称`, trigger: 'blur'},
            {validator: this.checkName, trigger: 'blur'},
          ],
          code: [
            {required: true, message: `请输${this.singularLabel}编码`, trigger: 'blur'},
            {max: 255, message: '长度溢出', trigger: 'blur'},
          ],
          user_id:[
            {required: true, message: '请选择官方小二', trigger: 'change'},
          ]
        }
      }
    },
    methods: {
      createResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {

        })
      },
      updateResourceFormData () {
        return _.tap(_.cloneDeep(this.form), formData => {

        })
      },
    },
    computed: {

    },
    async created () {
      if (this.updatePage) {
        this.form.name = _.get(this, 'resource.name')
        this.form.code = _.get(this, 'resource.code')
        this.form.description = _.get(this, 'resource.description')
        this.form.user_id = _.get(this, 'resource.user_id')
        this.form.supplier_user_id = _.get(this, 'resource.supplier_user_id')
      }
    }
  }
</script>

<style scoped>

</style>