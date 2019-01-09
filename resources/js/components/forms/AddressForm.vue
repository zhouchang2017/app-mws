<template>
    <div>
        <el-form-item label="联系人" prop="address.name">
            <el-input placeholder="请输入联系人"
                      v-model="address.name"></el-input>
        </el-form-item>
        <el-form-item label="座机" prop="address.tel">
            <el-input placeholder="请输入座机"
                      v-model="address.tel"></el-input>
        </el-form-item>
        <el-form-item label="手机" prop="address.phone">
            <el-input placeholder="请输入手机"
                      v-model="address.phone"></el-input>
        </el-form-item>
        <el-form-item label="传真" prop="address.fax">
            <el-input placeholder="请输入传真"
                      v-model="address.fax"></el-input>
        </el-form-item>
        <el-form-item label="邮编" prop="address.zip">
            <el-input placeholder="请输入邮编"
                      v-model="address.zip"></el-input>
        </el-form-item>
        <!--<el-form-item label="国家" prop="address.country">-->
        <!--<el-input disabled placeholder="请输入国家"-->
        <!--v-model="address.country"></el-input>-->
        <!--</el-form-item>-->

        <el-form-item label="省市区" prop="address.province">
            <el-cascader
                    class="w-full"
                    v-model="local"
                    :options="options"
                    @active-item-change="handleItemChange"
                    :props="props"
            ></el-cascader>

            <!--<el-input placeholder="请输入省份"-->
            <!--v-model="address.province"></el-input>-->
        </el-form-item>
        <!--<el-form-item label="城市" prop="address.city">-->
        <!--<el-input placeholder="请输入城市"-->
        <!--v-model="address.city"></el-input>-->
        <!--</el-form-item>-->
        <!--<el-form-item label="行政区" prop="address.district">-->
        <!--<el-input placeholder="请输入行政区"-->
        <!--v-model="address.district"></el-input>-->
        <!--</el-form-item>-->
        <el-form-item label="详细地址" prop="address.address">
            <el-input placeholder="请输入详细地址"
                      v-model="address.address"></el-input>
        </el-form-item>
    </div>
</template>

<script>
  export default {
    name: 'address-form',
    props: {
      collectName: {
        type: String,
        default: null
      },
      address: {
        type: Object,
        default: () => {
          return {
            name: null,
            tel: null,
            phone: null,
            fax: null,
            zip: null,
            country: '中国',
            province: null,
            city: null,
            district: null,
            address: null
          }
        }
      }
    },
    // model: {
    //   prop: 'address',
    //   event: 'input'
    // }
    data () {
      return {
        options: [],
        local: [],
        props: {
          label: 'name',
          value: 'id',
          children: 'children'
        }
      }
    },

    watch: {
      local: function (value) {
        if (value.length === 3) {
          this.address.district = (this.getArea(value)).name
        }
      }
    },

    methods: {
      fetchProvinces (params = null) {
        return axios.get('/divisions/provinces?orderBy=id', {params})
      },
      fetchCities (params = null) {
        return axios.get('/divisions/cities?orderBy=id', {params})
      },
      fetchAreas (params = null) {
        return axios.get('/divisions/areas?orderBy=id', {params})
      },
      getArea (local) {
        if (local.length === 3) {
          const province = _.find(this.options, ['id', _.head(local)])
          const city = _.find(province.children, ['id', local[1]])
          return _.find(city.children, ['id', _.last(local)])
        }
      },
      handleItemChange (value) {
        if (value.length === 1) {
          this.fetchCities({province_id: _.last(value)}).then(({data}) => {
            const province = _.find(this.options, ['id', _.last(value)])
            if (province) {
              this.address.province = province.name
              province.children = data.map(item => _.tap(item, item => {item.children = []}))
            }
          })
        }
        if (value.length === 2) {
          this.fetchAreas({city_id: _.last(value)}).then(({data}) => {
            const province = _.find(this.options, ['id', _.head(value)])
            const city = _.find(province.children, ['id', _.last(value)])
            this.address.city = city.name
            if (city) {
              city.children = data
            }
          })
        }
      }
    },

    async mounted () {
      const {data} = await this.fetchProvinces()
      this.options = data.map(item => _.tap(item, item => {item.children = []}))
      this.country = '中国'
      if(this.collectName){
        this.address.collect_name = this.collectName
      }
    }
  }
</script>

<style scoped>

</style>