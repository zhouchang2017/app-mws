<template>
    <div>
        <div class="flex rounded-lg shadow-lg overflow-hidden">

            <!-- Left -->
            <div class="w-1/3  flex flex-col">
                <!-- Header -->
                <div class="py-2 px-3 bg-grey-lighter flex flex-row justify-between items-center">
                    <div>
                        <img class="w-10 h-10 rounded-full" src="http://andressantibanez.com/res/avatar.png"/>
                    </div>

                    <div class="flex">
                        <div class="text-70 hover:text-primary">
                            <svg @click="refresh" class="fill-current cursor-pointer" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 24 24" width="24" height="24">
                                <path class="heroicon-ui"
                                      d="M6 18.7V21a1 1 0 0 1-2 0v-5a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2H7.1A7 7 0 0 0 19 12a1 1 0 1 1 2 0 9 9 0 0 1-15 6.7zM18 5.3V3a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.9A7 7 0 0 0 5 12a1 1 0 1 1-2 0 9 9 0 0 1 15-6.7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <el-dropdown @command="handleCommand" @visible-change="value=>dropDown = value">
                            <span class="text-70 hover:text-primary" :class="{'text-primary' : dropDown}">
                             <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                  height="24"><path class="heroicon-ui"
                                                    d="M4 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0-2a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm8 2a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0-2a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm8 2a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0-2a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg>
                            </span>
                                <el-dropdown-menu slot="dropdown">
                                    <el-dropdown-item :command="null">全部消息</el-dropdown-item>
                                    <el-dropdown-item :command="0">未读消息</el-dropdown-item>
                                    <el-dropdown-item :command="1">已读消息</el-dropdown-item>
                                </el-dropdown-menu>
                            </el-dropdown>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <!--<div class="py-2 px-2 bg-grey-lightest">-->
                <!--<input type="text" class="w-full px-2 py-2 text-sm" placeholder="Search or start new chat"/>-->
                <!--</div>-->

                <!-- Contacts -->
                <div v-loading="loading" class="bg-grey-lighter flex-1 overflow-auto" :style="listHeight">
                    <div v-for="item in items"
                         :key="item.id"
                         :class="listItemClass(item)"
                         class="px-3 flex items-center cursor-pointer"
                         @click="read(item)"
                    >

                        <div class="ml-4 flex-1 py-4 overflow-hidden">
                            <div class="flex items-bottom justify-between">
                                <p class="text-grey-dark mt-1 text-sm">
                                    {{ getTypeName(item.type) }}
                                </p>
                                <p class="text-xs text-grey-darkest">
                                    {{ item.created_at | ago }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <infinite-loading :identifier="infiniteId" @infinite="infiniteHandler">
                    </infinite-loading>
                </div>

            </div>


            <!-- Right -->
            <div class="w-2/3  flex flex-col">

                <!-- Messages -->
                <div class="flex-1 overflow-auto bg-white-50% h-full">
                    <div class="p-6" v-if="readat">
                        <component :is="getTypeComponent(readat.type)" :item="readat"></component>
                    </div>
                    <div class="flex items-center justify-center h-full" v-else>
                        <p class="text-50">未选取任何消息</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  import Pageable from '../pageable'
  import InfiniteLoading from 'vue-infinite-loading'

  export default {
    name: 'notification',

    mixins: [Pageable],

    components: {
      InfiniteLoading,
    },

    provide () {
      return {
        types: this.types
      }
    },

    props: {
      types: {
        type: Array
      }
    },

    data () {
      return {
        dropDown: false,
        is_read: null,
        response: null,
        loading: false,
        readat: null,
        items: [],
        infiniteId: +new Date(),
      }
    },
    watch: {
      is_read: async function (value) {
        this.refresh()
        // await this.fetchNotifications()
      }
    },
    methods: {
      refresh () {
        this.currentPage = 1
        this.items = []
        this.infiniteId += 1
      },
      getTypeComponent (type) {
        return _.last(type.split('\\'))
      },
      getTypeName (value) {
        if (!value) return 'N/A'
        return _.get(_.find(this.types, ['type', value]), 'name', value)
      },
      handleCommand (command) {
        this.is_read = command
      },
      infiniteHandler ($state) {
        this.loading = true
        axios.get('/notifications', {params: this.requestParams})
             .then(({data}) => {
               this.response = data
               data.current_page++
               this.setOptions(data)
               this.items.push(...data.data)
               if (data.next_page_url) {
                 $state.loaded()
               } else {
                 $state.complete()
               }
               this.loading = false
             })
      },
      fetchNotifications () {
        this.readat = null
        this.loading = true
        axios.get('/notifications', {params: this.requestParams})
             .then(({data}) => {
               this.response = data
               this.items.push(...data.data)
               this.setOptions(data)
               this.loading = false
             })
      },
      read (item) {
        this.readat = item
        this.makeAsRead(item)
      },
      makeAsRead (item) {
        if (_.isNull(item.read_at)) {
          axios.patch(`/notifications/${item.id}`).then(({data}) => {
            this.$set(item, 'read_at', dayjs().format('YYYY-MM-DD hh:mm:ss'))
          })
        }
      },
      listItemClass (item) {
        return {
          'bg-grey-light': item.id === _.get(this, 'readat.id'), // 选中高亮
          'bg-white hover:bg-grey-lighter': item.id !== _.get(this, 'readat.id'), // 未选中背景白色,鼠标经过变色
        }
      }
    },
    computed: {
      listHeight () {
        return {height: window.innerHeight * 4 / 6 + 'px'}
      },
      requestParams () {
        const params = {page: this.currentPage}
        if (this.is_read === null) {
          return params
        }
        return Object.assign({}, params, {is_read: this.is_read})
      }
    },
    async mounted () {
      // await this.fetchNotifications()
    }
  }
</script>

<style scoped>

</style>