<template>
    <div>
        <div class="p-6">
            <el-form v-if="init" v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">
                <el-form-item :label="`${singularLabel}名称`" prop="name">
                    <el-input :placeholder="`请输入${singularLabel}名称`"
                              v-model="form.name"></el-input>
                </el-form-item>

                <el-form-item :label="`${singularLabel}类型`" prop="type_id">
                    <el-select v-model="form.type_id" filterable placeholder="请选择">
                        <el-option
                                v-for="item in types"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                        </el-option>
                    </el-select>
                </el-form-item>

                <el-form-item label="仓库管理员" prop="user_id">
                    <remote-select-field
                            uri-key="users"
                            :default-value="form.user_id"
                            @change="item=>form.user_id = item"
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

  export default {
    name: 'warehouse-form',
    mixins: [BaseForm],

    data () {
      return {
        form: {
          name: null,
          type_id: null,
          user_id: null
        },
        rules: {
          name: [
            {required: true, message: `请输${this.singularLabel}名称`, trigger: 'blur'},
            {max: 255, message: `长度溢出`, trigger: 'blur'},
          ],
          user_id: [
            {required: true, message: `请选择仓库管理员`, trigger: 'change'},
          ],
          type_id: [
            {required: true, message: `请选择仓库类型`, trigger: 'change'},
          ],
        },
        types: [],
        response: null,
        users: [],
        init: false
      }
    },

    methods: {
      fetchWarehouseTypes () {
        return axios.get('/warehouse-types?withoutPage')
      },

      async fillWarehouseTypes () {
        const {data} = await this.fetchWarehouseTypes()
        this.types = data
      },
    },
    async created () {
      await this.fillWarehouseTypes()
      if (this.updatePage) {
        this.form.name = _.get(this, 'resource.name')
        this.form.type_id = _.get(this, 'resource.type_id')
        this.form.user_id = _.get(this, 'resource.user_id')
      }
      this.init = true
    }
  }
</script>

<style scoped>

</style>