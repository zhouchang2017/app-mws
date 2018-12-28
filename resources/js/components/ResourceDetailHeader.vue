<template>
    <card-title :label-name="labelName">
        <div class="ml-3 w-full flex items-center">
            <div class="flex w-full justify-end items-center"></div>
            <div class="ml-3"><!----> <!----></div>
            <el-popover
                    placement="top"
                    width="160"
                    v-model="visible">
                <p>确定删除吗？</p>
                <div style="text-align: right; margin: 0">
                    <el-button size="mini" type="text" @click="visible = false">取消</el-button>
                    <el-button type="primary" size="mini" :loading="loading" @click="requestDelApi">确定</el-button>
                </div>
                <button slot="reference" v-if="canDestroy" title="Delete"
                        @click.prevent="openDel"
                        class="btn btn-default btn-icon btn-white mr-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                         aria-labelledby="delete" role="presentation" class="fill-current text-80">
                        <path fill-rule="nonzero"
                              d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                    </svg>
                </button>
            </el-popover>

            <!----> <!---->

            <a :href="toEdit" class="btn btn-default btn-icon bg-primary"
               data-testid="edit-resource" dusk="edit-resource-button" title="Edit">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                     aria-labelledby="edit" role="presentation" class="fill-current text-white"
                     style="margin-top: -2px; margin-left: 3px;">
                    <path d="M4.3 10.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H5a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM6 14h2.59l9-9L15 2.41l-9 9V14zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h6a1 1 0 1 1 0 2H2v14h14v-6z"></path>
                </svg>
            </a></div>
    </card-title>
</template>

<script>
  export default {
    name: 'resource-detail-header',
    props: {
      labelName: {
        type: String,
        default: '详情页'
      },
      resourceName: {
        type: String,
        required: true
      },
      resourceId: {
        type: [String, Number]
      },
      canDestroy: {
        type: Boolean,
        default: true
      }
    },
    data () {
      return {
        visible: false,
        loading: false
      }
    },
    methods: {
      requestDelApi () {
        this.loading = true
        axios.delete(this.delApi).then(({data}) => {
          console.log(data)
        })
        this.loading = false
      }
    },
    computed: {
      delApi () {
        return `/${this.resourceName}/${this.resourceId}`
      },
      toEdit () {
        return `/${this.resourceName}/${this.resourceId}/edit`
      }
    }
  }
</script>

<style scoped>

</style>