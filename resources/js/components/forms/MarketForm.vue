<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">

                <el-form-item :label="singularLabel+'名称'" prop="name">
                    <el-input v-model="form.name"></el-input>
                </el-form-item>

                <el-form-item label="渠道" prop="marketable">
                    <el-select v-model="form.marketable" placeholder="请选择">
                        <el-option-group
                                v-for="group in marketables"
                                :key="group.type"
                                :label="group.type">
                            <el-option
                                    v-for="item in group.values"
                                    :key="item.id"
                                    :label="item.name"
                                    :value="item">
                            </el-option>
                        </el-option-group>
                    </el-select>
                </el-form-item>

                <el-form-item label="Enabled" prop="enabled">
                    <el-switch
                            v-model="form.enabled"
                    >
                    </el-switch>
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
    name: 'market-form',

    mixins: [BaseForm],

    data () {
      return {
        form: {
          name: null,
          marketable: null,
          enabled: true
        },
        marketables: [],
        rules: {
          name: [
            {required: true, message: `请输${this.singularLabel}名称`, trigger: 'blur'},
          ],
          marketable: [
            {required: true, message: `请指定渠道`, trigger: 'change'},
            {type:'object', message: `请指定渠道`, trigger: 'change'},
          ]
        }
      }
    },
    methods: {
      createResourceFormData () {
        const {name,enabled} = this.form
        const {marketable_id, marketable_type} = this.form.marketable
        return {name,enabled,marketable_id, marketable_type}
      },
      updateResourceFormData () {
        const {name,enabled} = this.form
        const {marketable_id, marketable_type} = this.form.marketable
        console.log({name,enabled,marketable_id, marketable_type})
        return {name,enabled,marketable_id, marketable_type}
      },
      fetchMarketables () {
        return axios.get('/markets/marketables')
      },
      async fillMarketables () {
        const {data} = await this.fetchMarketables()
        this.marketables = data
      },
      fillMarketable () {
        this.form.marketable = _.get(this, 'resource.marketable')
      },
    },
    computed: {},
    async created () {
      await this.fillMarketables()
      if (this.updatePage) {
        this.form.name = _.get(this,'resource.name')
        this.form.enabled = _.get(this,'resource.enabled')
        this.fillMarketable()
      }
    }
  }
</script>

<style scoped>

</style>