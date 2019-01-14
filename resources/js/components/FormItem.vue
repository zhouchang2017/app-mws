<template>
    <div class="form-list__item">
        <slot>
            <div class="w-1/5 py-6 px-8" :class="leftAppendClass">
                {{title}}
            </div>
            <slot name="right">
                <div class="w-4/5 py-6 px-8">
                    <slot name="value">
                        <p class="text-90" v-if="!isRelation">
                            {{value || '-'}}
                        </p>
                        <p class="text-90" v-else>
                            <a :href="relationLink" class="text-primary no-underline dim text-primary font-bold">{{value
                                || '-'}}</a>
                        </p>
                    </slot>
                </div>
            </slot>
        </slot>
    </div>
</template>

<script>
  export default {
    name: 'FormItem',
    props: {
      title: {
        type: String
      },
      value: {
        type: String
      },
      leftCenter: {
        type: Boolean,
        default: false
      },
      uriKey: {
        type: String
      },
      resourceId: {
        type: [Number, String]
      },
      url: {
        type: String
      }
    },
    computed: {
      leftAppendClass () {
        return {
          'flex items-center': this.leftCenter
        }
      },
      isRelation () {
        if (this.url) {
          return true
        }
        return !!(this.uriKey && this.resourceId)
      },
      relationLink () {
        if (this.url) {
          return this.url
        }
        return `/${this.uriKey}/${this.resourceId}`
      }
    }

  }
</script>

<style scoped>

</style>