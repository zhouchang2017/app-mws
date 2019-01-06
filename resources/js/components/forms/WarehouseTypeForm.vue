<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">

                <el-form-item :label="`${singularLabel}名称`" prop="name">
                    <el-input :placeholder="`请输入${singularLabel}名称`"
                              v-model="form.name"></el-input>
                </el-form-item>

                <el-form-item :label="`${singularLabel}描述`" prop="description">
                    <el-input type="textarea" :placeholder="`请输入${singularLabel}描述`"
                              v-model="form.description"></el-input>
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
    name: 'warehouse-type-form',
    mixins: [BaseForm],
    data () {
      return {
        form: {
          name: null,
          description: null
        },
        rules: {
          name: [
            {required: true, message: `请输${this.singularLabel}名称`, trigger: 'blur'},
            {max: 255, message: `长度溢出`, trigger: 'blur'},
          ],
          description: [
            {type: 'string', message: `请输文本类型`, trigger: 'blur'},
            {max: 255, message: '长度溢出', trigger: 'blur'},
          ],
        }
      }
    },
    created () {
      if (this.updatePage) {
        this.form.name = _.get(this, 'resource.name')
        this.form.description = _.get(this, 'resource.description')
      }
    }
  }
</script>

<style scoped>

</style>