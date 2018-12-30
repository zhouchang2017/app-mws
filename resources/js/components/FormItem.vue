<template>
    <div class="form-list__item">
        <slot>
            <div class="w-1/5 py-6 px-8">
                {{title}}
            </div>
            <slot name="right">
                <div class="w-4/5 py-6 px-8">
                    <slot name="value">
                        <p class="text-90">
                        {{value || '-'}}
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
    props: ['title', 'value']
  }
</script>

<style scoped>

</style>