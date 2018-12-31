<template>
    <div>
        <pre-inventory-action-order-check-item

                v-for="item in items"
                :item="item"
                :key="item.id"
        ></pre-inventory-action-order-check-item>
    </div>
</template>

<script>
  export default {
    name: 'pre-inventory-action-order-check',
    props: {
      resource: {
        type: Object,
        default: () => {}
      },
      resourceName: {
        type: String,
        default: 'pre-inventory-action-orders'
      },
      resourceId: {
        type: [String, Number]
      }
    },
    provide () {
      return {
        type: this.resource.type,
        resourceName: this.resourceName,
        resourceId: this.resourceId
      }
    },
    data () {
      return {
        dialogVisible: false
      }
    },
    methods: {
      check (item) {
        console.log(item)
        this.dialogVisible = true
      }
    },
    computed: {
      items () {
        return _.get(this, 'resource.items', [])
      }
    }
  }
</script>

<style scoped>

</style>