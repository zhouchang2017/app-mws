export default {
  props: {
    viaRelationName: {
      type: String,
      default: null
    },
    viaRelationId: {
      type: [Number, String],
      default: null
    },
  },
  computed: {
    viaRelationship () {
      return !!(this.viaRelationName && this.viaRelationId)
    }
  }
}