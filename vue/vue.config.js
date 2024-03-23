const { defineConfig } = require("@vue/cli-service");
module.exports = defineConfig({
  transpileDependencies: true,
  lintOnSave: false /*关闭语法检查*/,
  // 关闭vue运行过程异常出现的errors页面
  devServer: {
    client: {
      overlay: false
    }
  }
});
