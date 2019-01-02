<template>
    <div>
        <card-title v-if="canSearch" :label-name="labelName"></card-title>
        <div class="flex">
            <div class="flex items-center h-9 mb-3 flex-no-shrink">
                <div v-if="canSearch">
                    <el-input

                            placeholder="请输入内容"
                            prefix-icon="el-icon-search"
                    >
                    </el-input>
                </div>
                <div v-else>
                    <h4 class="text-90 font-normal text-2xl flex-no-shrink">
                        <slot name="title">
                            {{ labelName }}
                        </slot>
                    </h4>
                </div>
            </div>
            <div v-if="canCreate" class="w-full flex items-center mb-3">
                <div class="flex-no-shrink ml-auto">
                    <a class="btn btn-default btn-primary" :href="createLink">{{ '创建'+labelName }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'resource-index-header',
    props: {
      canSearch: {
        type: [Number, Boolean],
        default: false
      },
      canCreate: {
        type: [Boolean, Number],
        default: true
      },
      resourceName: {
        type: String,
        required: true
      },
      labelName: {
        type: String,
        default: '创建'
      },
      viaRelationName: {
        type: String,
        default: null
      },
      viaRelationId: {
        type: [Number, String],
        default: null
      }
    },
    computed: {
      createLink () {
        if (!this.viaRelationName && !this.viaRelationId) {
          return `/${this.resourceName}/create`
        }
        return `/${this.resourceName}/create?viaRelationName=${this.viaRelationName}&viaRelationId=${this.viaRelationId}`
      }
    }
  }
</script>

<style scoped>

</style>