<template>
    <div>
        <pre-inventory-action-order-check-item
                v-for="item in items"
                :item="item"
                :key="item.id"
                :attachmentTypes="attachmentTypes"
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
      uriKey: {
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
        uriKey: this.uriKey,
        resourceId: this.resourceId,
      }
    },
    data () {
      return {
        dialogVisible: false,
        attachmentTypes: []
      }
    },
    methods: {
      check (item) {
        console.log(item)
        this.dialogVisible = true
      },
      fetchAttachmentTypes () {
        axios.get('/attachment-types?withoutPage').then(({data}) => {
          this.attachmentTypes = data
        })
      }
    },
    computed: {
      items () {
        return _.get(this, 'resource.items', [])
      }
    },
    async mounted () {
      // if (this.resource.type.action === 'take') {
        await this.fetchAttachmentTypes()
      // }
    }
  }
</script>

<style scoped>

</style>