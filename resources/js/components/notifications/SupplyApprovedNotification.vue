<template>
    <div class="w-full">
        <h1 class="font-sans font-thin mb-4">{{ title }}</h1>

        <p class="text-grey mb-3">{{ createdAt }}</p>

        <!--<h2 class="font-sans font-thin leading-normal mb-4">供应商供货计划描述:{{ description }}</h2>-->
        <!--<h2 class="font-sans font-thin leading-normal mb-4">供应商名称:{{ supplierName }}</h2>-->

        <p class="text-grey-darkest mb-6 leading-tight">{{ description }}</p>
        <div>
            <a :href="link" target="_blank" class="text-primary mr-2">入库计划</a>
            <!--<a href="#" class="text-black">5 COMMENTS</a>-->
        </div>
    </div>
</template>

<script>
  import Helper from './helper'

  export default {
    name: 'supply-approved-notification',

    mixins: [Helper],

    computed:{
      description(){
        return _.get(this,'item.data.description')
      },
      supplyId () {
        return _.get(this, 'item.data.supply_id')
      },

      link(){
        return `/supplies/${this.supplyId}`
      }
    }
  }
</script>

<style scoped>

</style>