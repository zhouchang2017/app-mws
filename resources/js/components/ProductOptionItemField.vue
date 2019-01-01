<template>
    <div>
        <button
                class="btn-outline inline-flex items-center rounded-lg cursor-pointer text-primary-70% hover:text-primary">
            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                 height="24">
                <path class="heroicon-ui"
                      d="M17 11a1 1 0 0 1 0 2h-4v4a1 1 0 0 1-2 0v-4H7a1 1 0 0 1 0-2h4V7a1 1 0 0 1 2 0v4h4z"/>
            </svg>
        </button>
        <el-checkbox-group v-model="value">
            <el-checkbox v-for="optionValue in option.values" :label="optionValue"
                         :key="optionValue.id">{{optionValue.value}}
            </el-checkbox>
        </el-checkbox-group>
    </div>
</template>

<script>
  export default {
    name: 'product-option-item-field',
    props:{
      value:{
        type:Array
      },
      option:{
        type:Object
      }
    },
    model:{
      prop:'value',
      event:'change'
    },
    methods:{
      change(value){
        console.log(value)
      }
    }
  }
</script>

<style scoped>

</style>