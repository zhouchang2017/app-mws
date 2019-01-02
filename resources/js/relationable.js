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
    resourceName: {
      type: String,
      default: null
    },
    viaRelationResource: {
      type: Object,
      default: null
    }
  },
}