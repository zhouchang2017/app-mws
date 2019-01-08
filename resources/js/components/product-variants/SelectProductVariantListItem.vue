<template>
    <div class="px-3 flex items-center h-full cursor-pointer" @click="clickHandle" :class="listItemClass">

        <img class="h-12 w-12 rounded"
             v-lazy="bgUrl"/>

        <div class="ml-4 flex-1 p-1 overflow-hidden">
            <div class="flex items-bottom justify-between">
                <p class="text-grey-darkest font-semibold">
                    {{ resourceTitle }}
                </p>
                <el-tooltip class="item" effect="dark" content="余量" placement="top-start">
                    <p class="text-xs text-grey-darkest">
                        {{ stock }}
                    </p>
                </el-tooltip>
            </div>
            <p class="mt-2 text-grey-darkest font-semibold">
                售价:{{ price }}
            </p>
        </div>
    </div>
</template>

<script>

  export default {
    name: 'select-product-variant-list-item',
    props: {
      selections: {
        type: Array,
        default: () => []
      },
      update: {
        type: Boolean,
        default: false
      },
      resource: {
        type: Object,
        required: true
      },
      channelId: {
        type: [String, Number]
      },
    },

    methods: {
      clickHandle () {
        this.$emit('change', this.resource)
      }
    },

    computed: {
      selected () {
        return this.selections.includes(this.resource)
      },
      listItemClass () {
        return {
          'bg-grey-light': this.selected, // 选中高亮
          'bg-white hover:bg-grey-lighter': !this.selected, // 未选中背景白色,鼠标经过变色
        }
      },
      bgUrl () {
        return 'https://media.wired.com/photos/5b22c5c4b878a15e9ce80d92/master/pass/iphonex-TA.jpg'
      },
      resourceTitle () {
        return _.get(this, 'resource.variantName', '-')
      },
      price () {
        return _.get(_.find(this.resource.dp_prices,['channel_id', this.channelId]), 'price', 'N/A')
      },
      stock () {
        return _.get(this, 'resource.stock')
      }
    }
  }
</script>

<style scoped>
    .variant-item:hover {
        /*box-sizing: content-box;*/
        background-image: linear-gradient(270deg, #21c8f6, #637bff);
        box-shadow: none;
        justify-content: center;
        align-items: center;
        color: white;
    }

    .variant-item:hover .avatar {
        border-radius: 100%;
        width: 4.8rem;
        height: 4.8rem;
        margin-left: 8px;
    }

    .variant-item__active {
        background-image: linear-gradient(270deg, #21c8f6, #637bff);
        box-shadow: none;
        justify-content: center;
        align-items: center;
        color: white;
    }

    .variant-item__active .avatar {
        border-radius: 100%;
        width: 4.8rem;
        height: 4.8rem;
        margin-left: 8px;
    }

</style>