export default {
  data () {
    return {
      pageOption: {
        current_page: 1,
        first_page_url: null,
        from: 1,
        last_page: 1,
        last_page_url: null,
        next_page_url: null,
        path: null,
        per_page: 15,
        prev_page_url: null,
        to: 1,
        total: 1
      }
    }
  },
  methods: {
    setCurrentPage (page = 1) {
      this.$set(this, 'pageOption.current_page', page)
    },
    setOptions (options) {
      const {data, ...option} = options
      this.pageOption = Object.assign({}, this.pageOption, option)
    },
    incPage () {
      this.setCurrentPage(this.currentPage + 1)
    }
  },
  computed: {
    currentPage: {

      get: function () {
        return _.get(this, 'pageOption.current_page')
      },
      // setter
      set: function (newValue) {
        this.pageOption.current_page = newValue
      }

    },
    perTotal () {
      return _.get(this, 'pageOption.to', 0)
    },
    perPage () {
      return _.get(this, 'pageOption.per_page')
    },
    total () {
      return _.get(this, 'pageOption.total')
    }
  }
}