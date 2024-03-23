import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'

// https://vitejs.dev/config/
export default defineConfig({
  base: '/',
  server: {
    host: 'www.tp6.com',
    port: 8080, // 可根据需要修改端口号
  },
  plugins: [
    vue(),
    vueJsx(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  css: {
    // css预处理器
    preprocessorOptions: {
      less: {
        javascriptEnabled: true,
        rewriteUrls: 'all',
        math: 'always'  //就是这里设置math模式
      }
    }
  },
  build: {
    sourcemap: true,
    outDir: 'distp', //指定输出路径
    assetsDir: 'static/img/', // 指定生成静态资源的存放路径
    rollupOptions: {
      output: {
        manualChunks(id) {
          if (id.includes('node_modules')) {
            const arr = id.toString().split('node_modules/')[1].split('/')
            switch (arr[0]) {
              case '@naturefw': // 自然框架
              case '@popperjs':
              case '@vue':
              case 'element-plus': // UI 库
              case '@element-plus': // 图标
                return '_' + arr[0]
                break
              default:
                return '__vendor'
                break
            }
          }
        },
        chunkFileNames: 'static/js1/[name]-[hash].js',
        entryFileNames: 'static/js2/[name]-[hash].js',
        assetFileNames: 'static/[ext]/[name]-[hash].[ext]'
      },
    }
  },
})
