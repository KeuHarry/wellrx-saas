import useTenantStore from '@/stores/tenant'
import axios from 'axios'
import Cookies from 'js-cookie'

const tenantStore = useTenantStore()

const axiosTenant = axios.create({
  baseURL: 'http://api.wellrx.localhost/4b17f5e1-cb12-4fad-8453-f397a858f339/v1',
})

// Add a request interceptor to include the X-XSRF-TOKEN header
axiosTenant.interceptors.request.use(
  (config) => {
    // Get the CSRF token from the XSRF-TOKEN cookie
    const token = sessionStorage.getItem('authToken')

    if (token) {
      // Set the X-XSRF-TOKEN header
      config.headers['Authorization'] = `Bearer ${token}`
    }

    return config
  },
  (error) => {
    // Handle request error
    return Promise.reject(error)
  },
)

export default axiosTenant
