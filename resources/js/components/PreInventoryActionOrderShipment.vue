<template>
    <div>
        <card-title :label-name="`创建物流信息(${staticResource.type.name})`">
            <div v-if="routeName==='detail'" class="ml-3 w-full flex items-center">
                <div class="flex w-full justify-end items-center"></div>
                <div class="ml-3"><!----> <!----></div>
                <button slot="reference" title="Update" type="button"
                        @click="routeName = 'update'"
                        class="btn btn-default btn-icon btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                         aria-labelledby="edit" role="presentation" class="fill-current text-white"
                         style="margin-top: -2px; margin-left: 3px;">
                        <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                    </svg>
                </button>

            </div>
        </card-title>
        <div class="form-list mb-6 p-0" v-loading="loading">
            <div class="p-6">
                <form-item title="计划说明" :value="staticResource.description"></form-item>
                <form-item title="运输方式" :value="hasShip"></form-item>
                <form-item title="最后更新时间" :value="staticResource.updated_at"></form-item>
                <form-item title="客户地址" :value="staticResource.simple_address"></form-item>
                <form-item title="物流状态" :value="trackStatus"></form-item>
                <form-item title="产品明细">
                    <product-variant-list slot="value" :items="staticResource.items"></product-variant-list>
                </form-item>
            </div>
        </div>

        <card-title label="物流信息">
        </card-title>
        <div class="form-list  p-0" v-loading="loading">
            <div class="p-6">
                <template v-if="routeName === 'detail'">
                    <template v-for="track in staticResource.tracks">
                        <form-item title="物流公司" :value="getLogistic(track.id).name"></form-item>
                        <form-item title="物流单号" :value="track.tracking_number"></form-item>
                        <form-item title="快递费" :value="track.price"></form-item>
                        <form-item title="备注" :value="track.description"></form-item>
                    </template>
                </template>
                <el-form v-else v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                         label-width="180px">
                    <el-form-item label="物流公司" prop="logistic_id">
                        <el-select v-model="form.logistic_id" name="logistic_id" value="id" filterable
                                   placeholder="请选择物流公司">
                            <el-option
                                    v-for="item in logistic"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item.id">
                            </el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="快递单号" prop="tracking_number">
                        <el-input v-model="form.tracking_number" name="tracking_number"
                                  placeholder="请输入快递单号"></el-input>
                    </el-form-item>
                    <el-form-item label="快递费" prop="price">
                        <el-input v-model.number="form.price" name="price"
                                  placeholder="请输入快递费"></el-input>
                    </el-form-item>
                    <el-form-item label="备注" prop="description">
                        <el-input v-model="form.description"
                                  name="description"
                                  type="textarea"
                                  placeholder="可输入物流备注"></el-input>
                    </el-form-item>
                </el-form>
            </div>
            <div v-if="routeName === 'create'" class="bg-30 flex px-8 py-4">
                <button :disable="loading" @click="shipment" type="button"
                        class="ml-auto btn btn-default btn-primary inline-flex items-center relative">
                <span class="">
                    提交
                </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'pre-inventory-action-order-shipment',
    props: {
      resource: {
        type: Object,
        default: () => {}
      },
      logistic: {
        type: Array,
        default: () => []
      },
      postApi: {
        type: String,
        required: true
      },
    },
    data () {
      return {
        logistic_id: null,
        tracking_number: null,
        loading: false,
        update: false,
        routeName: '',
        staticResource: {},
        form: {
          logistic_id: null,
          tracking_number: null,
          price:null,
          description:null
        },
        rules: {
          logistic_id: [
            {required: true, message: '请选择物流公司', trigger: 'change'},
          ],
          tracking_number: [
            {required: true, message: '请输入物流单号', trigger: 'blur'},
          ],
          price:[
            {type:'number',message: '请输入正确金额', trigger: 'blur'}
          ],
          description:[
            {type:'string',max:255,message: '内容长度溢出', trigger: 'blur'}
          ]
        }
      }
    },
    methods: {
      shipment () {
        this.$refs['form'].validate(async (valid) => {
          if (valid) {
            this.loading = true

            axios.post(this.postApi, this.form).then(({data}) => {
              this.notify(data)
              this.staticResource = data.data
              this.routeName = 'detail'
            }).catch (e=>{
              this.notify({type: 'error', title: 'ERROR',message:e.response})
            })
            this.loading = false
            // this.state.push(data.data)
            // this.resetForm()
          } else {
            this.notify({type: 'error', title: '表单数据不合法'})
            return false
          }
        })
      },
      resetForm () {
        this.$refs['form'].resetFields()
      },
      getLogistic (id) {
        return _.find(this.logistic, ['id', +id])
      }
    },
    computed: {
      hasShip () {
        return _.get(this, 'staticResource.has_ship', true) ? '物流/快递' : '无需物流'
      },
      trackStatus () {
        return this.shipped ?
          '已发货'
          :
          '待发货'
      },
      shipped () {
        return (_.get(this, 'staticResource.tracks', [])).length > 0
      }
    },
    created () {
      this.staticResource = this.resource
    },
    mounted () {
      this.routeName = this.shipped ? 'detail' : 'create'
    }
  }
</script>

<style scoped>

</style>