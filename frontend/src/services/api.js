import axios from 'axios'
import _ from 'lodash'
import { notify } from '@kyvg/vue3-notification'

export default {
  // Authentication
  auth: {
    token: (data) => api().get('token', { params: data }),
  },

  // Users
  users: {
    getUsers: (params) => api().get('users', { params }),
    getUser: (id) => api().get(`users/${id}`),
    createUser: (data, headers = {}) => api(headers).post('users', data),
  },

  // Positions
  positions: {
    getPositions: () => api().get('positions'),
  },
}

function api(customHeaders = {}) {
  const instance = axios.create({
    baseURL: import.meta.env.VITE_ASSETS_URL + '/api/v1',
    headers: {
      Accept: 'application/json',
      'Content-Type': 'application/json',
      ...customHeaders,
    },
  })

  instance.interceptors.response.use(
    (response) => {
      return response
    },
    (error) => {
      let errors = error
      if (error.response?.data?.fails) {
        errors = _.values(error.response.data.fails).join('<br>')
      } else {
        errors = error.response.data.message
      }
      notify({
        group: 'messages',
        type: 'error',
        text: errors,
        duration: 20000,
      })
      return Promise.reject(error)
    }
  )

  return instance
}
