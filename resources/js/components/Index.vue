<template>
    <div>
        <resource-index-header
                :can-create="canCreate"
                :can-search="canSearch"
                :can-view="canView"
                :can-update="canUpdate"
                :can-destroy="canDestroy"
                :resource-name="resourceName"
                :label-name="labelName"
                :via-relation-name="viaRelationName"
                :via-relation-id="viaRelationId"
        >
        </resource-index-header>
        <resources-table :resource-name="resourceName">
            <slot></slot>
        </resources-table>
    </div>
</template>

<script>
  export default {
    name: 'index',
    props: {
      uriKey:{
        type: String
      },
      label:{
        type: String
      },
      singularLabel:{
        type: String
      },
      resourceName: {
        type: String,
        required: true
      },
      labelName: {
        type: String,
        default: '创建'
      },
      canSearch: {
        type: [Number, Boolean],
        default: false
      },
      canCreate: {
        type: [Boolean, Number],
        default: false
      },
      canUpdate: {
        type: [Boolean, Number],
        default: false
      },
      canDestroy: {
        type: [Boolean, Number],
        default: false
      },
      canView: {
        type: [Boolean, Number],
        default: false
      },
      viaRelationName: {
        type: String,
      },
      viaRelationId: {
        type: [Number, String]
      }
    }
  }
</script>

<style scoped>

</style>