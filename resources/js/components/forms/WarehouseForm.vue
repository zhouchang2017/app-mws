<template>
    <div>
        <div class="p-6">
            <el-form v-loading="loading" ref="form" :rules="rules" :model="form" label-position="left"
                     label-width="180px">
                <p class="text-xs font-semibold text-50">{{ singularLabel }}基本信息</p>
                <div class="border-30 border-b mb-3"></div>
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
                    <el-select
                            v-model="form.user_id"
                            filterable
                            remote
                            placeholder="请选择"
                            :remote-method="remoteUsers"
                            :loading="remoteLoading">
                        <el-option
                                v-for="item in users"
                                :key="item.id"
                                :label="item.name"
                                :value="item.id">
                            <!--<div class="flex items-bottom justify-between">-->
                            <!--<div class="font-semibold text-xs text-80">{{item.name}}</div>-->
                            <!--<div class="text-xs  text-70">{{item.code}}</div>-->
                            <!--</div>-->
                        </el-option>
                        <infinite-loading @infinite="infiniteHandler">
                        </infinite-loading>
                    </el-select>
                </el-form-item>

                <p class="text-xs font-semibold text-50">{{ singularLabel }}地址</p>
                <div class="border-30 border-b mb-3"></div>

                <address-form :address.sync="form.address"></address-form>

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
  import Pageable from '../../pageable'
  import InfiniteLoading from 'vue-infinite-loading'

  export default {
    name: 'warehouse-form',
    mixins: [BaseForm, Pageable],
    components: {
      InfiniteLoading,
    },
    data () {
      return {
        form: {
          name: null,
          type_id: null,
          user_id: null,
          address:{
            name:null,
            tel:null,
            phone:null,
            fax:null,
            zip:null,
            country:null,
            province:null,
            city:null,
            district:null,
            address:null
          }
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
        users: [],
        query: null,
        remoteLoading: false,
        response: null
      }
    },

    methods: {
      remoteUsers (query) {
        if (query !== '') {
          this.query = query
          this.currentPage = 1
          this.remoteLoading = true
          axios.get(`/users?search=${query}&page=${this.currentPage}`).then(({data}) => {
            this.users = data.data
            data.current_page++
            this.response = data
            this.setOptions(data)
            this.remoteLoading = false
          })
        }
      },
      infiniteHandler ($state) {
        const params = {search: this.query}
        if (_.isNull(this.query)) {
          this.currentPage++
          delete params.search
        }
        this.fetchUsers(Object.assign({}, params, {page: this.currentPage}))
             .then(({data}) => {
               this.response = data
               data.current_page++
               this.setOptions(data)
               this.users.push(...data.data)
               if (data.next_page_url) {
                 $state.loaded()
               } else {
                 $state.complete()
               }
             })
      },
      fetchWarehouseTypes () {
        return axios.get('/warehouse-types?withoutPage')
      },
      fetchUsers (params = null) {
        return axios.get('/users',{params})
      },
      async fillWarehouseTypes () {
        const {data} = await this.fetchWarehouseTypes()
        this.types = data
      },
      async fillUsers (search = null) {
        const {data} = await this.fetchUsers({search})
        this.users = data.data
      }
    },
    async created () {
      await Promise.all([
        this.fillWarehouseTypes(),
        this.fillUsers(_.get(this, 'resource.admin.email',null))
      ])
      if (this.updatePage) {
        this.form.name = _.get(this, 'resource.name')
        this.form.type_id = _.get(this, 'resource.type_id')
        this.form.user_id = _.get(this, 'resource.user_id')
        this.form.address = _.get(this,'resource.address',{})
      }
    }
  }
</script>

<style scoped>

</style>