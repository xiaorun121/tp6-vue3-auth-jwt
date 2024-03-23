// import './assets/main.css'

import { createApp } from 'vue'

import { createPinia } from 'pinia'
import ViewUIPlus from 'view-ui-plus'
// import { Button, Table } from 'view-ui-plus'
import locale from 'view-ui-plus/dist/locale/en-US';

import App from './App.vue'
import router from './router'

import api from '@/api/api';

import 'view-ui-plus/dist/styles/viewuiplus.css'

import '../theme/index.less'

import Index from '@/views/admin/Index.vue'
import NoAuth from '@/views/exception/403.vue'


// 创建一个新的 store 实例 
// Vue 中的“Store”通常指 Vuex，它是一个专为 Vue.js 应用程序开发的状态管理模式。使用 Vuex 可以更好地管理 Vue 应用程序中的共享状态，包括组件之间的通信、异步操作等。
import { createStore } from 'vuex'
const store = createStore({
    state () {
      return {
        menu: ''
      }
    },
    mutations: {
      increment (state) {
        state.menu
      },
      menuListLeft (state,menuListLeftData){
        state.menu = menuListLeftData
      }
    }
})

const app = createApp(App)

// vue 界面 <script setup> 就不能使用this.$api. 只能单独引用 import api ... 使用api.
app.config.globalProperties.$api = api;

// app.component('Button', Button);
// app.component('Table', Table);

app.use(createPinia())  
app.use(router)


app.use(store)


// 全局组件 在vue界面中 <Index /> 使用
app.component('Index',Index)
app.component('NoAuth',NoAuth)

app.config.warnHandler = () => null

app.use(ViewUIPlus, {
    transfer: true,
    size: 'large',
    capture: false,
    select: {
        arrow: 'md-arrow-dropdown',
        arrowSize: 20
    },
    locale
})

app.mount('#app')
