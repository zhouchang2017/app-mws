export default {
  props: {
    avatarClass:{
      type:String
    },
    image: {
      type: Object
    },
    path: {
      type: String
    },
    url: {
      type: String
    }
  },

  computed: {
    imgUrl () {
      if (this.url) {
        return this.url
      }
      if (this.image) {
        if (this.path) {
          return _.get(this.image, this.path)
        }
        return _.get(this, 'image.image')
      }
    }
  }
}