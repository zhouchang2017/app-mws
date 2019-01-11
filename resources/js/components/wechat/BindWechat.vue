<template>
    <div>
        <el-button type="text" @click="open">绑定微信</el-button>
        <el-dialog
                v-loading="loading"
                title="扫码绑定微信"
                :visible.sync="centerDialogVisible"
                width="30%"
        >
            <div class="flex items-center justify-center">
                <qriously :value="url" :size="200"/>
            </div>
            <div slot="footer" class="flex">
                <div class="ml-auto">
                    <el-button type="primary" @click="centerDialogVisible = false">关闭</el-button>
                </div>
            </div>
        </el-dialog>
    </div>
</template>

<script>
  import VueQriously from 'vue-qriously'

  export default {
    name: 'bind-wechat',

    components: {
      VueQriously
    },

    data () {
      return {
        loading: true,
        url: null,
        expires: 5,
        timer: null,
        timer1: null,
        init: false,
        centerDialogVisible: false,
        isBind: false
      }
    },

    watch: {
      isBind: function (v) {
        if (v) {
          clearInterval(this.timer1)
        }
      },
      centerDialogVisible: function (v) {
        if (!v) {
          clearInterval(this.timer)
        }
      }
    },

    methods: {
      async open () {
        if (!this.init) {
          await this.fillUrl()
          this.startLoopFetch()
          this.startLoopIsBind()
        }
        this.init = true
        this.centerDialogVisible = true
      },
      fetch () {
        return axios.get('/wechat/bind/create')
      },
      async fillUrl () {
        this.loading = true
        const {data} = await this.fetch()
        this.url = data
        this.loading = false
      },
      async checkIsBind () {
        const {data} = await axios.get('/wechat/bind')
        if (data) {
          this.isBind = true
        }
      },
      setTime () {
        return this.expires * 1000 * 60
      },
      startLoopFetch () {
        this.timer = setInterval(async () => {
          await this.fillUrl()
        }, this.setTime())
      },
      startLoopIsBind () {
        this.timer1 = setInterval(async () => {
          await this.checkIsBind()
        }, 3 * 1000)
      }
    },

    destroyed () {
      if (this.timer) {
        clearInterval(this.timer)
      }
      if (this.timer1) {
        clearInterval(this.timer1)
      }
    }
  }
</script>

<style scoped>

</style>