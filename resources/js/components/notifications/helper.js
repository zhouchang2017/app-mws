export default {
  props: ['item'],

  inject: ['types'],

  computed:{
    createdAt () {
      return _.get(this, 'item.created_at')
    },
    title () {
      return _.get(_.find(this.types, ['type', this.item.type]), 'name', 'N/A')
    },

  }
}