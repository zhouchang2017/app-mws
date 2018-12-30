import axios from 'axios'
import { Notification } from 'element-ui'

const instance = axios.create()

instance.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
instance.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector(
  'meta[name="csrf-token"]'
).content

instance.interceptors.response.use(
  response => response,
  error => {
    const { status } = error.response

    // Show the user a 500 error
    if (status >= 500) {
      Notification({
        type:'error',
        title:'ERROR500',
        message:error.response.data.message
      })
    }

    // Handle Session Timeouts
    if (status === 401) {
      Notification({
        type:'error',
        title:'Unauthorized',
        message:error.response.data.message
      })
      // window.location.href = '/'
    }

    // Handle Forbidden
    if (status === 403) {
      Notification({
        type:'error',
        title:'Forbidden',
        message:error.response.data.message
      })
    }

    return Promise.reject(error)
  }
)

export default instance
