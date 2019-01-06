<template>
    <div>
        <card-title v-if="canSearch" :label="label"></card-title>
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
                            {{ label }}
                        </slot>
                    </h4>
                </div>
            </div>
            <div v-if="canCreate" class="w-full flex items-center mb-3">
                <div class="flex-no-shrink ml-auto">
                    <a class="btn btn-default btn-primary" :href="createLink">{{ '创建'+singularLabel }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'resource-index-header',
    props: {
      // api资源地址
      uriKey:{
        type: String
      },
      // 复数名称
      label:{
        type: String
      },
      // 单数名称
      singularLabel:{
        type: String
      },
      // 是否可搜索
      canSearch: {
        type: [Number, Boolean],
        default: false
      },
      // 是否可创建
      canCreate: {
        type: [Boolean, Number],
        default: false
      },
      viaRelationName: {
        type: String,
        default: null
      },
      viaRelationId: {
        type: [Number, String],
        default: null
      },
      morphType:{
        type: String,
        default: null
      }
    },
    computed: {
      createLink () {
        if (!this.viaRelationName && !this.viaRelationId) {
          return `/${this.uriKey}/create`
        }
        if(this.viaRelationName && this.viaRelationId && this.morphType){
          return `/${this.uriKey}/create?morphType=${this.morphType}&viaRelationName=${this.viaRelationName}&viaRelationId=${this.viaRelationId}`
        }
        return `/${this.uriKey}/create?viaRelationName=${this.viaRelationName}&viaRelationId=${this.viaRelationId}`
      }
    }
  }
</script>

<style scoped>

</style>