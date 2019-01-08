<template>
    <el-select
            class="w-full"
            v-model="value"
            filterable
            remote
            :disabled="disabled"
            :placeholder="placeholder"
            :remote-method="remoteMethod"
            :loading="remoteLoading">
        <el-option
                v-for="item in options"
                :key="item[keyField]"
                :label="item[labelField]"
                :value="item[valueField]">
            <slot :item="item">

            </slot>
            <!--<div class="flex items-bottom justify-between">-->
            <!--<div class="font-semibold text-xs text-80">{{item.name}}</div>-->
            <!--<div class="text-xs  text-70">{{item.code}}</div>-->
            <!--</div>-->
        </el-option>
        <infinite-loading @infinite="infiniteHandler">
        </infinite-loading>
    </el-select>
</template>

<script>
  import Pageable from '../../pageable'
  import InfiniteLoading from 'vue-infinite-loading'

  export default {
    name: 'remote-select-field',

    mixins: [Pageable],

    components: {
      InfiniteLoading,
    },

    props: {
      placeholder: {
        type: String,
        default: '请选择'
      },
      disabled: {
        type: Boolean,
        default: false
      },
      defaultValue: {
        type: [Object, String, Number]
      },
      defaultOptions: {
        type: Array,
        default: () => []
      },
      uriKey: {
        type: String
      },
      params: {
        type: Object,
        default: () => {}
      },
      keyField: {
        type: String,
        default: 'id'
      },
      labelField: {
        type: String,
        default: 'name'
      },
      valueField: {
        type: String,
        default: 'id'
      }
    },

    data () {
      return {
        value: null,
        options: [],
        response: {},
        remoteLoading: false
      }
    },

    watch: {
      'value': function (value) {
        this.$emit('change', value)
      }
    },

    methods: {
      initValue (value) {
        this.value = value
      },
      async initOptions (options) {
        if (options.length > 0) {
          this.options = options
        } else {
          await this.remoteMethod()
        }
      },
      remoteMethod (query) {
        if (query !== '') {
          this.query = query
          this.currentPage = 1
          this.remoteLoading = true
          axios.get(`/${this.uriKey}`, {params: this.apiParams}).then(({data}) => {
            this.options = data.data
            data.current_page++
            this.response = data
            this.setOptions(data)
            this.remoteLoading = false

          })
        }
      },
      infiniteHandler ($state) {
        axios.get(`/${this.uriKey}`, {params: this.apiParams})
          .then(({data}) => {
            this.response = data
            data.current_page++
            this.setOptions(data)
            this.options.push(...data.data)
            if (data.next_page_url) {
              $state.loaded()
            } else {
              $state.complete()
            }
          })
      },
      getSelectedObject () {
        return this.selected
      }
    },

    computed: {
      apiParams () {
        return Object.assign({}, {
          search: this.query,
          page: this.currentPage
        }, this.params)
      },
      selected () {
        return _.find(this.options, [this.valueField, this.value])
      }
    },

    async mounted () {
      await this.initOptions(this.defaultOptions)
      if (this.defaultValue) {
        this.initValue(this.defaultValue)
      }
    }
  }
</script>

<style scoped>

</style>