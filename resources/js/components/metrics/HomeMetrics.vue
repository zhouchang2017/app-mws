<template>
    <div>
        <component
                :class="`w-${metric.width} mb-3`"
                v-for="metric in metrics"
                :key="metric.uriKey"
                :is="metric.component"
                :uriKey="metric.uriKey"
                :option="metric"
        >
        </component>
    </div>
</template>

<script>
  export default {
    name: 'home-metrics',

    data () {
      return {
        metrics: []
      }
    },

    methods: {
      async availableMetrics () {
        const {data} = await axios.get('metrics')
        this.metrics = data
      }
    },

    async mounted () {
      await this.availableMetrics()
    }
  }
</script>

<style scoped>

</style>