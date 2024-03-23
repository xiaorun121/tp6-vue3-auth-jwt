// vite.config.ts
import { fileURLToPath, URL } from "node:url";
import { defineConfig } from "file:///E:/phpstudy_pro/WWW/tp6/public/view-ui/node_modules/vite/dist/node/index.js";
import vue from "file:///E:/phpstudy_pro/WWW/tp6/public/view-ui/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import vueJsx from "file:///E:/phpstudy_pro/WWW/tp6/public/view-ui/node_modules/@vitejs/plugin-vue-jsx/dist/index.mjs";
var __vite_injected_original_import_meta_url = "file:///E:/phpstudy_pro/WWW/tp6/public/view-ui/vite.config.ts";
var vite_config_default = defineConfig({
  server: {
    host: "www.tp6.com",
    port: 8080
    // 可根据需要修改端口号
  },
  plugins: [
    vue(),
    vueJsx()
  ],
  resolve: {
    alias: {
      "@": fileURLToPath(new URL("./src", __vite_injected_original_import_meta_url))
    }
  },
  css: {
    // css预处理器
    preprocessorOptions: {
      less: {
        javascriptEnabled: true,
        rewriteUrls: "all",
        math: "always"
        //就是这里设置math模式
      }
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcudHMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJFOlxcXFxwaHBzdHVkeV9wcm9cXFxcV1dXXFxcXHRwNlxcXFxwdWJsaWNcXFxcdmlldy11aVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiRTpcXFxccGhwc3R1ZHlfcHJvXFxcXFdXV1xcXFx0cDZcXFxccHVibGljXFxcXHZpZXctdWlcXFxcdml0ZS5jb25maWcudHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0U6L3BocHN0dWR5X3Byby9XV1cvdHA2L3B1YmxpYy92aWV3LXVpL3ZpdGUuY29uZmlnLnRzXCI7aW1wb3J0IHsgZmlsZVVSTFRvUGF0aCwgVVJMIH0gZnJvbSAnbm9kZTp1cmwnXG5cbmltcG9ydCB7IGRlZmluZUNvbmZpZyB9IGZyb20gJ3ZpdGUnXG5pbXBvcnQgdnVlIGZyb20gJ0B2aXRlanMvcGx1Z2luLXZ1ZSdcbmltcG9ydCB2dWVKc3ggZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlLWpzeCdcblxuLy8gaHR0cHM6Ly92aXRlanMuZGV2L2NvbmZpZy9cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gIHNlcnZlcjoge1xuICAgIGhvc3Q6ICd3d3cudHA2LmNvbScsXG4gICAgcG9ydDogODA4MCwgLy8gXHU1M0VGXHU2ODM5XHU2MzZFXHU5NzAwXHU4OTgxXHU0RkVFXHU2NTM5XHU3QUVGXHU1M0UzXHU1M0Y3XG4gIH0sXG4gIHBsdWdpbnM6IFtcbiAgICB2dWUoKSxcbiAgICB2dWVKc3goKSxcbiAgXSxcbiAgcmVzb2x2ZToge1xuICAgIGFsaWFzOiB7XG4gICAgICAnQCc6IGZpbGVVUkxUb1BhdGgobmV3IFVSTCgnLi9zcmMnLCBpbXBvcnQubWV0YS51cmwpKVxuICAgIH1cbiAgfSxcbiAgY3NzOiB7XG4gICAgLy8gY3NzXHU5ODg0XHU1OTA0XHU3NDA2XHU1NjY4XG4gICAgcHJlcHJvY2Vzc29yT3B0aW9uczoge1xuXHRcdFx0bGVzczoge1xuXHRcdFx0XHRqYXZhc2NyaXB0RW5hYmxlZDogdHJ1ZSxcblx0XHRcdFx0cmV3cml0ZVVybHM6ICdhbGwnLFxuXHRcdFx0XHRtYXRoOiAnYWx3YXlzJyAgLy9cdTVDMzFcdTY2MkZcdThGRDlcdTkxQ0NcdThCQkVcdTdGNkVtYXRoXHU2QTIxXHU1RjBGXG5cdFx0XHR9XG5cdFx0fVxuICB9XG59KVxuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUFnVCxTQUFTLGVBQWUsV0FBVztBQUVuVixTQUFTLG9CQUFvQjtBQUM3QixPQUFPLFNBQVM7QUFDaEIsT0FBTyxZQUFZO0FBSjRLLElBQU0sMkNBQTJDO0FBT2hQLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQzFCLFFBQVE7QUFBQSxJQUNOLE1BQU07QUFBQSxJQUNOLE1BQU07QUFBQTtBQUFBLEVBQ1I7QUFBQSxFQUNBLFNBQVM7QUFBQSxJQUNQLElBQUk7QUFBQSxJQUNKLE9BQU87QUFBQSxFQUNUO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDUCxPQUFPO0FBQUEsTUFDTCxLQUFLLGNBQWMsSUFBSSxJQUFJLFNBQVMsd0NBQWUsQ0FBQztBQUFBLElBQ3REO0FBQUEsRUFDRjtBQUFBLEVBQ0EsS0FBSztBQUFBO0FBQUEsSUFFSCxxQkFBcUI7QUFBQSxNQUN0QixNQUFNO0FBQUEsUUFDTCxtQkFBbUI7QUFBQSxRQUNuQixhQUFhO0FBQUEsUUFDYixNQUFNO0FBQUE7QUFBQSxNQUNQO0FBQUEsSUFDRDtBQUFBLEVBQ0E7QUFDRixDQUFDOyIsCiAgIm5hbWVzIjogW10KfQo=
