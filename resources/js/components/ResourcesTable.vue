<template>
    <div>
        <el-table
                :data="resources"
        >
            <el-table-column
                    prop="description"
                    label="计划描述"
            >
            </el-table-column>
            <el-table-column
                    prop="current_state"
                    label="当前状态"
            >
            </el-table-column>
            <el-table-column
                    fixed="right"
                    label="操作"
            >
                <template slot-scope="{row}">
                    <a class="no-underline dim text-primary font-bold" href="">查看</a>
                    <a class="no-underline dim text-primary font-bold" href="">编辑</a>
                </template>
            </el-table-column>
        </el-table>

        <div class="flex my-3">
            <el-pagination
                    background
                    :current-page.sync="currentPage"
                    :page-size="perPage"
                    layout="prev, pager, next"
                    :total="total">
            </el-pagination>
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
        type: String
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
        await this.fetchVariants()
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