<template>
    <form-item :title="title" left-center>
        <translation-detail-value
                :attribute="attribute"
                :translations="translations"
                slot="value">
        </translation-detail-value>
    </form-item>
</template>

<script>
  export default {
    name: 'translation-detail-item',
    props: ['title', 'value', 'attribute', 'translations']
  }
</script>

<style scoped>

</style>