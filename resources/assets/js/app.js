import Vue from 'vue'
import store from '~/store'
import router from '~/router'
import { i18n } from '~/plugins'
import App from '~/components/App'
import moment from 'moment'

import '~/components'

Vue.config.productionTip = false

new Vue({
  i18n,
  store,
  router,
  filters: {
    formatDate: (date, format) => {
      if (!date) {
        return 'N/A'
      } else {
        return moment(date, 'YYYY-MM-DD').format(format)
      }
    }
  },
  ...App
})

Vue.use(require('vue-moment'))
