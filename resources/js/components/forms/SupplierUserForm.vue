<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">

                <el-form-item :label="singularLabel" prop="name">
                    <el-input :placeholder="`请输入${singularLabel}名称`"
                              v-model="form.name"></el-input>
                </el-form-item>
                <el-form-item label="邮箱" prop="email">
                    <el-input :placeholder="`请输入${singularLabel}Email`"
                              v-model="form.email"></el-input>
                </el-form-item>

                <el-form-item label="手机" prop="phone">
                    <el-input :placeholder="`请输入${singularLabel}手机`"
                              v-model="form.phone"></el-input>
                </el-form-item>

                <el-form-item label="密码" prop="password">
                    <el-input type="password" v-model="form.password" autocomplete="off"></el-input>
                </el-form-item>

                <el-form-item label="确认密码" prop="checkPass">
                    <el-input type="password" v-model="form.checkPass" autocomplete="off"></el-input>
                </el-form-item>

                <el-form-item label="供应商" prop="supplier_id">
                    <remote-select-field
                            :disabled="viaRelationship || !isAdmin"
                            :default-value="form.supplier_id"
                            @change="item=>form.supplier_id = item"
                            uri-key="suppliers"
                    ></remote-select-field>
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
  import Relationable from '../../relationable'
  export default {
    name: 'supplier-user-form',

    mixins: [BaseForm,Relationable],

    props: {},

    data () {
      let validatePass = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请输入密码'));
        } else {
          if (this.form.checkPass !== '') {
            this.$refs.form.validateField('checkPass');
          }
          callback();
        }
      };
      let validatePass2 = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请再次输入密码'));
        } else if (value !== this.form.password) {
          callback(new Error('两次输入密码不一致!'));
        } else {
          callback();
        }
      };

      return {
        optionConfig: {
          value: 'id',
          label: 'name'
        },
        form: {
          name: null,
          phone: null,
          email: null,
          supplier_id: null,
          password:null,
          checkPass:null
        },
        rules: {
          name: [
            {required: true, message: `请输${this.singularLabel}名称`, trigger: 'blur'},
            {validator: this.checkName, trigger: 'blur'},
          ],
          phone: [
            {required: true, message: `请输${this.singularLabel}手机`, trigger: 'blur'},
            {validator:this.checkPhone, trigger: 'blur'},
          ],
          email: [
            { required: true, message: '请输入邮箱地址', trigger: 'blur' },
            { type: 'email', message: '请输入正确的邮箱地址', trigger: ['blur', 'change'] }
          ],
          password: [
            { required: true, message: '请输入密码', trigger: 'blur' },
            { min: 6, message: '最小长度6位', trigger: 'blur' },
            { validator: validatePass, trigger: 'blur' }
          ],
          checkPass: [
            { required: true, message: '请再次输入密码', trigger: 'blur' },
            { min: 6, message: '最小长度6位', trigger: 'blur' },
            { validator: validatePass2, trigger: 'blur' }
          ],
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
    computed: {},
    async created () {

      if (this.viaRelationship) {
        this.form.supplier_id = +this.viaRelationId
      }

      if (this.updatePage) {
        this.form.name = _.get(this, 'resource.name')
        this.form.supplier_id = _.get(this, 'resource.supplier_id')
        this.form.phone = _.get(this, 'resource.phone')
        this.form.email = _.get(this, 'resource.email')
      }
    }
  }
</script>

<style scoped>

</style>