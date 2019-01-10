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

            <div v-if="canCreate && isEmpty" class="w-full flex items-center mb-3">
                <div class="flex-no-shrink ml-auto">
                    <button @click="centerDialogVisible = true" class="btn btn-default btn-primary">{{ '创建'+label }}
                    </button>
                </div>
            </div>
            <div v-if="canUpdate && !isEmpty" class="w-full flex items-center mb-3">
                <div class="flex-no-shrink ml-auto">
                    <button @click="centerDialogVisible = true" class="btn btn-default btn-icon bg-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                             aria-labelledby="edit" role="presentation" class="fill-current text-white"
                             style="margin-top: -2px; margin-left: 3px;">
                            <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="form-list mb-6">
            <address-penal :address="value" :label="label"></address-penal>
        </div>
        <el-dialog
                :title="dialogTitle"
                :visible.sync="centerDialogVisible"
                width="50%"
        >
            <div class="card-no-shadow p-6 w-full">
                <address-form :resourceId="addressId" :resource="value" ref="addressForm" :singular-label="label"
                              :uri-key="api"
                              :collection-name="collectionName">
                    <div class="flex px-8 py-4" slot-scope="{form}">
                        <div class="ml-auto">
                            <el-button @click="centerDialogVisible = false">取消</el-button>
                            <el-button type="primary" @click="submit(form)">确定</el-button>
                        </div>
                    </div>
                </address-form>
            </div>

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
      collectionName: {
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
        value: {}
      }
    },

    methods: {
      async submit (form) {
        try {
          await this.$refs['addressForm'].validateForm()
          this.$refs['addressForm'].startLoading()
          if (form.hasOwnProperty('id')) {
            // update
            const {data} = await axios.patch(this.api, form)
            this.value = data.data
            this.notify(data)
          } else {
            const {data} = await axios.post(this.api, form)
            this.value = data.data
            this.notify(data)
          }
          this.centerDialogVisible = false
        } catch (e) {
          this.notify({type: 'error', title: '表单数据不合法'})
        }

        this.$refs['addressForm'].endLoading()
      },

    },

    computed: {
      isEmpty () {
        return _.isEmpty(this.value)
      },
      canUpdate () {
        return _.get(this, 'address.authorize.canUpdate', false)
      },
      addressId () {
        return _.get(this, 'address.id', null)
      },
      dialogTitle () {
        return this.isEmpty ? `创建${this.label}` : `更新${this.label}`
      }
    },

    mounted () {
      this.value = this.address
    }
  }
</script>

<style scoped>

</style>