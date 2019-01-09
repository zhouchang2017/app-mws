<template>
    <div>
        <img class="h-12 w-12 rounded" :style="imageStyle" :class="avatarClass"
             v-lazy="imgUrl"/>
    </div>
</template>

<script>
  import Imageable from '../../imageable'

  export default {
    name: 'thumb-image',
    mixins: [Imageable],

    props:{
      height:{
        type:Number,
        default:100
      },
      width:{
        type:Number,
        default:100
      }
    },

    computed:{
      imageStyle(){
        return {
          height:this.height + 'px',
          width:this.width + 'px'
        }
      }
    }
  }
</script>

<style scoped>

</style>