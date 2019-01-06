<template>
    <div class="w-full">
        <h1 class="font-sans font-thin mb-4">{{ title }}</h1>

        <p class="text-grey mb-3">{{ createdAt }}</p>

        <div>
            <a :href="link" target="_blank" class="text-primary mr-2">入库计划</a>
        </div>
    </div>
</template>

<script>
  import Helper from './helper'

  export default {
    name: 'supply-un-ship-notification',
    mixins: [Helper],

    computed: {
      description () {
        return _.get(this, 'item.data.description')
      },
      supplyId () {
        return _.get(this, 'item.data.supply_id')
      },

      link () {
        return `/supplies/${this.supplyId}`
      }
    }
  }
</script>

<style scoped>

</style>