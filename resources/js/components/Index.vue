<template>
    <div>
        <resource-index-header
                :can-create="canCreate"
                :can-search="canSearch"
                :uri-key="uriKey"
                :label="label"
                :singular-label="singularLabel"
        >
        </resource-index-header>
        <resources-table
                :can-view="canView"
                :can-update="canUpdate"
                :can-destroy="canDestroy"
                :uri-key="uriKey"
                :label="label"
                :singular-label="singularLabel"
        >
            <slot></slot>
        </resources-table>
    </div>
</template>

<script>
  export default {
    name: 'index',
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
      // 是否可更新
      canUpdate: {
        type: [Boolean, Number],
        default: false
      },
      // 是否可删除
      canDestroy: {
        type: [Boolean, Number],
        default: false
      },
      // 是否可看详情
      canView: {
        type: [Boolean, Number],
        default: false
      },
    }
  }
</script>

<style scoped>

</style>