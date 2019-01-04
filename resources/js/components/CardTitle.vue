<template>
    <div class="flex items-center mb-3">
        <h4 class="text-90 font-normal text-2xl flex-no-shrink">
            <slot name="title">
                {{ label }}
            </slot>
        </h4>
        <slot/>
    </div>
</template>

<script>
  export default {
    name: 'card-title',
    props: ['label']
  }
</script>

<style scoped>

</style>