<template>
    <div v-loading="loading" class="px-6 py-4">
        <h3 class="flex mb-3 text-base text-80 font-bold">
            {{ title }}
            <span class="ml-auto font-semibold text-70 text-sm">({{ formattedTotal}} {{__('total')}})</span>
        </h3>

        <div class="overflow-hidden overflow-y-auto max-h-90px">
            <ul class="list-reset">
                <li v-for="item in formattedItems" class="text-xs text-80 leading-normal">
                    <span class="inline-block rounded-full w-2 h-2 mr-2" :style="{
                        backgroundColor: item.color
                    }"/>{{ item.label }} ({{ item.value }} - {{ item.percentage }}%)
                </li>
            </ul>
        </div>

        <div
            ref="chart"
            :class="chartClasses"
            style="width: 90px; height: 90px; right: 20px; bottom: 30px; top: calc(50% + 15px);"
        />
    </div>
</template>

<script>
import Chartist from 'chartist'
import 'chartist/dist/chartist.min.css'

const colorForIndex = index =>
    [
        '#F5573B',
        '#F99037',
        '#F2CB22',
        '#8FC15D',
        '#098F56',
        '#47C1BF',
        '#1693EB',
        '#6474D7',
        '#9C6ADE',
        '#E471DE',
    ][index]

export default {
    name: 'PartitionMetric',

    props: {
        loading: Boolean,
        title: String,
        chartData: Array,
    },

    data: () => ({ chartist: null }),

    watch: {
        chartData: function(newData, oldData) {
            this.renderChart()
        },
    },

    mounted() {
        this.chartist = new Chartist.Pie(this.$refs.chart, this.formattedChartData, {
            donut: true,
            donutWidth: 10,
            donutSolid: true,
            startAngle: 270,
            showLabel: false,
        })
    },

    methods: {
        renderChart() {
            this.chartist.update(this.formattedChartData)
        },
    },

    computed: {
        chartClasses() {
            return ['z-40', 'vertical-center', 'rounded-b-lg', 'ct-chart', this.formattedTotal <= 0 ? 'invisible' : '']
        },

        formattedChartData() {
            return { labels: this.formattedLabels, series: this.formattedData }
        },

        formattedItems() {
            return _(this.chartData)
                .map((item, index) => {
                    return {
                        label: item.label,
                        value: item.value,
                        color: colorForIndex(index),
                        percentage: this.formattedTotal > 0 ? (item.value * 100 / this.formattedTotal).toFixed(2) : '0',
                    }
                })
                .value()
        },

        formattedLabels() {
            return _(this.chartData)
                .map(item => item.label)
                .value()
        },

        formattedData() {
            return _(this.chartData)
                .map(item => item.value)
                .value()
        },

        formattedTotal() {
            return _.sumBy(this.chartData, 'value')
        },
    },
}
</script>
