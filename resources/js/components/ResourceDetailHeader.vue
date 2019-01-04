<template>
    <card-title :label="label">
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
                        class="btn btn-default btn-icon btn-white" :class="{'mr-3':delMr}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                         aria-labelledby="delete" role="presentation" class="fill-current text-80">
                        <path fill-rule="nonzero"
                              d="M6 4V2a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6H1a1 1 0 1 1 0-2h5zM4 6v12h12V6H4zm8-2V2H8v2h4zM8 8a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z"></path>
                    </svg>
                </button>
            </el-popover>

            <!----> <!---->
            <a v-if="canView" :href="toView" class="btn btn-default btn-icon bg-primary" :class="{'mr-3':viewMr}"
               title="View">
                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                     height="24">
                    <path class="heroicon-ui"
                          d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                </svg>
            </a>

            <a v-if="canUpdate" :href="toEdit" class="btn btn-default btn-icon bg-primary"
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
      singularLabel: {
        type: String,
        default: '详情页'
      },
      uriKey: {
        type: String,
        required: true
      },
      resourceId: {
        type: [String, Number]
      },
      canDestroy: {
        type: [Boolean, Number],
        default: true
      },
      canUpdate: {
        type: [Boolean, Number],
        default: true
      },
      canView: {
        type: [Boolean, Number],
        default: false
      },
      labelName:{
        type:String
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
          this.go(`/${this.uriKey}`)
        })
        this.loading = false
      }
    },
    computed: {
      delApi () {
        return `/${this.uriKey}/${this.resourceId}`
      },
      toEdit () {
        return `/${this.uriKey}/${this.resourceId}/edit`
      },
      toView () {
        return `/${this.uriKey}/${this.resourceId}`
      },
      delMr () {
        return this.canView || this.canUpdate
      },
      viewMr () {
        return this.canUpdate
      },
      label () {
        return _.get(this, 'labelName', this.singularLabel + '详情页')
      }
    }
  }
</script>

<style scoped>

</style>