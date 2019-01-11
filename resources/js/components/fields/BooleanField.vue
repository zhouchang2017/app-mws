<template>
    <div class="flex items-center">
        <div class="rounded-full mr-2" style="height: 10px; width: 10px;" :class="boolClass"></div>
    </div>
</template>

<script>
  export default {
    name: 'boolean-field',
    props: ['value','trueValue','falseValue'],

    computed:{
      boolClass(){
        return this.value === this.trueValue ? 'bg-green' : 'bg-red'
      }
    }
  }
</script>

<style scoped>

</style>