<template>
    <div class="flex items-center">
        <ul v-if="!empty" class="list-reset flex w-full image-list">
            <li class="mb-3"  v-for="image in images" :key="images.id">
                <thumb-image :height="height" :width="width" :image="image" :path="path"></thumb-image>
            </li>
        </ul>
        <p v-else class="text-80 font-semibold">暂无图片</p>
    </div>
</template>

<script>
  export default {
    name: 'thumb-image-list',
    props: {
      images: {
        type: Array,
        default: () => []
      },
      path: {
        type: String,
        default:'image'
      },
      height:{
        type:Number,
        default:100
      },
      width:{
        type:Number,
        default:100
      }
    },
    computed: {
      empty () {
        return this.images.length === 0
      }
    }
  }
</script>

<style scoped>

</style>