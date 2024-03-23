import Vue from 'vue'
import VueRouter from 'vue-router'

// 注册 vue-router (需在 router 实例创建之前调用)
Vue.use(VueRouter)

// 引入首页组件
import HelloWorld from '@/components/HelloWorld'
// 导入登录页面
import Login from '@/views/main/login'
// 后台首页
import Index from '@/views/main/index'
// 导入以下页面

import menuList from '@/views/main/menuList.vue'

import schoolList from '@/views/school/list.vue'
// import schoolAdd from '@/page/school/add.vue'
// import schoolEdit from '@/page/school/edit.vue'

//创建并暴露router实例对象，去管理一组一组的路由规则
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '',
            component: HelloWorld,
            children: []
        },
        {
            path: '/login',
            name: 'Login',
            component: Login,
            children: []
        },
        {
            path: '/index',
            name: 'Index',
            component: Index,
        },
        {
            path: '/menuList',
            name: 'menuList',
            component: menuList,
            children: []
        },
        {
            path: '/schoolList',
            name: 'schoolList',
            component: schoolList,
            children: []
        }
    ]
})


export default router;