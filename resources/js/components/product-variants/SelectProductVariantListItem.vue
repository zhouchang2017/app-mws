<template>
    <div class="w-full flex rounded-lg overflow-hidden border border-50 h-24 text-80 bg-white variant-item cursor-pointer"
         :class="{'variant-item__active':selected}"
         @click="clickHandle"
    >
        <div class="h-auto w-24 flex-none bg-cover text-center overflow-hidden lazy-bg bg-center avatar"
             v-lazy:background-image="bgUrl"
        >
        </div>
        <div class="p-3 w-full">
            <slot :title="resourceTitle" :price="price" :stock="stock">
                <div class="flex flex-col justify-between leading-normal">
                    <div class="font-semibold">{{resourceTitle}}</div>
                    <p class="font-sans">售价 $<b>{{price}}</b></p>
                    <span class="font-mono">库存 <b>{{stock}}</b></span>
                </div>
            </slot>
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
      bgUrl () {
        return 'https://media.wired.com/photos/5b22c5c4b878a15e9ce80d92/master/pass/iphonex-TA.jpg'
      },
      resourceTitle () {
        return _.get(this, 'resource.variantName', '-')
      },
      price () {
        return _.get(this, 'resource.price.price', 'N/A')
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