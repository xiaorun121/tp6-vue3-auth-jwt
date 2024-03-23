import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import("../views/layout/Layout.vue"),    // 包含布局的路由
      children: [
        {
          path: 'admin',
          name: 'admin',
          component: () => import("../views/admin/Admin.vue"),
        },
        {
          path: 'menu',
          name: 'menu',
          component: () => import("../views/admin/Menu.vue")
        },
        {
          path: 'role',
          name: 'role',
          component: () => import("../views/admin/Role.vue"),
        },
        {
          path: 'user',
          name: 'user',
          component: () => import("../views/admin/User.vue"),
        },
        {
          path: 'chat',
          name: 'chat',
          component: () => import("../views/admin/Chat.vue"),
        },
        {
          path: 'trace',
          name: 'trace',
          component: () => import("../views/admin/Trace.vue"),
        },
        {
          path: 'logs',
          name: 'logs',
          component: () => import("../views/admin/Logs.vue"),
        },
        {
          path: 'liveUpdateLogs',
          name: 'liveUpdateLogs',
          component: () => import("../views/admin/LiveUpdateLogs.vue"),
        },
        {
          path: 'projectGather',
          name: 'projectGather',
          component: () => import("../views/admin/ProjectGather.vue"),
        },
        {
          path: 'traceListed',
          name: 'traceListed',
          component: () => import("../views/admin/TraceListed.vue"),
        },
        {
          path: 'traceReview',
          name: 'traceReview',
          component: () => import("../views/admin/TraceReview.vue"),
        },
        {
          path: 'gather',
          name: 'gather',
          component: () => import("../views/admin/Gather.vue"),
        },
        {
          path: 'backend',
          name: 'backend',
          component: () => import("../views/admin/Backend.vue"),
        },
        {
          path: 'api',
          name: 'api',
          component: () => import("../views/admin/Api.vue"),
        },
        {
          path: ':catchAll(.*)',
          name: '404',
          component: () => import("../views/exception/404.vue")
        }
      ]
    },
    {
      path: '/login',
      name: 'login',
      component: () => import("../views/admin/Login.vue")
    },
    {
      path: '/:catchAll(.*)',
      name: '404',
      component: () => import("../views/exception/404.vue")
    }
  ]
})




export default router
