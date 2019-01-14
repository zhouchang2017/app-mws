<template>
    <div>
        <el-upload
                :file-list="fileList"
                :data="appendData"
                :headers="headers"
                :name="name"
                :on-success="HandleSuccess"
                :multiple="multiple"
                :action="actionUrl"
                list-type="picture-card"
                :on-preview="handlePictureCardPreview"
                :on-remove="handleRemove">
            <i class="el-icon-plus"></i>
        </el-upload>
        <el-dialog :visible.sync="dialogVisible">
            <img width="100%" v-lazy="dialogImageUrl" alt="">
        </el-dialog>
    </div>
</template>

<script>
  export default {
    name: 'images-upload',

    props: {
      actionUrl: {
        type: String,
        default: '/fs/upload/image'
      },
      multiple: {
        type: Boolean,
        default: false
      },
      name: {
        type: String,
        default: 'image'
      },
      images: {
        type: Array,
        default: () => []
      },
      option: {
        type: Object,
        default: () => {
          return {
            name: 'image',
            url: 'image'
          }
        }
      }
    },

    data () {
      return {
        dialogImageUrl: '',
        dialogVisible: false,
        fileList: []
      }
    },

    watch: {
      fileList: function (value) {
        this.$emit('change',value)
      },
    },

    methods: {
      async handleRemove (file, fileList) {
        await axios.delete('/fs/upload/image', {params: file})
        const index = file.hasOwnProperty('id') ? this.fileList.findIndex(item => item.id === file.id) :
          this.fileList.findIndex(item => item.name === file.response)
        this.fileList.splice(index, 1)
      },
      handlePictureCardPreview (file) {
        this.dialogImageUrl = file.url
        this.dialogVisible = true
      },
      // 上传成功
      HandleSuccess (response) {
        this.fileList.push({
          name: response,
          url:response
        })
      }
    },

    computed: {
      headers () {
        return {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.head.querySelector(
            'meta[name="csrf-token"]'
          ).content
        }
      },
      appendData () {
        return {
          name: this.name
        }
      }
    },

    mounted () {
      this.$nextTick(() => {
        this.fileList = this.images.map(item => {
          return {
            'id': _.get(item, 'id'),
            'name': _.get(item, this.option.name),
            'url': _.get(item, this.option.url),
          }
        })
      })

    }
  }
</script>

<style scoped>

</style>