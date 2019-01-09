<template>
    <img class="h-12 w-12 rounded" :class="avatarClass"
         v-lazy="imgUrl"/>
</template>

<script>
  import Imageable from '../../imageable'

  export default {
    name: 'avatar',
    mixins: [Imageable]
  }
</script>

<style scoped>

</style>