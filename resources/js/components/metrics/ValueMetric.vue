<template>
    <BaseValueMetric
            @selected="handleRangeSelected"
            :title="option.name"
            :previous="previous"
            :value="value"
            :ranges="option.ranges"
            :prefix="prefix"
            :suffix="suffix"
            :selected-range-key="selectedRangeKey"
            :loading="loading"
    />
</template>

<script>
  import BaseValueMetric from '../../base-components/metrics/ValueMetric'

  export default {
    name: 'value-metric',

    components: {
      BaseValueMetric,
    },

    props: {
      option: {
        type: Object,
        required: true,
      },
      uriKey: {
        type: String,
        required: true,
      },
      metricCode: {
        type: [Number, String],
        required: true,
      },
    },

    data: () => ({
      loading: true,
      value: 0,
      previous: 0,
      prefix: '',
      suffix: '',
      selectedRangeKey: null,
    }),

    created () {
      if (this.hasRanges) {
        this.selectedRangeKey = this.option.ranges[0].value
      }
    },

    mounted () {
      this.fetch(this.selectedRangeKey)
    },

    methods: {
      handleRangeSelected (key) {
        this.selectedRangeKey = key
        this.fetch()
      },

      fetch () {
        this.loading = true

        this.minimum(axios.get(this.metricEndpoint, this.rangePayload)).then(
          ({
             data: {
               value: {value, previous, prefix, suffix},
             },
           }) => {
            this.value = value
            this.prefix = prefix || ''
            this.suffix = suffix || ''
            this.previous = previous
            this.loading = false
          }
        )
      },
    },

    computed: {
      hasRanges () {
        return this.option.ranges.length > 0
      },

      rangePayload () {
        return this.hasRanges ? {params: {range: this.selectedRangeKey}} : {}
      },

      metricEndpoint () {
        return `/metrics/${this.uriKey}/${this.metricCode}`
      },
    },
  }
</script>
