<template>
    <div>
      <a href="/#/schoolList"><el-button type="button" size="small" style="float: left;">数据列表</el-button></a>
      <br />
      <br />
      <!--  表单 start https://element.eleme.cn/#/zh-CN/component/form -->
      <el-form :model="dataForm" status-icon ref="dataForm" label-width="100px" class="">
        <el-form-item label="学校名称" prop="name"><el-input type="text" v-model="dataForm.name"></el-input></el-form-item>
        <el-form-item label="所在城市" prop="city"><el-input type="text" v-model="dataForm.city"></el-input></el-form-item>
        <el-form-item label="人数" prop="num"><el-input type="number" v-model.number="dataForm.num"></el-input></el-form-item>
        <el-form-item>
          <el-button type="primary" @click="submitForm()">提交</el-button>
          <el-button @click="resetForm('dataForm')">重置</el-button>
        </el-form-item>
      </el-form>
      <!--  表单 end -->
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        dataForm: {
          name: '',
          city: '',
          num: ''
        }
      };
    },
    methods: {
      submitForm() {
        var _self = this;
        _self.$ajax
          .post('http://0608.cc/school', _self.dataForm)
          .then(function(response) {
            // 不管成功与失败、都弹出消息
            alert(response.data.msg);
            // 判断是否成功、成功跳转至展示页面
            if (response.data.code == 0) {
              _self.$router.push({ name: 'schoolList' });
            }
          })
          .catch(function(error) {
            console.log(error);
          });
      },
      resetForm(formName) {
        this.$refs[formName].resetFields();
      }
    }
  };
  </script>
  
  <style></style>