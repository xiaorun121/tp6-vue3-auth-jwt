<template>
  <div class="login">
    <el-card class="box-card">
      <el-image :src="logo_src" :fit="contain" class="logo_src"></el-image>
      <el-form ref="form" :model="form" label-width="60px">
        <el-form-item label="用户名">
          <el-input v-model="form.username"></el-input>
        </el-form-item>
        <el-form-item label="密码">
          <el-input v-model="form.password"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm('ruleForm')">登录</el-button>
          <el-button @click="resetForm('ruleForm')">重置</el-button>
        </el-form-item>
      </el-form>
    </el-card>
    <!--  表单 start https://element.eleme.cn/#/zh-CN/component/form -->

    <!--  表单 end -->
  </div>
</template>
  
<script>
import logo_src from "@/assets/img/cat.jpg";
export default {
  data() {
    return {
      logo_src: logo_src,
      form: {
        username: '',
        password: ''
      }
    };
  },
  methods: {
    submitForm() {
      // var _self = this;
      // _self.$ajax
      //   .get('http://0608.cc/login', { params: _self.dataForm })
      //   .then(function (response) {
      //     // 不管成功与失败、都弹出消息
      //     alert(response.data.msg);
      //     // 判断是否成功、成功跳转至展示页面
      //     if (response.data.code == 0) {
      //       _self.$router.push({ name: 'schoolList' });
      //     }
      //   })
      //   .catch(function (error) {
      //     console.log(error);
      //   });
      this.$api.Admin.login(this.form);
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    }
  }
};
</script>
  
<style lang="scss">
.login {
  widows: 100%;
  height: 100%;
  background-image: url("../../assets/img/bg.jpg");
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  background-size: 100% 100%;
}

.logo_src {
  width: 100px;
  height: 100px;
}

.box-card {
  position: absolute;
  left: 0;
  right: 0;
  top: 0;
  bottom: 0;
  margin: auto;
  width: 10%;
  min-width: 300px;
  height: 300px;
  min-height: 300px;
  border-radius: 10px;
  text-align: center;
}

.card-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.text {
  font-size: 14px;
}

.item {
  margin-bottom: 18px;
}
</style>