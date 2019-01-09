<template>
    <div>
        <div class="flex">
            <div class="flex items-center h-9 mb-3 flex-no-shrink">
                <div>
                    <h4 class="text-90 font-normal text-2xl flex-no-shrink">
                        <slot name="title">
                            {{ label }}
                        </slot>
                    </h4>
                </div>
            </div>
            <div v-if="canCreate" class="w-full flex items-center mb-3">
                <div class="flex-no-shrink ml-auto">
                    <button @click="centerDialogVisible = true" class="btn btn-default btn-primary">{{ '创建'+label }}
                    </button>
                </div>
            </div>
        </div>
        <div class="form-list mb-6">
            <empty-resources :message="`暂无${label}`"></empty-resources>
        </div>
        <el-dialog
                :title="`创建${label}`"
                :visible.sync="centerDialogVisible"
                width="50%"
        >
            <div class="card-no-shadow p-6 w-full">
                <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                         label-width="80px">
                    <address-form :collect-name="collectName" :address.sync="form.address"></address-form>
                </el-form>
            </div>
            <span slot="footer" class="dialog-footer">
                <el-button @click="centerDialogVisible = false">取消</el-button>
                <el-button type="primary" @click="submit">确定</el-button>
            </span>
        </el-dialog>
    </div>
</template>

<script>

  export default {
    name: 'detail-address',

    props: {
      label: {
        type: String,
        default: '地址'
      },
      api: {
        type: String
      },
      collectName: {
        type: String
      },
      address: {
        type: Object,
        default: () => {}
      }
    },
    data () {
      return {
        loading: false,
        canCreate: true,
        centerDialogVisible: false,
        rules: {},
        form: {
          address: {}
        }
      }
    },

    methods: {
      submit () {

      }
    }
  }
</script>

<style scoped>

</style>