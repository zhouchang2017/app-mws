<template>
    <el-cascader
            class="w-full"
            v-model="local"
            :options="options"
            @active-item-change="handleItemChange"
            :props="props"
    ></el-cascader>
</template>

<script>
  export default {
    name: 'area-select-field',

    props: {
      defaultValue: {
        type: Array,
        default: () => []
      }
    },

    data () {
      return {
        options: [],
        local: [],
        props: {
          label: 'name',
          value: 'id',
          children: 'children'
        },
        address: {
          province: null,
          city: null,
          district: null
        }
      }
    },

    watch: {
      local: function (value) {
        if (value.length === 3) {
          this.address.district = (this.getArea(value)).name
          this.$emit('change', this.address)
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
      handleItemChange (value, prop = 'id') {
        if (value.length === 1) {
          this.fetchCities({province_id: _.last(value)}).then(({data}) => {
            const province = _.find(this.options, [prop, _.last(value)])
            if (province) {
              this.address.province = province.name
              province.children = data.map(item => _.tap(item, item => {item.children = []}))
            }
          })
        }
        if (value.length === 2) {
          this.fetchAreas({city_id: _.last(value)}).then(({data}) => {
            const province = _.find(this.options, [prop, _.head(value)])
            const city = _.find(province.children, [prop, _.last(value)])
            this.address.city = city.name
            if (city) {
              city.children = data
            }
          })
        }
      },
      async defaultValueInit () {
        if (this.defaultValue.length === 3) {
          // find province
          const province = _.find(this.options, ['name', _.head(this.defaultValue)])
          if (province) {
            this.address.province = province.name
            this.local[0] = province.id
            const cities = await this.fetchCities({province_id: province.id})
            province.children = cities.data.map(item => _.tap(item, item => {item.children = []}))
            // find city
            const city = _.find(cities.data, ['name', this.defaultValue[1]])
            if (city) {
              this.local[1] = city.id
              this.address.city = city.name
              const areas = await this.fetchAreas({city_id:city.id})
              city.children = areas.data
              // find area
              const area = _.find(areas.data,['name',_.last(this.defaultValue)])
              console.log(area)
              if(area){
                this.local[2] = area.id
                this.address.district = area.name
              }
            }
          }
        }
      }
    },

    async mounted () {
      const {data} = await this.fetchProvinces()
      this.options = data.map(item => _.tap(item, item => {item.children = []}))

      await this.defaultValueInit()
    }

  }
</script>

<style scoped>

</style>