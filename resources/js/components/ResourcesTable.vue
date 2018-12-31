<template>
    <div>
        <div class="flex">
            <div class="relative h-9 mb-6 flex-no-shrink">
                <el-input
                        placeholder="请输入内容"
                        prefix-icon="el-icon-search"
                >
                </el-input>
            </div>
            <div v-if="canCreate" class="w-full flex items-center mb-6">
                <div class="flex-no-shrink ml-auto">
                    <a class="btn btn-default btn-primary" :href="`/${resourceName}/create`">{{ labelName }}</a>
                </div>
            </div>

        </div>
        <div class="card p-6 w-full">
            <el-table
                    :data="resources"
                    v-loading="loading"
            >
                <slot/>
                <el-table-column
                        align="right"
                        fixed="right"
                        label="操作"
                >
                    <template slot-scope="{row}">
                        <a v-if="canView" class="cursor-pointer text-70 hover:text-primary" :class="{'mr-3':canUpdate}" :href="`/${resourceName}/${row.id}`">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                 height="24">
                                <path class="heroicon-ui"
                                      d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                            </svg>
                        </a>
                        <a v-if="canUpdate" class="cursor-pointer text-70 hover:text-primary" :href="`/${resourceName}/${row.id}/edit`">
                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                 height="24">
                                <path class="heroicon-ui"
                                      d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/>
                            </svg>
                        </a>
                    </template>
                </el-table-column>
            </el-table>
            <div class="flex  my-3 items-center">
                <span class="text-sm text-80">{{ perTotal }}/{{total}}</span>
                <el-pagination
                        class="ml-auto"
                        background
                        :current-page.sync="currentPage"
                        :page-size="perPage"
                        layout="prev, pager, next"
                        :total="total">
                </el-pagination>
            </div>

        </div>

    </div>

</template>

<script>
  import Pageable from '../pageable'

  export default {
    name: 'resources-table',

    mixins: [Pageable],

    props: {
      resourceName: {
        type: String,
        required: true
      },
      labelName: {
        type: String,
        default: '创建'
      },
      canCreate: {
        type: [Boolean,Number],
        default: true
      },
      canUpdate:{
        type: [Boolean,Number],
        default: true
      },
      canDestroy:{
        type: [Boolean,Number],
        default: false
      },
      canView:{
        type: [Boolean,Number],
        default: true
      }
    },

    data () {
      return {
        response: {},
        loading: true,
      }
    },

    methods: {
      fetchResources () {
        this.loading = true
        return axios.get(`/${this.resourceName}?page=${this.currentPage}`).then(({data}) => {
          this.response = data
          this.setOptions(data)
          this.loading = false
        })
      },
    },
    watch: {
      currentPage: async function (val) {
        await this.fetchResources()
      }
    },

    computed: {
      resources () {
        return _.get(this, 'response.data', [])
      },
    },
    async mounted () {
      await this.fetchResources()
    }
  }
</script>

<style scoped>

</style>