export default {
  props: ['uriKey', 'resourceId', 'dataPath'],
  data () {
    return {
      response: {},
      loading: true,
    }
  },
  methods: {
    fetchResources () {
      this.loading = true
      return axios.get(`/${this.uriKey}/${this.resourceId}`).then(({data}) => {
        this.response = data
        this.loading = false
      })
    },
  },
  computed: {
    resources () {
      if (this.dataPath) {
        return _.get(this, `response.${this.dataPath}`, [])
      }
      return _.get(this, 'response.data', [])
    },
  },
  async mounted () {
    await this.fetchResources()
  }
}