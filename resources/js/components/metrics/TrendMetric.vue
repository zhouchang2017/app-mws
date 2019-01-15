<template>
    <BaseTrendMetric
        @selected="handleRangeSelected"
        :title="option.name"
        :value="value"
        :chart-data="data"
        :ranges="option.ranges"
        :prefix="prefix"
        :suffix="suffix"
        :selected-range-key="selectedRangeKey"
        :loading="loading"
    />
</template>

<script>
import _ from 'lodash'
import InteractsWithDates from '../../InteractsWithDates'
import BaseTrendMetric from '../../base-components/metrics/TrendMetric'

export default {
    name: 'trend-metric',

    mixins: [InteractsWithDates],

    components: {
        BaseTrendMetric,
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
    },

    data: () => ({
        loading: true,
        value: '',
        data: [],
        prefix: '',
        suffix: '',
        selectedRangeKey: null,
    }),

    created() {
        if (this.hasRanges) {
            this.selectedRangeKey = this.option.ranges[0].value
        }
    },

    mounted() {
        this.fetch()
    },

    methods: {
        handleRangeSelected(key) {
            this.selectedRangeKey = key
            this.fetch()
        },

        fetch() {
            this.loading = true

            this.minimum(axios.get(this.metricEndpoint, this.metricPayload)).then(
                ({
                    data: {
                        value: { labels, trend, value, prefix, suffix },
                    },
                }) => {
                    this.value = value
                    this.labels = Object.keys(trend)
                    this.data = {
                        labels: Object.keys(trend),
                        series: [
                            _.map(trend, (value, label) => {
                                return {
                                    meta: label,
                                    value: value,
                                }
                            }),
                        ],
                    }
                    this.prefix = prefix || ''
                    this.suffix = suffix || ''
                    this.loading = false
                }
            )
        },
    },

    computed: {
        hasRanges() {
            return this.option.ranges.length > 0
        },

        metricPayload() {
            const payload = {
                params: {
                    timezone: this.userTimezone,
                    twelveHourTime: this.usesTwelveHourTime,
                },
            }

            if (this.hasRanges) {
                payload.params.range = this.selectedRangeKey
            }

            return payload
        },

        metricEndpoint() {
          return `/metrics/${this.uriKey}`
        },
    },
}
</script>
