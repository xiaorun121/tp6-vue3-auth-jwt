import Vue from 'vue'
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import axios from 'axios'
import App from './App.vue'

import api from './api/api';
Vue.use(api);

import router from './router'; // 导入 router 实例
// 只需导入 router 文件夹，默认就会导入该文件里面的 index.js 文件

Vue.prototype.$ajax = axios

Vue.use(ElementUI);

Vue.config.productionTip = false

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')
