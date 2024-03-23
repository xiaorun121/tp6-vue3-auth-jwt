// vite.config.ts
import { fileURLToPath, URL } from "node:url";
import { defineConfig } from "file:///E:/phpstudy_pro/WWW/tp6/public/view-ui/node_modules/vite/dist/node/index.js";
import vue from "file:///E:/phpstudy_pro/WWW/tp6/public/view-ui/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import vueJsx from "file:///E:/phpstudy_pro/WWW/tp6/public/view-ui/node_modules/@vitejs/plugin-vue-jsx/dist/index.mjs";
var __vite_injected_original_import_meta_url = "file:///E:/phpstudy_pro/WWW/tp6/public/view-ui/vite.config.ts";
var vite_config_default = defineConfig({
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
    },
    loaderOptions: {
      less: {
        lessOptions: {
          javascriptEnabled: true
        }
      }
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcudHMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJFOlxcXFxwaHBzdHVkeV9wcm9cXFxcV1dXXFxcXHRwNlxcXFxwdWJsaWNcXFxcdmlldy11aVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiRTpcXFxccGhwc3R1ZHlfcHJvXFxcXFdXV1xcXFx0cDZcXFxccHVibGljXFxcXHZpZXctdWlcXFxcdml0ZS5jb25maWcudHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0U6L3BocHN0dWR5X3Byby9XV1cvdHA2L3B1YmxpYy92aWV3LXVpL3ZpdGUuY29uZmlnLnRzXCI7aW1wb3J0IHsgZmlsZVVSTFRvUGF0aCwgVVJMIH0gZnJvbSAnbm9kZTp1cmwnXG5cbmltcG9ydCB7IGRlZmluZUNvbmZpZyB9IGZyb20gJ3ZpdGUnXG5pbXBvcnQgdnVlIGZyb20gJ0B2aXRlanMvcGx1Z2luLXZ1ZSdcbmltcG9ydCB2dWVKc3ggZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlLWpzeCdcblxuLy8gaHR0cHM6Ly92aXRlanMuZGV2L2NvbmZpZy9cbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XG4gIHBsdWdpbnM6IFtcbiAgICB2dWUoKSxcbiAgICB2dWVKc3goKSxcbiAgXSxcbiAgcmVzb2x2ZToge1xuICAgIGFsaWFzOiB7XG4gICAgICAnQCc6IGZpbGVVUkxUb1BhdGgobmV3IFVSTCgnLi9zcmMnLCBpbXBvcnQubWV0YS51cmwpKVxuICAgIH1cbiAgfSxcbiAgY3NzOiB7XG4gICAgLy8gY3NzXHU5ODg0XHU1OTA0XHU3NDA2XHU1NjY4XG4gICAgcHJlcHJvY2Vzc29yT3B0aW9uczoge1xuXHRcdFx0bGVzczoge1xuXHRcdFx0XHRqYXZhc2NyaXB0RW5hYmxlZDogdHJ1ZSxcblx0XHRcdFx0cmV3cml0ZVVybHM6ICdhbGwnLFxuXHRcdFx0XHRtYXRoOiAnYWx3YXlzJyAgLy9cdTVDMzFcdTY2MkZcdThGRDlcdTkxQ0NcdThCQkVcdTdGNkVtYXRoXHU2QTIxXHU1RjBGXG5cdFx0XHR9XG5cdFx0fSxcbiAgICBsb2FkZXJPcHRpb25zOiB7XG4gICAgICBsZXNzOiB7XG4gICAgICAgIGxlc3NPcHRpb25zOiB7XG4gICAgICAgICAgamF2YXNjcmlwdEVuYWJsZWQ6IHRydWVcbiAgICAgICAgfVxuICAgICAgfVxuICAgIH1cbiAgfVxufSlcbiJdLAogICJtYXBwaW5ncyI6ICI7QUFBZ1QsU0FBUyxlQUFlLFdBQVc7QUFFblYsU0FBUyxvQkFBb0I7QUFDN0IsT0FBTyxTQUFTO0FBQ2hCLE9BQU8sWUFBWTtBQUo0SyxJQUFNLDJDQUEyQztBQU9oUCxJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUMxQixTQUFTO0FBQUEsSUFDUCxJQUFJO0FBQUEsSUFDSixPQUFPO0FBQUEsRUFDVDtBQUFBLEVBQ0EsU0FBUztBQUFBLElBQ1AsT0FBTztBQUFBLE1BQ0wsS0FBSyxjQUFjLElBQUksSUFBSSxTQUFTLHdDQUFlLENBQUM7QUFBQSxJQUN0RDtBQUFBLEVBQ0Y7QUFBQSxFQUNBLEtBQUs7QUFBQTtBQUFBLElBRUgscUJBQXFCO0FBQUEsTUFDdEIsTUFBTTtBQUFBLFFBQ0wsbUJBQW1CO0FBQUEsUUFDbkIsYUFBYTtBQUFBLFFBQ2IsTUFBTTtBQUFBO0FBQUEsTUFDUDtBQUFBLElBQ0Q7QUFBQSxJQUNFLGVBQWU7QUFBQSxNQUNiLE1BQU07QUFBQSxRQUNKLGFBQWE7QUFBQSxVQUNYLG1CQUFtQjtBQUFBLFFBQ3JCO0FBQUEsTUFDRjtBQUFBLElBQ0Y7QUFBQSxFQUNGO0FBQ0YsQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
