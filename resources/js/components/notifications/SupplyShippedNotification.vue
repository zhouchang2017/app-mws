<template>
    <div class="w-full">
        <h1 class="font-sans font-thin mb-4">{{ title }}</h1>

        <p class="text-grey mb-3">{{ createdAt }}</p>

    </div>
</template>

<script>
  import Helper from './helper'

  export default {
    name: 'supply-shipped-notification',
    mixins: [Helper]
  }
</script>

<style scoped>

</style>