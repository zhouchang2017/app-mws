<template>
    <div class="card w-full p-6 flex mb-6">
        <div class="w-1/2 border-r border-40 flex items-center">
            <h1 class="font-sans font-bold text-80">余额</h1>
            <h1 class="font-sans font-thin text-primary ml-6">{{value}}</h1>
        </div>
        <div class="w-1/2 flex items-center">
            <div class="ml-auto"
            >
                <button  type="button"
                        class="btn btn-default btn-primary inline-flex items-center relative">
                    <span class="">
                        提现
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'cash-panel',

    data () {
      return {
        value: '0.00'
      }
    },

    methods: {
      fetch () {
        return axios.get('/bills/balance')
      },

      async fillValue () {
        const {data} = await this.fetch()
        this.value = data
      }
    },

    async mounted () {
      await this.fillValue()
    }
  }
</script>

<style scoped>

</style>